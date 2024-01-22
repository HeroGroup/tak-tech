@extends('layouts.auth', ['pageTitle' => 'Password Recovered', 'pageTitleFa' => 'بازیابی رمز عبور'])
@section('content')
    @if(isset($provider))
    <div class="text-center">
        @if($provider=='google')
        <a href="{{route('auth.redirect', ['provider' => 'google'])}}" class="text-center external-login-provider">
            <span>ورود با </span> &nbsp; <img src="/assets/img/Google_logo.png" alt="گوگل" />
        </a>
        @elseif($provider=='apple')
        <a href="#" class="text-center external-login-provider">
            <span>ورود با </span> &nbsp; <img src="/assets/img/Apple_logo.png" alt="اپل" />
        </a>
        @endif
    </div>
    @else
    <div class="form-group">
        <div class="alert alert-success">ایمیل بازیابی رمز عبور برای شما فرستاده شد.</div>
    </div>
    <div class="form-note-s2 pt-5">
        <a href="{{route('auth.login')}}"><strong>بازگشت به ورود</strong></a>
    </div>
    @endif
@endsection