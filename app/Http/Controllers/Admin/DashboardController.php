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
    public function dashboard(Request $request) {
        try {
            $report = $request->query('report', 7);
        
            $days = [];
            $base_prices = [];
            $final_prices = [];

            $report_start_date = date('Y-m-d H:i:s', time() - ($report * 86400));
            $report_result = Order::where('status', OrderStatus::PAYMENT_SUCCESSFUL->value)
                ->where('created_at', '>=', substr($report_start_date, 0, 10))
                ->selectRaw('SUM(base_price) AS SUM_BASE_PRICE, SUM(final_price) AS SUM_FINAL_PRICE, SUBSTR(created_at, 1, 10) AS DAY')
                ->groupByRaw('DAY')
                ->get()
                ->toArray();
            
            $days = json_encode(array_merge($days, array_column($report_result, 'DAY')));
            $base_prices = json_encode(array_merge($base_prices, array_column($report_result, 'SUM_BASE_PRICE')));
            $final_prices = json_encode(array_merge($final_prices, array_column($report_result, 'SUM_FINAL_PRICE')));

            $successfulOrders = Order::where('status', OrderStatus::PAYMENT_SUCCESSFUL->value);
            $totalRevenueWithOutDiscount = $successfulOrders->sum('base_price');
            $totalRevenue = $successfulOrders->sum('final_price');
            $numberOfSuccessfulOrders = $successfulOrders->count();
            $numberOfUsers = User::count();

            return view('admin.dashboard', compact('report', 'days', 'base_prices', 'final_prices', 'totalRevenueWithOutDiscount', 'totalRevenue', 'numberOfUsers', 'numberOfSuccessfulOrders'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'error');
        }
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
