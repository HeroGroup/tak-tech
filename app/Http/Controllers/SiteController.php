<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\TransactionType;
use App\Enums\TransactionReason;
use App\Enums\TransactionStatus;
use App\Http\Controllers\Admin\DiscountController;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserCart;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;

require_once app_path('Helpers/utils.php');

class SiteController extends Controller
{
    public function index() {
        try {
            $products = Product::where('products.is_active', 1)
                ->join('services', 'services.product_id', '=', 'products.id')
                ->where('services.is_sold', 0)
                ->selectRaw('products.*, COUNT(*) AS CNT')
                ->groupByRaw('`products`.`id`, `products`.`title`, `products`.`description`, `products`.`image_url`, `products`.`price`, `products`.`is_active`, `products`.`is_featured`, `products`.`period`, `products`.`iType`, `products`.`allowed_traffic`, `products`.`maximum_connections`')
                ->get();

            $cart = "{}";

            if (auth()->user()) {
                $userCart = UserCart::where('user_id', auth()->user()->id)->first(['cart']);
                if ($userCart) {
                    $cart = $userCart->cart;
                }
            }
            
            return view('site.index', compact('products', 'cart'));
        } catch (\Exeption $exception) {
            return $this->fail($exception->getMessage());
        }
        
    }

    public function addToCart(Request $request) {
        try {
            $userId = auth()->user()?->id;
            if ($userId) {
                // $cart = json_decode($request->cart, true);
                $userCart = UserCart::updateOrCreate(
                    ['user_id' => $userId],
                    ['cart' => $request->cart]
                );

                return $this->success('cart updated successfully.');
            } else {
                return $this->fail('invalid user');
            }
        } catch (\Exeption $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function cart() {
        try {
            $cart = "{}";

            if (auth()->user()) {
                $userCart = UserCart::where('user_id', auth()->user()->id)->first(['cart']);
                if ($userCart) {
                    $cart = $userCart->cart;
                }
            }
            return view('site.cart', compact('cart'));
        } catch (\Exeption $exception) {
            return $this->fail($exception->getMessage());
        }
    }

    public function submitOrder(Request $request) {
        $uid = generateUID();
        $status = 'fail';
        $message = '';
        $now_ts = time();
        $zipName = resource_path("confs/$now_ts.zip");

        $order = Order::create([
            'uid' => $uid
        ]);
        $now = date('Y-m-d H:i:s', time());

        try {
            if ($request->discountCode) {
                $discountController = new DiscountController;
                $checkDiscountResult = $discountController->checkDiscountCode($request->discountCode, 'array');
                
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
            
            $cart = json_decode($request->cart);

            foreach($cart as $key => $value) {
                // $key is product_id
                $product = Product::find($key);
                $count = $value->count;

                $orderDetail = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'count' => $count,
                    'product_title' => $product->title,
                    'product_description' => $product->description,
                    'product_base_price' => $product->price,
                ];

                // implement discount code details if exist
                $productFinalPrice = $product->price;
                if (isset($discountDetails)) {
                    foreach ($discountDetails as $discountDetail) {
                        if ($key == $discountDetail->product_id) {
                            if ($discountDetail->discount_percent) {
                                $productFinalPrice = $productFinalPrice * (100 - $discountDetail->discount_percent);
                            } else if ($discountDetail->fixed_amount) {
                                $productFinalPrice = $productFinalPrice - $discountDetail->fixed_amount;
                            }
                            $orderDetail['discount_detail_id'] = $discountDetail->id;
                        }
                    }
                }
                $orderDetail['product_final_price'] = $productFinalPrice;

                $order_detail_record = OrderDetail::create($orderDetail);

                $services = [];
                $files = [];
                for ($i=0; $i < $count; $i++) { 
                    $service = Service::where('product_id', $product->id)->where('is_sold', 0)->first();
                    array_push($services, $service->id);
                    array_push($files, $service->qr_file);
                    array_push($files, $service->conf_file);
                    $service->update([
                        'is_sold' => 1,
                        'sold_at' => $now,
                        'order_detail_id' => $order_detail_record->id,
                        'activated_date' => $now
                    ]);
                }

                $basePriceSum += $product->price * $count;
                $finalPriceSum += $productFinalPrice * $count;
            }
            
            if (isset($discount)) {
                // maniuplate order final price
                if ($discount->discount_percent) {
                    $finalPriceSum = $finalPriceSum * (100 - $discount->discount_percent);
                } else if ($discount->fixed_amount) {
                    $finalPriceSum = $finalPriceSum - $discount->fixed_amount;
                }
                $order->discount_id = $discount->id;
            }

            // unreal transactions
            // کسر موجودی کیف پول در صورت وجود
            $amountToCharge = $finalPriceSum - auth()->user()?->wallet;

            $chargeTransaction = new Transaction;
            $chargeTransaction->title = 'شارژ کیف پول';
            $chargeTransaction->amount = $amountToCharge;
            $chargeTransaction->type = TransactionType::INCREASE->value;
            $chargeTransaction->reason = TransactionReason::CHARGE->value;
            $chargeTransaction->status = TransactionStatus::PAYMENT_SUCCESSFUL->value;

            $payTransaction = new Transaction;
            $payTransaction->title = 'پرداخت سفارش '.$order->uid;
            $payTransaction->amount = $finalPriceSum;
            $payTransaction->type = TransactionType::DECREASE->value;
            $payTransaction->reason = TransactionReason::PAYMENT->value;
            $payTransaction->status = TransactionStatus::PAYMENT_SUCCESSFUL->value;

            if (auth()->user()) {
                $userId = auth()->user()->id;

                $order->user_id = $userId;
                $chargeTransaction->user_id = $userId;
                $payTransaction->user_id = $userId;
                $serviceUpdate['owner'] = $userId;
                if (auth()->user()->user_type !== 'customer') {
                    $serviceUpdate['activated_at'] = NULL;
                }
                Service::whereIn('id', $services)->update($serviceUpdate);
                
                // delete cart from db
                UserCart::where('user_id', $userId)->delete();

                // check if first purchase and user has invitee
                if (auth()->user()->invitee) {
                    $numberOfOrders = Order::where(['user_id' => $userId, 'status' => OrderStatus::PAYMENT_SUCCESSFUL->value])->count();
                    if ($numberOfOrders == 0) {
                        // find invitee and give reward
                        $invitee = User::find(auth()->user()->invitee);
                        // ToDo: do the reward action
                        Transaction::create([
                            'user_id' => $invitee->id,
                            'title' => 'شارژ کیف پول',
                            'description' => 'شارژ کیف پول بابت دعوت از دوستان',
                            'amount' => env('INVITE_CHARGE_AMOUNT'),
                            'type' => TransactionType::INCREASE->value,
                            'reason' => TransactionReason::CHARGE->value,
                            'status' => TransactionStatus::PAYMENT_SUCCESSFUL->value,
                            'is_reward' => 1
                        ]);
                    }
                }
            }
            
            $chargeTransaction->save();
            $payTransaction->save();
            
            $order->base_price = $basePriceSum;
            $order->final_price = $finalPriceSum;
            $order->status = OrderStatus::PAYMENT_SUCCESSFUL->value;
            $order->transaction_id = $payTransaction->id;
            $order->save();

            $zipResult = createZip($files, $zipName, $now_ts);
            if ($zipResult['status'] == 1) {
                // create download link to $zipResult['file']
            } else {
                $message .= $zipResult['message'];
            }
            
            $status = 'success';
            $message .= 'سفارش با موفقیت ثبت شد!';
        } catch (\Exception $exception) {
            $message = $exception->getLine().': '.$exception->getMessage();
            // rollback everything
            if ($order && $order->id) {
                // delete from order items
                OrderDetail::where('order_id', $order->id)->delete();

                $pay_transaction_id = $order->transaction_id;
                // delete order
                $order->delete();

                // delete transactions
                Transaction::where('id', $pay_transaction_id)->delete();
            }
        } finally {
            return view('site.final', compact('status', 'message', 'now_ts'));
        }
    }

    public function downloadZip($name)
    {
        return response()->download(resource_path("confs/$name.zip"));
    }
}
