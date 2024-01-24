@extends('layouts.admin.main', ['pageTitle' => 'Orders', 'active' => 'orders'])
@section('content')
<div class="row col-md-12 mb-4 filter-btns">
    <a href="#" onclick="searchOrders('all', '{{$userId}}')" class="filter-btn border-bottom-info">
        <span class="text-gray-900">All</span>&nbsp;<span class="text-info">{{$numberOfSuccessfulPayments + $numberOfFailedPayments + $numberOfPendingPayments}}</span>
    </a>
    <a href="#" onclick="searchOrders('{{\App\Enums\OrderStatus::PAYMENT_SUCCESSFUL->value}}', '{{$userId}}')" class="filter-btn border-bottom-success">
        <span class="text-gray-900">Successful Payments</span>&nbsp;<span class="text-success">{{$numberOfSuccessfulPayments}}</span>
    </a>
    <a href="#" onclick="searchOrders('{{\App\Enums\OrderStatus::PAYMENT_FAILED->value}}', '{{$userId}}')" class="filter-btn border-bottom-danger">
        <span class="text-gray-900">Failed Payments</span>&nbsp;<span class="text-danger">{{$numberOfFailedPayments}}</span>
    </a>
    <a href="#" onclick="searchOrders('{{\App\Enums\OrderStatus::PENDING->value}}', '{{$userId}}')" class="filter-btn border-bottom-warning">
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
            <a href="#" onclick="searchOrders('{{$filter}}', '{{$userId}}')" class="btn btn-sm btn-primary">Search</a>
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
                    <a href="#" onclick="searchOrders('all', '{{$userId}}')" class="dropdown-item">
                        <span>All</span>&nbsp;<span class="text-info">{{$numberOfSuccessfulPayments + $numberOfFailedPayments + $numberOfPendingPayments}}</span>
                    </a>
                    <a href="#" onclick="searchOrders('{{\App\Enums\OrderStatus::PAYMENT_SUCCESSFUL->value}}', '{{$userId}}')" class="dropdown-item">
                        <span>Successful Payments</span>&nbsp;<span class="text-success">{{$numberOfSuccessfulPayments}}</span>
                    </a>
                    <a href="#" onclick="searchOrders('{{\App\Enums\OrderStatus::PAYMENT_FAILED->value}}', '{{$userId}}')" class="dropdown-item">
                        <span>Failed Payments</span>&nbsp;<span class="text-danger">{{$numberOfFailedPayments}}</span>
                    </a>
                    <a href="#" onclick="searchOrders('{{\App\Enums\OrderStatus::PENDING->value}}', '{{$userId}}')" class="dropdown-item">
                        <span>Pending Payments<span>&nbsp;<span class="text-warning">{{$numberOfPendingPayments}}</span>
                    </a>
                </div>
            </div>
        </div>
        <span class="text-gray-900">Filters: {{$filters}}</span>
    </div>
    
    <a href="{{route('admin.orders')}}">clear filters</a>
</div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Orders</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>uid</th>
                            <th>User</th>
                            <th>Base Price</th>
                            <th>Final Price</th>
                            <th>Status</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr id="{{$order->id}}">
                            <td>{{$order->uid}}</td>
                            <td>{{$order->user->email ?? $order->user->name}}</td>
                            <td>{{number_format($order->base_price)}}</td>
                            <td>{{number_format($order->final_price)}}</td>
                            <td>
                              @if ($order->status==\App\Enums\OrderStatus::PENDING->value)
                              <span class="badge badge-warning">{{$order->status}}</span>
                              @elseif ($order->status==\App\Enums\OrderStatus::PAYMENT_SUCCESSFUL->value)
                              <span class="badge badge-success">{{$order->status}}</span>
                              @else
                              <span class="badge badge-danger">{{$order->status}}</span>
                              @endif
                            </td>
                            <td>{{date('Y-m-d H:i', $order->created_at->timestamp)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
