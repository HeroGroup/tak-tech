@extends('layouts.customer.main', ['pageTitle' => 'Transactions', 'pageTitleFa' => 'تراکنش ها', 'active' => 'transactions'])
@section('content')
<div class="nk-block nk-block-lg">
    <a href="#" data-bs-toggle="modal" data-bs-target="#increase-modal">
        <em class="icon ni ni-plus"></em> افزایش موجودی
    </a><br><br>
    <!-- Increase Modal -->
    <div class="modal fade" id="increase-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="increase-modal-label">افزایش موجودی کیف پول</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('customer.transactions.increase')}}" class="form-validate is-alter" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="amount">مبلغ</label>
                            <div class="form-control-wrap">
                                <input type="number" class="form-control" name="amount" id="amount" required min="1000" />
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lg btn-primary">پرداخت</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  <table class="datatable-init nowrap nk-tb-list is-separate" data-auto-responsive="false">
    <thead>
        <tr class="nk-tb-item nk-tb-head">
            <th class="nk-tb-col"><span>شناسه</span></th>
            <th class="nk-tb-col"><span>زمان</span></th>
            <th class="nk-tb-col"><span>دلیل</span></th>
            <th class="nk-tb-col"><span>مبلغ</span></th>
            <th class="nk-tb-col"><span>توضیحات</span></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
        <tr class="nk-tb-item">
            <td class="nk-tb-col">
                <span class="tb-lead">{{$transaction->id}}</span>
            </td>
            <td class="nk-tb-col">
                <span class="tb-sub">{{jdate('j F Y ساعت H:i', $transaction->created_at->timestamp)}}</span>
            </td>
            <td class="nk-tb-col">
                <span class="dot bg-warning d-sm-none"></span>
                <span class=@if($transaction->type==\App\Enums\TransactionType::INCREASE->value) 'badge badge-sm badge-dot has-bg d-none d-sm-inline-flex bg-success' @else 'badge badge-sm badge-dot has-bg d-none d-sm-inline-flex bg-danger' @endif>{{$transaction->reason}}</span>
            </td>
            <td class="nk-tb-col">
                <span class=@if($transaction->type==\App\Enums\TransactionType::INCREASE->value) 'tb-lead text-success' @else 'tb-lead text-danger' @endif>{{number_format($transaction->amount)}} تومان</span>
            </td>
            <td class="nk-tb-col">
                <span class="tb-lead">{{$transaction->description}}</span>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
</div>
@endsection