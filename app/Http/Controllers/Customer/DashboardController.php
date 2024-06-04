<?php

namespace App\Http\Controllers\Customer;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SiteController;
use App\Models\LoginSession;
use App\Models\Mailbox;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Service;
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

    public function services() {
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

    public function transactions() {
        try {
            $transactions = Transaction::where('user_id', auth()->user()->id)->orderByDesc('id')->get();

            return view('customer.transactions', compact('transactions'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
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

            return view('customer.invite', compact('numberOdInvitedPeople', 'reward'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }
}
