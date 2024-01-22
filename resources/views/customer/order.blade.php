@extends('layouts.customer.main', ['pageTitle' => 'Order', 'pageTitleFa' => '', 'active' => 'orders'])
@section('content')
  <div class="nk-block nk-block-lg">
    <div class="card card-bordered">
        <div class="card-header bg-white" style="border-bottom: 1px solid lightgray;">
            <h5>
                <a href="{{route('customer.orders')}}" class="text-black">
                    <em class="icon ni ni-arrow-left"></em>
                </a>
                <span>&nbsp; جزئیات سفارش</span>
            </h5>
        </div>
        <div class="card-inner">
            <div class="col-xxl-2 col-md-6 col-lg-4">
                <ul class="nk-top-products">
                    <li class="item">
                        <div class="info">
                            <label class="text-gray">شماره پیگیری</label>
                        </div>
                        <div class="total">
                            <label>{{$order->transaction_id}}</label>
                        </div>
                    </li>
                    <li class="item">
                        <div class="info">
                            <label class="text-gray">تاریخ ثبت سفارش</label>
                        </div>
                        <div class="total">
                            <label>{{jdate('l d F Y ساعت H:i', $order->created_at->timestamp)}}</label>
                        </div>
                    </li>
                </ul>
            </div>
            <hr />
            <div>
                <img src="/assets/img/success-icon.png" alt="success" width="32" height="32">
                <span>{{$order->status}}</span>
            </div>
            <div class="col-xxl-2 col-md-6 col-lg-4">
                <ul class="nk-top-products">
                    <li class="item">
                        <div class="info">مبلغ کل سفارش</div>
                        <div class="total">{{number_format($order->final_price)}} تومان</div>
                    </li>
                    <li class="item">
                        <div class="info">تخفیف</div>
                        <div class="total">{{number_format($order->base_price-$order->final_price)}} تومان</div>
                    </li>
                </ul>
            </div>
            
            <hr />
            <div class="card card-bordered col-xxl-2 col-md-6 col-lg-4">
                <div class="card-header bg-white" style="border-bottom: 1px solid lightgray;">
                    <h5>اقلام سفارش</h5>
                </div>

                <?php $orderDetails = $order->orderDetails; ?>

                <div class="card-inner">
                    <div>
                        @foreach ($orderDetails as $orderDetail)
                        <ul class="nk-top-products">
                            <li class="item">
                                <div class="info">
                                    <label class="text-gray">{{$orderDetail->product_title}}</label>
                                </div>
                                <div class="total">
                                    <label>{{$orderDetail->count}} عدد</label>
                                </div>
                            </li>
                            <li class="item">
                                <div class="info">
                                    <label class="text-gray">قیمت</label>
                                </div>
                                <div class="total">
                                    <label>{{number_format($orderDetail->product_final_price * $orderDetail->count)}} تومان</label>
                                </div>
                            </li>
                            <li class="item">
                                <div class="info">
                                    <label class="text-gray">تخفیف</label>
                                </div>
                                <div class="total">
                                    <label>{{number_format(($orderDetail->product_base_price - $orderDetail->product_final_price) * $orderDetail->count)}} تومان</label>
                                </div>
                            </li>
                        </ul>
                        
                        <hr />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <!-- .nk-block -->
@endsection