@extends('layouts.auth', ['pageTitle' => 'Register', 'pageTitleFa' => 'ثبت نام در '.env('APP_NAME')])
@section('content')
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
  <!-- <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>تبریک، </strong> حساب کاربری شما با موفقیت ایجاد شد.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div> -->
  <form action="{{route('auth.post.register')}}" method="POST" id="commonForm">
      @csrf
	    <!-- <div class="form-group position-relative clearfix">
          <input name="name" type="text" class="form-control" placeholder="نام کاربری" aria-label="Full Name">
      </div> -->
      <div class="form-group position-relative clearfix">
          <input name="email" type="email" class="form-control" placeholder="پست الکترونیکی" aria-label="Email Address">
          <div class="login-popover login-popover-abs" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="لطفا آدرس ایمیل خود را وارد کنید">
              <i class="fa fa-info-circle"></i>
          </div>
      </div>
      <div class="form-group clearfix position-relative password-wrapper">
          <input name="password" type="password" class="form-control" autocomplete="off" placeholder="رمز عبور" aria-label="Password">
          <i class="fa fa-eye password-indicator"></i>
      </div>
      <div class="form-group checkbox clearfix">
          <div class="clearfix float-end">
              <div class="form-check">
                  <input class="form-check-input" name="policy" type="checkbox" id="policy">
                  <label class="form-check-label" for="policy">
                      من با شرایط استفاده از خدمات موافقم.
                  </label>
              </div>
          </div>
      </div>
      <div class="form-group clearfix mb-0">
          <button type="submit" class="btn btn-primary btn-lg btn-theme">ثبت نام</button>
      </div>
  </form>
  <x-loginProviders></x-loginProviders>
  <p>حساب کاربری دارید؟ <a href="{{route('auth.login')}}">ورود</a></p>

@endsection
