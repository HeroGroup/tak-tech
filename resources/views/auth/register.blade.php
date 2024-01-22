@extends('layouts.auth', ['pageTitle' => 'Register', 'pageTitleFa' => 'ثبت نام در '.config("app.name"), 'withExtrnalLoginProviders' => true])
@section('content')

<form action="{{route('auth.register')}}" method="post">
  @csrf 
  <div class="form-group">
      <label class="form-label" for="email">ایمیل</label>
      <div class="form-control-wrap">
          <input name="email" type="email" class="form-control form-control-lg" id="email" placeholder="آدرس ایمیل خود را وارد کنید" required />
      </div>
  </div>
  <div class="form-group">
      <label class="form-label" for="password">رمز عبور</label>
      <div class="form-control-wrap">
          <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
              <em class="passcode-icon icon-show icon ni ni-eye"></em>
              <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
          </a>
          <input name="password" type="password" class="form-control form-control-lg" id="password" placeholder="رمز عبور خود را وارد کنید" required />
      </div>
  </div>

  <div class="form-group">
      <button class="btn btn-lg btn-primary btn-block">ثبت نام</button>
  </div>
</form>

<div class="form-note-s2 pt-4">حساب کاربری دارید؟ <a href="{{route('auth.login')}}"><strong>وارد شوید</strong></a></div>

@endsection