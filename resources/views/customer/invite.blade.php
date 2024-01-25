@extends('layouts.customer.main', ['pageTitle' => 'Invite', 'pageTitleFa' => 'دعوت از دوستان', 'active' => 'invite'])
@section('content')
<div class="mb-4">
  <h3 style="margin: 1em 0;">کسب درآمد با دعوت از دوستان</h3>
  <p class="mb-4">با استفاده از لینک زیر، دوستان خود را دعوت کنید و پس از اولین خرید آن ها، پاداش دریافت کنید.</p>

  <div style="text-align: center;">
    <button onclick="invite('{{auth()->user()->invite_code}}')" class="btn btn-primary">ارسال لینک دعوت</button>
  </div>
</div>
<table class="table">
  <tbody>
    <tr>
      <td>تعداد افراد دعوت شده توسط شما</td>
      <td>{{$numberOdInvitedPeople}} نفر</td>
    </tr>
    <tr>
      <td>پاداش دریافتی شما تا کنون</td>
      <td>{{number_format($reward)}} تومان</td>
    </tr>
  </tbody>
</table>
@endsection