<?php

namespace App\Http\Controllers\Customer;

use App\Enums\OrderStatus;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\Admin\DiscountController;
use App\Models\LoginSession;
use App\Models\Mailbox;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceRenew;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\UpdatePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

require_once app_path('Helpers/utils.php');

class DashboardController extends Controller
{
    use UpdatePassword;

    public function dashboard()
    {
        $user_id = auth()->user()->id;
        $l_session = LoginSession::where('user_id', $user_id)->orderByDesc('created_at')->first();
        $active_services_count = Service::where('owner', $user_id)->where('is_enabled', 1)->count();
        $inactive_services_count = Service::where('owner', $user_id)->where('is_enabled', 0)->count();
        $orders_count = Order::where('user_id', $user_id)->where('status', OrderStatus::PAYMENT_SUCCESSFUL->value)->count();
        
        return view('customer.dashboard', compact('l_session', 'active_services_count', 'inactive_services_count', 'orders_count'));
    }

    public function profile()
    {
        $user = auth()->user();
        return view('customer.profile', compact('user'));
    }

    public function updateProfile(Request $request) {
        try {
            $user = User::find(auth()->user()->id);

            if ($request->name) {
                $user->name = $request->name; 
            }

            if ($request->mobile) {
                $user->mobile = $request->mobile; 
            }

            $user->save();

            return back()->with('message', 'پروفایل با موفقیت بروزرسانی شد.')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->with('message', $wxception->getMessage())->with('type', 'danger');
        }
        
    }

    public function updatePassword(Request $request) {
        try {
            $user = User::find(auth()->user()->id);
            
            $result = $this->updateUserPassword($request, $user);

            return back()->with('message', $result['message'])->with('type', $result['type']);
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function orders() {
        try {
            $orders = Order::where('user_id', auth()->user()->id)->orderByDesc('id')->get();

            return view('customer.orders', compact('orders'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function showOrder($uid) {
        try {
            $order = Order::where(['uid' => $uid, 'user_id' => auth()->user()->id])->first();

            return view('customer.order', compact('order'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function services()
    {
        try {
            $services = Service::where('owner', auth()->user()->id)->orderByDesc('id')->get();

            return view('customer.services', compact('services'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function updateServiceNote(Request $request, $id) {
        try {
            Service::where('id', $id)->where('owner', auth()->user()->id)->update(['note' => $request->note]);

            return back()->with('message', 'توضیحات سرویس با موفقیت به روزرسانی شد.')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function downloadService($id, $files='all') {
        try {
            $service = Service::where('id', $id)->where('owner', auth()->user()->id)->first();

            if ($files == 'all' && $service->conf_file && $service->qr_file) {
                $now_ts = time();
                $zipName = resource_path("confs/$now_ts.zip");
                $files = [$service->conf_file, $service->qr_file];
                $zipResult = createZip($files, $zipName, $now_ts);
                if ($zipResult['status'] == 1) {
                    $sc = new SiteController();
                    return $sc->downloadZip($now_ts);
                }
            } else if ($files == 'conf' && $service->conf_file) {
                return response()->download($service->conf_file);
            }
        } catch (\Exception $exception) {
            //
        }
    }

    public function renewService(Request $request)
    {
        try {
            $service_id = $request->id;
            $cart = json_decode($request->cart);
            $discountCode = $request->discountCode;

            if ($discountCode) {
                $discountController = new DiscountController;
                $checkDiscountResult = $discountController->checkDiscountCode($discountCode, 'array');
                
                if ($checkDiscountResult['status'] == 1) {
                    if (isset($checkDiscountResult['data']['discountDetails']) && count($checkDiscountResult['data']['discountDetails']) > 0) {
                        $discountDetails = $checkDiscountResult['data']['discountDetails'];
                    } else {
                        $discount = $checkDiscountResult['data']['discount'];
                    }
                }
            }

            $basePriceSum = 0;
            $finalPriceSum = 0;

            foreach($cart as $key => $value) {
                // $key is product_id
                $product = Product::find($key);
                $count = $value->count;

                // implement discount code details if exist
                $productFinalPrice = $product->price;
                if (isset($discountDetails)) {
                    foreach ($discountDetails as $discountDetail) {
                        if ($key == $discountDetail->product_id) {
                            if ($discountDetail->discount_percent) {
                                $productFinalPrice = $productFinalPrice * (100 - $discountDetail->discount_percent) / 100;
                            } else if ($discountDetail->fixed_amount) {
                                $productFinalPrice = $productFinalPrice - $discountDetail->fixed_amount;
                            }
                        }
                    }
                }
                $productFinalPrice = $productFinalPrice < 0 ? 0 : $productFinalPrice;

                $basePriceSum += $product->price * $count;
                $finalPriceSum += $productFinalPrice * $count;
            }

            if (isset($discount)) {
                if ($discount->discount_percent) {
                    $finalPriceSum = $finalPriceSum * (100 - $discount->discount_percent) / 100;
                } else if ($discount->fixed_amount) {
                    $finalPriceSum = $finalPriceSum - $discount->fixed_amount;
                }
                $finalPriceSum = $finalPriceSum < 0 ? 0 : $finalPriceSum;
            }

            $amount = ($finalPriceSum - auth()->user()?->wallet)*10;
            
            session(['service_id' => $service_id, 'service_amount' => $finalPriceSum]);

            if ($amount > 0) {
                // redirect to bank
                $pay_url = env('PAY_URL');
                return "$pay_url?amount=$amount&description=$service_id&reason=renew";
            }

            return "/renew/payResult?order_id=$service_id&status=OK&ref_id=";
        } catch (\Exception $exception) {
            return $exception->getLine().': '.$exception->getMessage();
        }
    }

    public function renewPayResult(Request $request)
    {
        $status = 'fail';
        $message = '';
        $service_id = session('service_id', $request->query('order_id'));
        $service = Service::find($service_id);
        $user_id = $service->owner;
        $service_expire_days = $service->expire_days;
        $service_amount = session('service_amount');
        $pay_status = $request->query('status');
        $pay_transaction = '';

        try {
            if ($pay_status == 'NOK') {
                $message = $request->query('message');
                return view('site.final', compact('status', 'message'));
            } else {
                $ref_id = $request->query('ref_id');

                // کسر موجودی کیف پول در صورت وجود
                $amountToCharge = $service_amount - auth()->user()?->wallet;
                
                if ($amountToCharge > 0) {
                    $chargeTransaction = Transaction::create([
                        'title' => 'شارژ کیف پول',
                        'amount' => $amountToCharge,
                        'type' => TransactionType::INCREASE->value,
                        'reason' => TransactionReason::CHARGE->value,
                        'status' => TransactionStatus::PAYMENT_SUCCESSFUL->value,
                        'user_id' => $user_id,
                        'description' => "شماره پیگیری: $ref_id"
                    ]);
                }

                $payTransaction = Transaction::create([
                    'title' => 'تمدید سرویس '.$service_id,
                    'amount' => $service_amount,
                    'type' => TransactionType::DECREASE->value,
                    'reason' => TransactionReason::PAYMENT->value,
                    'status' => TransactionStatus::PAYMENT_SUCCESSFUL->value,
                    'user_id' => $user_id,
                ]);

                // check if service is already expired
                $expire = Product::find($service->product_id)->duration ?? 30;
                $diff = strtotime($service->activated_at. " + $service_expire_days days") - time(); 
                if ($diff >= 0) { // not expired yet
                    $service->update([
                        'expire_days' => $service_expire_days + $expire
                    ]);
                } else { // expired
                    $total_past = explode('.', round(-$diff / (60 * 60 * 24), 2));
                    $days_past = $total_past[0] ?? 0;
                    $service->update([
                        'expire_days' => $service_expire_days + $expire + $days_past
                    ]);
                }

                // api call to update service expire_days
                $data = [
                    'token' => env('API_CALL_TOKEN'),
                    'peer_id' => $service->panel_peer_id,
                    'add_days' => $expire + $days_past
                ];

                $api_call = api_call('POST', env('PANEL_URL').'/wiregaurd/peers/renew', $data, true);

                // insert in service_renews
                ServiceRenew::create([
                    'service_id' => $service_id, 
                    'add_days' => $expire + $days_past,
                    'api_call_status' => $api_call['status'],
                    'api_call_message' => $api_call['message'],
                ]);

                $status = 'success';
                $message .= 'سرویس با موفقیت تمدید شد!';

                return view('site.final', compact('status', 'message', 'ref_id'));
            }
        } catch (\Exception $exception) {
            // delete pay transaction
            $pay_transaction = Transaction::find($pay_transaction->id);
            $pay_transaction_amount = $pay_transaction->amount;
            $pay_transaction->delete();

            // update wallet
            $user = User::find($user_id);
            $user_wallet = $user->wallet ?? 0;
            $user->wallet = $user_wallet + $pay_transaction_amount;
                        
            // undo services allocations
            Service::where('id', $service_id)->update([
                'expire_days' => $service_expire_days,
            ]);
        }
    }

    public function transactions() {
        try {
            $transactions = Transaction::where('user_id', auth()->user()->id)->orderByDesc('id')->get();

            return view('customer.transactions', compact('transactions'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function increaseWalletAmount(Request $request)
    {
        try {
            $user_id = auth()->user()->id;
            $charge_amount = $request->amount;
            if ($charge_amount > 0) {
                $chargeTransaction = Transaction::create([
                    'title' => 'شارژ کیف پول',
                    'amount' => $charge_amount,
                    'type' => TransactionType::INCREASE->value,
                    'reason' => TransactionReason::CHARGE->value,
                    'status' => TransactionStatus::PENDING->value,
                    'user_id' => $user_id,
                    // 'description' => "شماره پیگیری: $ref_id"
                ]);
                // redirect to bank
                $pay_url = env('PAY_URL');
                $amount_rial = $charge_amount * 10;
                return redirect("$pay_url?amount=$amount_rial&description=$chargeTransaction->id&reason=wallet");
            }

            return back()->with('message', 'مبلغ نامعتبر')->with('type', 'danger');
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
        
    }

    public function walletPayResult(Request $request)
    {
        $status = 'fail';
        $message = '';
        $user_id = auth()->user()->id;
        $charge_transaction_id = $request->query('order_id');
        $charge_transaction = Transaction::find($charge_transaction_id);
        $charge_amount = $charge_transaction->amount ?? 0;

        $pay_status = $request->query('status');
        try {
            if ($pay_status == 'NOK') {
                $message = $request->query('message');
                return view('site.final', compact('status', 'message'));
            } else {
                $ref_id = $request->query('ref_id');
                
                if ($charge_transaction && $charge_amount > 0) {
                    $charge_transaction->status = TransactionStatus::PAYMENT_SUCCESSFUL->value;
                    $charge_transaction->description = "شماره پیگیری: $ref_id";
                    $charge_transaction->save();

                    // update user wallet
                    $user_wallet = auth()->user()->wallet;
                    User::where('id', auth()->user()->id)
                        ->update([
                            'wallet', $user_wallet + $charge_amount
                        ]);
                }

                $status = 'success';
                $charge_amount_formatted = number_format($charge_amount);
                $message .= "موجودی کیف پول با موفقیت به اندازه $charge_amount_formatted تومان افزایش یافت.";

                return view('site.final', compact('status', 'message', 'ref_id'));
            }
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            return view('site.final', compact('status', 'message'));
        }
    }

    public function notifications() {
        try {
            $notifications = Mailbox::where('user_id', auth()->user()->id)->orderByDesc('id')->get();

            return view('customer.notifications', compact('notifications'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }
    
    public function markAllAsRead() {
        try {
            Mailbox::where(['user_id' => auth()->user()->id, 'is_read' => 0])
                ->update(['is_read' => 1]);

            return $this->success('all messages marked as read.');
        } catch (\Exception $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function invite() {
        try {
            $userId = auth()->user()->id;
            $numberOdInvitedPeople = User::where('invitee', $userId)->count();;
            $reward = Transaction::where(['user_id' => $userId, 'is_reward' => 1])->sum('amount');
            $app_url = env('APP_URL');

            return view('customer.invite', compact('numberOdInvitedPeople', 'reward', 'app_url'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }
}
