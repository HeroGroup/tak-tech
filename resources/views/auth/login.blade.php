@extends('layouts.auth', ['pageTitle' => 'Login', 'pageTitleFa' => 'ورود به '.config("app.name"), 'withExtrnalLoginProviders' => true])
@section('content')
    
<!-- .nk-block-head -->
<form action="{{route('auth.post.login')}}" method="POST">
  @csrf
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="email">ایمیل</label>
            <!-- <a class="link link-primary link-sm" tabindex="-1" href="#">راهنمایی</a> -->
        </div>
        <div class="form-control-wrap">
            <input name="email" type="email" class="form-control form-control-lg" id="email" placeholder="آدرس ایمیل خود را وارد کنید" required />
        </div>
    </div>
    <!-- .form-group -->
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="password">رمز عبور</label>
            <a class="link link-primary link-sm" tabindex="-1" href="{{route('auth.forgotPassword')}}">رمز عبور را فراموش کردید؟</a>
        </div>
        <div class="form-control-wrap">
            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
            </a>
            <input name="password" type="password" class="form-control form-control-lg" id="password" placeholder="رمز عبور خود را وارد کنید" required />
        </div>
    </div>

    <div class="checkbox form-group">
      <div class="form-check">
        <input name="remember" class="form-check-input" type="checkbox" id="remember" />
        <label class="form-check-label" for="remember">
          مرا به خاطر بسپار
        </label>
      </div>
    </div>
    
    <!-- .form-group -->
    <div class="form-group">
        <button class="btn btn-lg btn-primary btn-block">ورود</button>
    </div>
</form>
<!-- form -->
<div class="form-note-s2 pt-4">حساب کاربری ندارید؟ <a href="{{route('auth.register')}}">ثبت نام کنید</a></div>
                                    
@endsection