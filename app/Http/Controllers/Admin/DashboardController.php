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
        $successfulOrders = Order::where('status', OrderStatus::PAYMENT_SUCCESSFUL->value);
        $totalRevenue = $successfulOrders->sum('final_price');
        $numberOfSuccessfulOrders = $successfulOrders->count();
        $numberOfUsers = User::count();

        return view('admin.dashboard', compact('totalRevenue', 'numberOfUsers', 'numberOfSuccessfulOrders'));
    }

    public function notifications() {
        try {
            $notifications = Mailbox::where('user_id', auth()->user()->id)->orderByDesc('id')->get();

            return view('admin.notifications', compact('notifications'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function orders(Request $request) {
        try {
            $filter = $request->query('filter');
            $userId = $request->query('userId');
            $fromDate = $request->query('fromDate');
            $toDate = $request->query('toDate');
            
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

            if ($fromDate && $fromDate != 'null') {
                $orders = $orders->where('created_at', '>=' ,$fromDate);
                $filters .= ', From: ' . $fromDate;

                $numberOfSuccessfulPayments = $numberOfSuccessfulPayments->where('created_at', '>=' ,$fromDate);
                $numberOfFailedPayments = $numberOfFailedPayments->where('created_at', '>=' ,$fromDate);
                $numberOfPendingPayments = $numberOfPendingPayments->where('created_at', '>=' ,$fromDate);
            }
            
            if ($toDate && $toDate != 'null') {
                $orders = $orders->where('created_at', '<=' ,$toDate);
                $filters .= ', Until: ' . $toDate;

                $numberOfSuccessfulPayments = $numberOfSuccessfulPayments->where('created_at', '<=' ,$toDate);
                $numberOfFailedPayments = $numberOfFailedPayments->where('created_at', '<=' ,$toDate);
                $numberOfPendingPayments = $numberOfPendingPayments->where('created_at', '<=' ,$toDate);
            }

            $orders = $orders->orderBy('created_at', 'desc')->get();

            $numberOfSuccessfulPayments = $numberOfSuccessfulPayments->count();
            $numberOfFailedPayments = $numberOfFailedPayments->count();
            $numberOfPendingPayments = $numberOfPendingPayments->count();

            return view('admin.orders', 
                compact('orders', 'numberOfSuccessfulPayments', 'numberOfFailedPayments', 'numberOfPendingPayments', 'userId', 'filter', 'filters', 'fromDate', 'toDate'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }
}
