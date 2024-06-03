@extends('layouts.customer.main', ['pageTitle' => 'Orders', 'pageTitleFa' => 'سفارشات', 'active' => 'orders'])
@section('content')
  
  <div class="nk-block nk-block-lg">
      <table class="datatable-init nowrap nk-tb-list is-separate" data-auto-responsive="false">
          <thead>
            <tr class="nk-tb-item nk-tb-head">
              <th class="nk-tb-col"><span>شناسه سفارش</span></th>
              <th class="nk-tb-col tb-col-md"><span>زمان</span></th>
              <th class="nk-tb-col"><span>وضعیت</span></th>
              <th class="nk-tb-col"><span>قیمت</span></th>
              <th class="nk-tb-col"><span>عملیات</span></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($orders as $order)
            <tr class="nk-tb-item">
                <td class="nk-tb-col">
                    <span class="tb-sub">{{$order->uid}}</span>
                </td>
                <td class="nk-tb-col tb-col-md">
                    <span class="tb-lead">{{jdate('Y/m/d ساعت H:i', $order->created_at->timestamp)}}</span>
                </td>
                <td class="nk-tb-col">
                    <span class="dot bg-warning d-sm-none"></span>
                    @if($order->status==\App\Enums\OrderStatus::PENDING->value)
                    <span class="badge badge-sm badge-dot has-bg bg-warning d-none d-sm-inline-flex">{{$order->status}}</span>
                    @else
                    <span class="badge badge-sm badge-dot has-bg bg-success d-none d-sm-inline-flex">{{$order->status}}</span>
                    @endif
                </td>
                <td class="nk-tb-col">
                    <span class="tb-lead">{{number_format($order->final_price)}} تومان</span>
                </td>
                <td class="nk-tb-col nk-tb-col-tools">
                    <ul class="nk-tb-actions gx-1">
                        <li>
                            <a href="{{route('customer.orders.show', $order->uid)}}" class="btn btn-icon btn-trigger btn-tooltip" title="مشاهده سفارش"> <em class="icon ni ni-eye"></em></a>
                        </li>
                    </ul>
                </td>
            </tr>
          @endforeach
            </tbody>
        </table>
      <!-- .nk-tb-list -->
  </div>
  <!-- .nk-block -->
@endsection