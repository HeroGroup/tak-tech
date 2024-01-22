@extends('layouts.auth', ['pageTitle' => 'Forgot Password', 'pageTitleFa' => 'بازیابی رمز عبور'])
@section('content')
    <!-- <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>تبریک،</strong> ایمیل بازیابی رمز برای شما ارسال شد.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> -->
    <form action="{{route('auth.post.forgotPassword')}}" method="POST" id="commonForm">
        @csrf
        <div class="form-group position-relative clearfix">
            <input name="email" type="email" class="form-control" placeholder="ایمیل را وارد کنید..." aria-label="Email Address">
            <div class="login-popover login-popover-abs" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="لطفا آدرس ایمیل خود را وارد کنید.">
                <i class="fa fa-info-circle"></i>
            </div>
        </div>
        <div class="form-group clearfix mb-0">
            <button type="submit" class="btn btn-primary btn-lg btn-theme">ارسال لینک بازیابی</button>
        </div>
    </form>

    <div style="padding-top: 12px;" >
        <a href="{{route('auth.login')}}">بازگشت</a>
    </div>
@endsection
