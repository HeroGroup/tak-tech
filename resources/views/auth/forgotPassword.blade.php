@extends('layouts.auth', ['pageTitle' => 'Forgot Password', 'pageTitleFa' => 'بازیابی رمز عبور'])
@section('content')

  <form action="{{route('auth.forgotPassword')}}" method="post">
    @csrf
    <div class="form-group">
        <div class="form-label-group">
            <label class="form-label" for="email">ایمیل</label>
        </div>
        <div class="form-control-wrap">
            <input name="email" type="text" class="form-control form-control-lg" id="email" placeholder="نشانی ایمیل خود را وارد کنید" required />
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-lg btn-primary btn-block">ارسال لینک بازیابی</button>
    </div>
  </form>
  <!-- form -->
  <div class="form-note-s2 pt-5">
      <a href="{{route('auth.login')}}"><strong>بازگشت به ورود</strong></a>
  </div>

  @endsection