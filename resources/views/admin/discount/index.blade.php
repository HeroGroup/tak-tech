@extends('layouts.admin.main', ['pageTitle' => 'Discount Codes', 'active' => 'discounts'])
@section('content')
    <div class="mb-4">
        <a href="{{route('admin.discounts.create')}}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">new discount code</span>
        </a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Expire Date</th>
                            <th>Capacity</th>
                            <th>Used</th>
                            <th>Created At</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($discounts as $discount)
                        <tr id="{{$discount->id}}">
                            <td>{{$discount->code}}</td>
                            <td>{{substr($discount->expire_date, 0, 10)}}</td>
                            <td>{{$discount->capacity}}</td>
                            <td>{{$discount->used}}</td>
                            <td>{{date('Y-m-d', $discount->created_at->timestamp)}}</td>
                            <?php 
                              $creator = \App\Models\User::find($discount->created_by); 
                              $updator = \App\Models\User::find($discount->updated_by); 
                            ?>
                            <td>{{$creator->name ?? $creator->email}}</td>
                            <td>{{$updator->name ?? $updator->email}}</td>
                            <td>
                                <a href="{{route('admin.discounts.edit', $discount)}}" class="btn btn-info btn-circle btn-sm" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-circle btn-sm" title="Delete" onclick="destroy('{{route('admin.discounts.destroy',$discount)}}','{{$discount->id}}','{{$discount->id}}')">
                                    <i class="fas fa-trash"></i>
                                </a>
                                &nbsp;
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        </div>
    </div>

@endsection
