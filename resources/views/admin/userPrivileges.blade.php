@extends('layouts.admin.main', ['pageTitle' => 'Users', 'active' => 'users'])
@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Privileges</h6>
      </div>
      <div class="card-body">
        <form action="{{route('admin.users.updatePrivileges')}}" method="post">
          @csrf
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="user_id" value="{{$user->id}}">
          @foreach ($privileges as $key => $value)
          <input type="checkbox" name="privileges[]" value="{{$key}}" @if(in_array($key, $user_privileges)) checked="checked" @endif/> {{$value}} <br><br>
          @endforeach
          <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection