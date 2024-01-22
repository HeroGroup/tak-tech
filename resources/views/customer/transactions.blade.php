@extends('layouts.customer.main', ['pageTitle' => 'Transactions', 'pageTitleFa' => 'تراکنش ها', 'active' => 'transactions'])
@section('content')
<div class="nk-block nk-block-lg">
  <table class="datatable-init nowrap nk-tb-list is-separate" data-auto-responsive="false">
    <thead>
        <tr class="nk-tb-item nk-tb-head">
            <th class="nk-tb-col"><span>شناسه</span></th>
            <th class="nk-tb-col"><span>زمان</span></th>
            <th class="nk-tb-col"><span>دلیل</span></th>
            <th class="nk-tb-col"><span>مبلغ</span></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
        <tr class="nk-tb-item">
            <td class="nk-tb-col">
                <span class="tb-lead">#{{$transaction->id}}</span>
            </td>
            <td class="nk-tb-col">
                <span class="tb-sub">{{jdate('Y/m/d ساعت H:i', $transaction->created_at->timestamp)}}</span>
            </td>
            <td class="nk-tb-col">
                <span class="dot bg-warning d-sm-none"></span>
                <span class=@if($transaction->type==\App\Enums\TransactionType::INCREASE->value) 'badge badge-sm badge-dot has-bg d-none d-sm-inline-flex bg-success' @else 'badge badge-sm badge-dot has-bg d-none d-sm-inline-flex bg-danger' @endif>{{$transaction->reason}}</span>
            </td>
            <td class="nk-tb-col">
                <span class=@if($transaction->type==\App\Enums\TransactionType::INCREASE->value) 'tb-lead text-success' @else 'tb-lead text-danger' @endif>{{number_format($transaction->amount)}} تومان</span>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
</div>
@endsection