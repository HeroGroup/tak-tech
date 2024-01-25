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
  <div class="card">
    <div class="card-header">
      <h5>اطلاعات آخرین سفارش</h5>
    </div>
  <div class="card-inner">
    <div class="row">
      <div class="col-md-6 center">
        <img src="/assets/img/qr-code.png" width="256" height="256" />
      </div>
        <div class="col-md-6 center">
          <a href="#" style="font-size: 18px; padding: 1em; text-decoration: none;">
            <em class="icon ni ni-download"></em>
            <span>دانلود فایل .conf </span>
          </a>
        </div>
    </div>
    
  </div>
    
  </div>
</div>
@endsection