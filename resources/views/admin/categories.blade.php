@extends('layouts.admin.main', ['pageTitle' => 'Categories', 'active' => 'categories'])
@section('content')
    <div class="mb-4">
        <a class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#new-category-modal">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">new category</span>
        </a>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr id="{{$category->id}}">
                            <td>{{$category->title}}</td>
                            <td>
                              @if ($category->is_active)
                              <div class="is-active-indicator bg-success"></div> Active
                              @else
                              <div class="is-active-indicator bg-warning"></div> Not Active
                              @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-info btn-circle btn-sm" data-toggle="modal" data-target="#edit-category-modal-{{$category->id}}" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                &nbsp;

                                <!-- Edit Category Modal -->
                                <div class="modal fade" id="edit-category-modal-{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="{{route('admin.categories.update',$category->id)}}">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="PUT">
                                                    <div class="form-group row" style="margin-bottom:30px;">
                                                        <div class="col-md-12">
                                                            <label for="title">Title</label>
                                                            <input class="form-control" name="title" value="{{$category->title}}" placeholder="Enter category name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" style="margin-bottom:30px;">
                                                        <div class="col-md-12">
                                                            <span> Inactive </span>
                                                            <label class="switch">
                                                                <input type="checkbox" name="is_active" @if($category->is_active) checked @endif >
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

                                <a href="#" class="btn btn-danger btn-circle btn-sm" title="Delete" onclick="destroy('{{route('admin.categories.destroy',$category->id)}}','{{$category->id}}','{{$category->id}}')">
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
    </div>


    <!-- Create Category Modal -->
    <div class="modal fade" id="new-category-modal" tabindex="-1" role="dialog" aria-labelledby="newCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newCategoryModalLabel">Add new category</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('admin.categories.store')}}">
                        @csrf
                        <div class="form-group row" style="margin-bottom:30px;">
                            <div class="col-md-12">
                                <label for="title">Title</label>
                                <input class="form-control" name="title" value="{{old('title')}}" placeholder="Enter category name" required>
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
@endsection
