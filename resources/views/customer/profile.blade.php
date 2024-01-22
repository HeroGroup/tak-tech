@extends('layouts.customer.main', ['pageTitle' => 'Profile', 'pageTitleFa' => 'پروفایل', 'active' => 'profile'])
@section('content')
<div class="card">
    <div class="card-aside-wrap">
        <div class="card-inner card-inner-lg">
            <div class="nk-block">
                <div class="nk-data data-list">
                    <div class="data-head">
                        <h6 class="overline-title">پایه</h6>
                    </div>
                    <div class="data-item" data-bs-toggle="modal" data-bs-target="#profile-edit">
                        <div class="data-col">
                            <span class="data-label">نام کامل</span>
                            @if ($user->name)
                            <span class="data-value">{{$user->name}}</span>
                            @else
                            <span class="data-value text-soft">هنوز اضافه نشده</span>
                            @endif
                        </div>
                        <div class="data-col data-col-end">
                            <span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
                        </div>
                    </div>
                    <!-- data-item -->
                    <div class="data-item">
                        <div class="data-col">
                            <span class="data-label">ایمیل</span>
                            <span class="data-value">{{$user->email}}</span>
                        </div>
                        <div class="data-col data-col-end">
                            <span class="data-more disable"><em class="icon ni ni-lock-alt"></em></span>
                        </div>
                    </div>
                    <!-- data-item -->
                    <div class="data-item" data-bs-toggle="modal" data-bs-target="#profile-edit">
                        <div class="data-col">
                            <span class="data-label">شماره تلفن</span>
                            @if ($user->mobile)
                            <span class="data-value">{{$user->mobile}}</span>
                            @else
                            <span class="data-value text-soft">هنوز اضافه نشده</span>
                            @endif
                        </div>
                        <div class="data-col data-col-end">
                            <span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
                        </div>
                    </div>
                    <!-- data-item -->
                    @if ($user->password)
                    <div class="data-item" data-bs-toggle="modal" data-bs-target="#profile-edit">
                        <div class="data-col">
                            <span class="data-label">تغییر رمز عبور</span>
                            <span class="data-value text-soft">***************</span>
                        </div>
                        <div class="data-col data-col-end">
                            <span class="data-more"><em class="icon ni ni-forward-ios"></em></span>
                        </div>
                    </div>
                    <!-- data-item -->
                    @endif
                </div>
                <!-- data-list -->
            </div>
            <!-- .nk-block -->    
        </div>
    </div>
</div>

<!-- .modal -->
<!-- @@ Profile Edit Modal @e -->
<div class="modal fade" role="dialog" id="profile-edit">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            <div class="modal-body modal-body-lg">
                <h5 class="title">به روز رسانی پروفایل</h5>
                <ul class="nk-nav nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#personal">اطلاعات شخصی</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#changePassword">رمز عبور</a>
                    </li>
                </ul>
                <!-- .nav-tabs -->
                <div class="tab-content">
                    <div class="tab-pane active" id="personal">
                        <form method="post" action="{{route('customer.updateProfile')}}">
                            @csrf
                            <input type="hidden" name="_method" value="put" />
                            <div class="row gy-4">
                                <div class="row col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="email">آدرس ایمیل</label>
                                        <input type="email" class="form-control form-control-lg" name="email" id="email" value="{{$user->email}}" disabled />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="name">نام کامل</label>
                                        <input type="text" class="form-control form-control-lg" name="name" id="name" value="{{$user->name}}" placeholder="نام کامل خود را وارد کنید" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="mobile">شماره تلفن</label>
                                        <input type="text" class="form-control form-control-lg" name="mobile" id="mobile" value="{{$user->mobile}}" placeholder="شماره تلفن" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                        <li>
                                            <button type="submit" data-bs-dismiss="modal" class="btn btn-lg btn-primary">به روز رسانی پروفایل</button>
                                        </li>
                                        <li>
                                            <a href="#" data-bs-dismiss="modal" class="link link-light">لغو</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- .tab-pane -->
                    @if ($user->password)
                    <div class="tab-pane" id="changePassword">
                        <form method="post" action="{{route('customer.updatePassword')}}">
                            @csrf
                            <input type="hidden" name="_method" value="put" />
                            <div class="row gy-4">
                                <div class="row col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="current_password">رمز عبور فعلی</label>
                                        <input type="password" class="form-control form-control-lg" name="current_password" id="current_password" required />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="password">رمز عبور جدید</label>
                                            <input type="password" class="form-control form-control-lg" name="password" id="password" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="password_confirmation">تکرار رمز عبور</label>
                                            <input type="password" class="form-control form-control-lg" name="password_confirmation" id="password_confirmation" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <ul class="align-center flex-wrap flex-sm-nowrap gx-4 gy-2">
                                        <li>
                                            <button type="submit" class="btn btn-lg btn-primary">به روز رسانی رمز عبور</button>
                                        </li>
                                        <li>
                                            <a href="#" data-bs-dismiss="modal" class="link link-light">لغو</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- .tab-pane -->
                    @endif
                </div>
                <!-- .tab-content -->
            </div>
            <!-- .modal-body -->
        </div>
        <!-- .modal-content -->
    </div>
    <!-- .modal-dialog -->
</div>
<!-- .modal -->
@endsection