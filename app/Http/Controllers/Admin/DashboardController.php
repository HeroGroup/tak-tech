<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Models\Mailbox;
use App\Models\Order;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function notifications() {
        try {
            $notifications = Mailbox::where('user_id', auth()->user()->id)->orderByDesc('id')->get();

            return view('admin.notifications', compact('notifications'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function orders($filter, $userId=null) {
        try {
            $orders = Order::with('user');
            $filters = $filter;

            $numberOfSuccessfulPayments = Order::where('status', OrderStatus::PAYMENT_SUCCESSFUL->value);
            $numberOfFailedPayments = Order::where('status', OrderStatus::PAYMENT_FAILED->value);
            $numberOfPendingPayments = Order::where('status', OrderStatus::PENDING->value);

            if ($filter && $filter != 'all') {
                $orders = $orders->where('status', $filter);
            }

            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $orders = $orders->where('user_id', $userId);
                    $filters .= ', User: ' . $user->email;

                    $numberOfSuccessfulPayments = $numberOfSuccessfulPayments->where('user_id', $userId);
                    $numberOfFailedPayments = $numberOfFailedPayments->where('user_id', $userId);
                    $numberOfPendingPayments = $numberOfPendingPayments->where('user_id', $userId);
                }
            }

            $orders = $orders->orderBy('created_at', 'desc')->get();

            $numberOfSuccessfulPayments = $numberOfSuccessfulPayments->count();
            $numberOfFailedPayments = $numberOfFailedPayments->count();
            $numberOfPendingPayments = $numberOfPendingPayments->count();

            return view('admin.orders', 
                compact('orders', 'numberOfSuccessfulPayments', 'numberOfFailedPayments', 'numberOfPendingPayments', 'userId', 'filters'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }
}
