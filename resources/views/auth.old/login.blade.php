@extends('layouts.auth', ['pageTitle' => 'Login', 'pageTitleFa' => 'ورود به '.env("APP_NAME")])
@section('content')
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <!-- @if($errors && count($errors) > 0)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{$errors->first('email')}}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif -->
    <!-- <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>متاسفانه</strong> نام کاربری یا رمز عبور اشتباه است.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> -->
    <form action="{{route('auth.post.login')}}" method="POST" id="commonForm">
        @csrf
        <div class="form-group position-relative clearfix">
            <input name="email" type="email" class="form-control" placeholder="نام کاربری" aria-label="Full Name">
            <div class="login-popover login-popover-abs" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="لطفا آدرس ایمیل خود را وارد کنید">
                <i class="fa fa-info-circle"></i>
            </div>
        </div>
        <div class="form-group clearfix position-relative password-wrapper">
            <input name="password" type="password" class="form-control" autocomplete="off" placeholder="رمز عبور" aria-label="Password">
            <i class="fa fa-eye password-indicator"></i>
        </div>
        <div class="checkbox form-group clearfix">
            <div class="form-check float-end">
                <input name="remember" class="form-check-input" type="checkbox" id="remember">
                <label class="form-check-label" for="remember">
                    مرا به خاطر بسپار
                </label>
            </div>
            <a href="{{route('auth.forgotPassword')}}" class="link-light float-start forgot-password">فراموشی رمز عبور</a>
        </div>
        <div class="form-group clearfix mb-0">
            <button type="submit" class="btn btn-primary btn-lg btn-theme">ورود</button>
        </div>
    </form>
    <x-loginProviders />		    
    <p>حساب کاربری ندارید؟ <a href="{{route('auth.register')}}" class="thembo">ثبت نام کنید</a></p>

@endsection
