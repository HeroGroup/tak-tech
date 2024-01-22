<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Mailbox;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\UpdatePassword;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use UpdatePassword;

    public function dashboard()
    {
        return view('customer.dashboard');
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
            return back()->with('message', $exception->getMessage())->with('type', 'error');
        }
    }

    public function orders() {
        try {
            $orders = Order::where('user_id', auth()->user()->id)->orderByDesc('id')->get();

            return view('customer.orders', compact('orders'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'error');
        }
    }

    public function showOrder($uid) {
        try {
            $order = Order::where(['uid' => $uid, 'user_id' => auth()->user()->id])->first();

            return view('customer.order', compact('order'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'error');
        }
    }

    public function transactions() {
        try {
            $transactions = Transaction::where('user_id', auth()->user()->id)->orderByDesc('id')->get();

            return view('customer.transactions', compact('transactions'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'error');
        }
    }

    public function notifications() {
        try {
            $notifications = Mailbox::where('user_id', auth()->user()->id)->orderByDesc('id')->get();

            return view('customer.notifications', compact('notifications'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'error');
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
}
