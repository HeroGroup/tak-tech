@extends('layouts.admin.main', ['pageTitle' => 'Transactions', 'active' => 'transactions'])
@section('content')
<div class="row col-md-12 mb-4 filter-btns">
    <a href="#" onclick="searchTransactions('all', '{{$userId}}')" class="filter-btn border-bottom-info">
        <span class="text-gray-900">All</span>&nbsp;<span class="text-info">{{$numberOfSuccessfulPayments + $numberOfFailedPayments + $numberOfPendingPayments}}</span>
    </a>
    <a href="#" onclick="searchTransactions('{{\App\Enums\TransactionStatus::PAYMENT_SUCCESSFUL->value}}', '{{$userId}}')" class="filter-btn border-bottom-success">
        <span class="text-gray-900">Successful Payments</span>&nbsp;<span class="text-success">{{$numberOfSuccessfulPayments}}</span>
    </a>
    <a href="#" onclick="searchTransactions('{{\App\Enums\TransactionStatus::PAYMENT_FAILED->value}}', '{{$userId}}')" class="filter-btn border-bottom-danger">
        <span class="text-gray-900">Failed Payments</span>&nbsp;<span class="text-danger">{{$numberOfFailedPayments}}</span>
    </a>
    <a href="#" onclick="searchTransactions('{{\App\Enums\TransactionStatus::PENDING->value}}', '{{$userId}}')" class="filter-btn border-bottom-warning">
        <span class="text-gray-900">Pending Payments</span>&nbsp;<span class="text-warning">{{$numberOfPendingPayments}}</span>
    </a>
</div>

<div class="row col-md-12 mb-4">
    <div class="date-search-box">
        <div class="row mr-4">
            <label for="from-date" class="col-sm-2 col-form-label">From</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" id="from-date" name="from-date" value={{$fromDate}}>
            </div>
        </div>
        <div class="row mr-4">
            <label for="to-date" class="col-sm-2 col-form-label">To</label>
            <div class="col-sm-10">
                <input type="date" class="form-control" id="to-date" name="to-date" value={{$toDate}}>
            </div>
        </div>
        <div class="row">
            <a href="#" onclick="searchTransactions('{{$filter}}', '{{$userId}}')" class="btn btn-sm btn-primary">Search</a>
        </div>
    </div>
</div>

<div class="filters-container mb-4">
    <div style="display: flex; align-items:center;">
        <div class="filter-dropdown">
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle btn btn-info btn-circle" href="#" id="actionsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-fw fa-filter"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="actionsDropdown">
                    <a href="#" onclick="searchTransactions('all', '{{$userId}}')" class="dropdown-item">
                        <span>All</span>&nbsp;<span class="text-info">{{$numberOfSuccessfulPayments + $numberOfFailedPayments + $numberOfPendingPayments}}</span>
                    </a>
                    <a href="#" onclick="searchTransactions('{{\App\Enums\TransactionStatus::PAYMENT_SUCCESSFUL->value}}', '{{$userId}}')" class="dropdown-item">
                        <span>Successful Payments</span>&nbsp;<span class="text-success">{{$numberOfSuccessfulPayments}}</span>
                    </a>
                    <a href="#" onclick="searchTransactions('{{\App\Enums\TransactionStatus::PAYMENT_FAILED->value}}', '{{$userId}}')" class="dropdown-item">
                        <span>Failed Payments</span>&nbsp;<span class="text-danger">{{$numberOfFailedPayments}}</span>
                    </a>
                    <a href="#" onclick="searchTransactions('{{\App\Enums\TransactionStatus::PENDING->value}}', '{{$userId}}')" class="dropdown-item">
                        <span>Pending Payments<span>&nbsp;<span class="text-warning">{{$numberOfPendingPayments}}</span>
                    </a>
                </div>
            </div>
        </div>
        <span class="text-gray-900">Filters: {{$filters}}</span>
    </div>
    
    <a href="{{route('admin.transactions')}}">clear filters</a>
</div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transactions</h6>
        </div>
        <div class="card-body">
            <a class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#new-transaction-modal">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">new transaction</span>
            </a>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Reason</th>
                            <th>Transafer Token</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr id="{{$transaction->id}}">
                            <td>{{$transaction->user->email ?? $transaction->user->name}}</td>
                            <td>{{$transaction->title}}</td>
                            <td>{{$transaction->description}}</td>
                            <td>
                              @if ($transaction->type==\App\Enums\TransactionType::INCREASE->value)
                                <span class="text-success">{{number_format($transaction->amount)}}</span>
                              @else
                                <span class="text-danger">{{number_format($transaction->amount)}}</span>
                              @endif
                            </td>
                            <td>{{$transaction->reason}}</td>
                            <td>{{$transaction->transfer_token}}</td>
                            <td>
                              @if ($transaction->status==\App\Enums\TransactionStatus::PENDING->value)
                              <span class="badge badge-warning">{{$transaction->status}}</span>
                              @elseif ($transaction->status==\App\Enums\TransactionStatus::PAYMENT_SUCCESSFUL->value)
                              <span class="badge badge-success">{{$transaction->status}}</span>
                              @else
                              <span class="badge badge-danger">{{$transaction->status}}</span>
                              @endif
                            </td>
                            <td>{{date('Y-m-d H:i', $transaction->created_at->timestamp)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Create transaction Modal -->
<div class="modal fade" id="new-transaction-modal" tabindex="-1" role="dialog" aria-labelledby="newtransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newtransactionModalLabel">Add new Transaction</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                  <form method="post" action="{{route('admin.transactions.store')}}">
                    @csrf
                    <div class="form-group row" style="margin-bottom:30px;">
                            <div class="col-md-12">
                                <label for="title">Title</label>
                                <input class="form-control" name="title" value="{{old('title')}}" placeholder="Enter transaction title" required>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:30px;">
                        <div class="col-md-12">
                          <label for="user_type">Transaction Reason</label>
                          <select name="reason" id="reason" class="form-control">
                            <option value="{{\App\Enums\TransactionReason::TRANSFER->name}}" selected>{{\App\Enums\TransactionReason::TRANSFER->value}}</option>
                          </select>            
</div>                                                
                        </div>
                        <div class="form-group row" style="margin-bottom:30px;">
                            <div class="col-md-12">
                                <label for="title">Amount</label>
                                <input class="form-control" name="amount"value="{{old('amount')}}" placeholder="Enter transaction amount" required>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:30px;">
                        <div class="col-md-12">
                          <label for="from">From</label>
                          <select name="from" id="from" class="form-control" required>
                            @foreach ($users as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                          </select>                    
</div>                                        
                        </div>
                        <div class="form-group row" style="margin-bottom:30px;">
                        <div class="col-md-12">
                          <label for="to">To</label>
                          <select name="to" id="to" class="form-control">
                            @foreach ($users as $key => $value)
                            <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                          </select>    
</div>                                                   
                        </div>
                        <div class="form-group row" style="margin-bottom:30px;">
                            <div class="col-md-12" style="text-align:center;">
                                <input type="submit" class="btn btn-success" value="Save and close" />
                            </div>
                        </div>
                  </form>
                </div>
            </div>
        </div>
    </div>

@endsection
