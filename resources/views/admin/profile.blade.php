@extends('layouts.admin.main', ['pageTitle' => $user->email.' Profile', 'active' => 'users'])
@section('content')
  <div class="row">
    <div class="col-lg-12">
      <div class="card shadow mb-4">
          <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Edit Profile</h6>
          </div>
          <div class="card-body">
            <form method="post" action="{{route('admin.users.updateProfile')}}">
              @csrf
              <input type="hidden" name="_method" value="PUT" />
              <div class="form-group row">
                  <label for="name" class="col-sm-4 control-label">Name</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" id="name" name="name" value="{{$user->name}}">
                  </div>
              </div>
              <div class="form-group row">
                  <label for="email" class="col-sm-4 control-label">Email</label>
                  <div class="col-sm-8">
                      <input disabled  type="text" class="form-control" id="email" name="email" value="{{$user->email}}" required>
                  </div>
              </div>
              <div class="form-group row">
                  <label for="mobile" class="col-sm-4 control-label">Mobile</label>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" id="mobile" minlength="11" maxlength="11" name="mobile" value="{{$user->mobile}}">
                  </div>
              </div>
              <div class="form-group row">
                  <div class="col-sm-12 text-right">
                      <button type="submit" class="btn btn-success">submit</button>
                  </div>
              </div>
            </form>
          </div>
      </div>
    </div>
  </div>
@endsection
