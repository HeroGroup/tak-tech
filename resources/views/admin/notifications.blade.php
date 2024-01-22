@extends('layouts.admin.main', ['pageTitle' => 'Messages', 'active' => 'messages'])
@section('content')
@foreach ($notifications as $notification)
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{$notification->subject}}</h6>
        <h6 class="text-gray">{{jdate('Y/m/d ساعت H:i', $notification->created_at->timestamp)}}</h6>
    </div>
    <div class="card-body">{{$notification->description}}</div>
</div>
@endforeach
@endsection