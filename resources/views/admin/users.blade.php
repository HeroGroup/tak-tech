@extends('layouts.admin.main', ['pageTitle' => 'Users', 'active' => 'users'])
@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Users</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>User Type</th>
                        <th>Registered Date</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr id="{{$user->id}}">
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->mobile}}</td>
                        <td>{{$user->user_type}}</td>
                        <td>{{date('Y-m-d', $user->created_at->timestamp)}}</td>
                        <td>
                          @if ($user->is_active)
                          <div class="is-active-indicator bg-success"></div> Active
                          @else
                          <div class="is-active-indicator bg-warning"></div> Not Active
                          @endif
                        </td>
                        <td>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle btn btn-sm btn-info" href="#" id="actionsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Actions</a>
                                
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="actionsDropdown">
                                    @if(auth()->user()->isSuperadmin)
                                    <a href="{{route('admin.users.privileges',$user->id)}}" class="dropdown-item">
                                        <i class="fa fa-key"></i> Privileges
                                    </a>
                                    @endif
                                    <a href="{{route('admin.orders', ['filter' => 'all', 'userId' => $user->id])}}" class="dropdown-item">
                                        <i class="fa fa-shopping-cart"></i> Orders
                                    </a>
                                    <a href="{{route('admin.transactions', ['filter' => 'all', 'userId' => $user->id])}}" class="dropdown-item">
                                        <i class="fa fa-euro-sign"></i> Transactions
                                    </a>
                                    <a href="#" class="dropdown-item" data-toggle="modal" data-target="#edit-user-modal-{{$user->id}}">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                    @if (auth()->user()->id != $user->id)
                                    <div class="dropdown-divider"></div>
                                    <a href="#" onclick="document.getElementById('impersonate-form-{{$user->id}}').submit();" class="dropdown-item">
                                        <i class="fa fa-sign-in-alt"></i> Impersonate
                                    </a>
                                    <form id="impersonate-form-{{$user->id}}" method="post" action="{{route('admin.users.impersonate')}}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$user->id}}" />
                                    </form>
                                    @endif
                                </div>
                            </div>
                            
                          <!-- Edit User Modal -->
                          <div class="modal fade" id="edit-user-modal-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                              <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">×</span>
                                          </button>
                                      </div>
                                      <div class="modal-body">
                                          <form method="post" action="{{route('admin.users.update',$user->id)}}">
                                              @csrf
                                              <input type="hidden" name="_method" value="PUT">
                                              <div class="form-group row" style="margin-bottom:30px;">
                                                  <div class="col-md-12">
                                                      <label for="user_type">User Type</label>
                                                      <select name="user_type" id="user_type" class="form-control">
                                                        @foreach ($userTypes as $key => $value)
                                                        <option value="{{$key}}" @if($user->user_type==$key) selected @endif>{{$value}}</option>
                                                        @endforeach
                                                      </select>                                                            
                                                  </div>
                                              </div>
                                              <div class="form-group row" style="margin-bottom:30px;">
                                                  <div class="col-md-12">
                                                      <span> Inactive </span>
                                                      <label class="switch">
                                                          <input type="checkbox" name="is_active" @if($user->is_active) checked @endif >
                                                          <span class="slider round"></span>
                                                          </label>
                                                      <span> Active </span>
                                                  </div>
                                              </div>
                                              <div class="form-group row" style="margin-bottom:30px;">
                                                  <div class="col-md-12" style="text-align:center;">
                                                      <input type="submit" class="btn btn-success" value="Save and close" />
                                                  </div>
                                              </div>
                                          </form>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
  </div>

@endsection