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
    public function index($filter)
    {
        try {
            if ($filter && $filter != 'all') {
                $transactions = Transaction::with('user')->where('status', $filter)->orderBy('created_at', 'desc')->get();
            } else {
                $transactions = Transaction::with('user')->orderBy('created_at', 'desc')->get();
            }

            $users = User::pluck('email', 'id')->toArray();

            $numberOfSuccessfulPayments = Transaction::where('status', TransactionStatus::PAYMENT_SUCCESSFUL->value)->count();
            $numberOfFailedPayments = Transaction::where('status', TransactionStatus::PAYMENT_FAILED->value)->count();
            $numberOfPendingPayments = Transaction::where('status', TransactionStatus::PENDING->value)->count();

            return view('admin.transactions', compact('transactions', 'users', 'numberOfSuccessfulPayments', 'numberOfFailedPayments', 'numberOfPendingPayments'));
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

            Mailbox::create([
                'user_id' => $request->to,
                'subject' => 'افزایش اعتبار',
                'description' => 'مبلغ ' . number_format($request->amount) . ' تومان از طرف مدیریت سایت به کیف پول شما اضافه شد.',
                'route' => '/customer/transactions',
            ]);

            return back()->with('message', 'Transfer completed successfully')->with('type', 'success');
        } catch (\Exception $exception) {
            return back()->with('message', $exception->getMessage())->with('type', 'danger');
        }
    }
}
