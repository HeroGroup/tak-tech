@extends('layouts.customer.main', ['pageTitle' => 'Dashboard', 'pageTitleFa' => 'داشبورد', 'active' => 'dashboard'])
@section('content')
<div>
  
    <div class="card">
      <div class="card-header">
        <h5>اطلاعات آخرین ورود</h5>
      </div>
      <div class="card-inner">
        <table class="table">
          <thead>
            <th>زمان</th>
            <th>دستگاه</th>
            <th>آی پی</th>
          </thead>
          <tbody>
            <tr>
              <td>{{jdate('l d F Y ساعت H:i', $l_session->created_at->timestamp)}}</td>
              <td>{{$l_session->device}}</td>
              <td>{{$l_session->ip_address}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  <hr>
  <div class="row g-gs">
    <div class="col-xxl-3 col-sm-6">
      <div class="card">
        <div class="nk-ecwg nk-ecwg6">
          <div class="card-inner">
            <div class="card-title-group">
              <div class="card-title">
                <h6 class="title">سرویس های من</h6>
              </div>
            </div>
            <div class="data">
              <ul>
                <li>{{$active_services_count}} سرویس فعال</li>
                <li>{{$inactive_services_count}} سرویس غیرفعال</li>
                <li>
                  <a href="{{route('customer.services')}}">مشاهده همه</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xxl-3 col-sm-6">
      <div class="card">
        <div class="nk-ecwg nk-ecwg6">
          <div class="card-inner">
            <div class="card-title-group">
              <div class="card-title">
                <h6 class="title">تعداد سفارشات</h6>
              </div>
            </div>
            <div class="data">
              <ul>
                <li>{{$orders_count}}</li>
                <li>
                  <a href="{{route('customer.orders')}}">مشاهده همه</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection