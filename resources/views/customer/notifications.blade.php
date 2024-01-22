@extends('layouts.customer.main', ['pageTitle' => 'Notifications', 'pageTitleFa' => 'پیام ها', 'active' => 'notifications'])
@section('content')
<div class="nk-block nk-block-lg">
    @foreach ($notifications as $notification)
    <div class="card card-bordered @if($notification->is_read==0) not-read @endif">
        <div class="card-inner">
            <h5 class="card-title">{{$notification->subject}}</h5>
            <h6 class="card-subtitle mb-2">{{jdate('l j F Y ساعت H:i', $notification->created_at->timestamp)}}</h6>
            <p class="card-text">{{$notification->description}}</p>
        </div>
    </div>
    @endforeach
</div>
@endsection