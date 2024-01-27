<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TransactionReason;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Models\Mailbox;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

require_once app_path('/Helpers/utils.php');

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $filter = $request->query('filter');
            $userId = $request->query('userId');
            $fromDate = $request->query('fromDate');
            $toDate = $request->query('toDate');

            $transactions = Transaction::with('user');
            $filters = $filter;

            $numberOfSuccessfulPayments = Transaction::where('status', TransactionStatus::PAYMENT_SUCCESSFUL->value);
            $numberOfFailedPayments = Transaction::where('status', TransactionStatus::PAYMENT_FAILED->value);
            $numberOfPendingPayments = Transaction::where('status', TransactionStatus::PENDING->value);

            if ($filter && $filter != 'all') {
                $transactions = $transactions->where('status', $filter);
            }

            if ($userId) {
                $user = User::find($userId);
                if ($user) {
                    $transactions = $transactions->where('user_id', $userId);
                    $filters .= ', User: ' . $user->email;

                    $numberOfSuccessfulPayments = $numberOfSuccessfulPayments->where('user_id', $userId);
                    $numberOfFailedPayments = $numberOfFailedPayments->where('user_id', $userId);
                    $numberOfPendingPayments = $numberOfPendingPayments->where('user_id', $userId);
                }
            }

            if ($fromDate && $fromDate != 'null') {
                $transactions = $transactions->where('created_at', '>=' ,$fromDate);
                $filters .= ', From: ' . $fromDate;

                $numberOfSuccessfulPayments = $numberOfSuccessfulPayments->where('created_at', '>=' ,$fromDate);
                $numberOfFailedPayments = $numberOfFailedPayments->where('created_at', '>=' ,$fromDate);
                $numberOfPendingPayments = $numberOfPendingPayments->where('created_at', '>=' ,$fromDate);
            }
            
            if ($toDate && $toDate != 'null') {
                $transactions = $transactions->where('created_at', '<=' ,$toDate);
                $filters .= ', Until: ' . $toDate;

                $numberOfSuccessfulPayments = $numberOfSuccessfulPayments->where('created_at', '<=' ,$toDate);
                $numberOfFailedPayments = $numberOfFailedPayments->where('created_at', '<=' ,$toDate);
                $numberOfPendingPayments = $numberOfPendingPayments->where('created_at', '<=' ,$toDate);
            }

            $transactions = $transactions->orderBy('created_at', 'desc')->get();
            $users = User::where('is_active', 1)->pluck('email', 'id')->toArray();

            $numberOfSuccessfulPayments = $numberOfSuccessfulPayments->count();
            $numberOfFailedPayments = $numberOfFailedPayments->count();
            $numberOfPendingPayments = $numberOfPendingPayments->count();

            return view('admin.transactions', 
                compact('transactions', 'users', 'numberOfSuccessfulPayments', 'numberOfFailedPayments', 'numberOfPendingPayments', 'userId', 'filter', 'filters', 'fromDate', 'toDate'));
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'title' => ['required'],
                'reason' => ['required'],
                'amount' => ['required'],
                'from' => ['required'],
                'to' => ['required'],
            ]);

            $transactionObject = [
                'title' => $request->title,
                'amount' => $request->amount,
                'reason' => TransactionReason::TRANSFER->value,
                'status' => TransactionStatus::PAYMENT_SUCCESSFUL->value,
                'transfer_token' => rand_string(9),
            ];
            
            $toUser = User::find($request->to);
            Transaction::create([
                ...$transactionObject,
                'user_id' => $request->from,
                'type' => TransactionType::DECREASE->value,
                'description' => 'انتقال به کیف پول ' . $toUser?->email,
            ]);
            
            Transaction::create([
                ...$transactionObject,
                'user_id' => $request->to,
                'type' => TransactionType::INCREASE->value,
                'description' => 'انتقال از طرف مدیریت سایت',
            ]);

            return back()->with('message', 'Transfer completed successfully')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }
}
