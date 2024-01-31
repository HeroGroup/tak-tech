@extends('layouts.admin.main', ['pageTitle' => 'Products', 'active' => 'products'])
@section('content')
  <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Products</h6>
        </div>
        <div class="card-body">
            <a class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#new-product-modal">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">new product</span>
            </a>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Categories</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr id="{{$product->id}}">
                            <td>
                            <object data="{{$product->image_url}}" type="image/png" width="32" height="32" class="center mb-4" style="width:100%">
                                <img src="/assets/img/undraw_rocket.svg" alt="product image" width="32" height="32">
                            </object>
                            </td>
                            <td>{{$product->title}}</td>
                            <td>{{$product->description}}</td>
                            <td>{{$product->price}}</td>
                            <td>
                              <ul style="margin:0">
                              @foreach ($product->categories as $productCategory)
                                <li>{{$productCategory->category->title}}</li>  
                              @endforeach
                              </ul>
                            </td>
                            <td>
                              @if ($product->is_active)
                              <div class="is-active-indicator bg-success"></div> Active
                              @else
                              <div class="is-active-indicator bg-warning"></div> Not Active
                              @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-info btn-circle btn-sm" data-toggle="modal" data-target="#edit-product-modal-{{$product->id}}" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                &nbsp;

                                <!-- Edit Product Modal -->
                                <div class="modal fade" id="edit-product-modal-{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="post" action="{{route('admin.products.update',$product->id)}}" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="PUT">
                                                    <div class="form-group row">
                                                        <div class="col-md-12">
                                                            <object data="{{$product->image_url}}" type="image/png" width="64" height="64" class="center mb-4" style="width:100%">
                                                            <img src="/assets/img/undraw_rocket.svg" alt="product image" width="64" height="64">
                                                        </object>
                                                        <label for="photo">Choose Photo for Product</label>
                                                        <input type="file" name="photo" accept="image/*"  />
                                                    </div>
                                                        
                                                    </div>
                                                    <div class="form-group row" style="margin-bottom:30px;">
                                                      <div class="col-md-12">
                                                        <label for="categories">Categories</label>
                                                        <select name="categories[]" id="categories[]" class="form-control" multiple>
                                                        @foreach ($categories as $key => $value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                        @endforeach
                                                        </select>
                                                      </div>
                                                    </div>
                                                    <div class="form-group row" style="margin-bottom:30px;">
                                                        <div class="col-md-12">
                                                            <label for="title">Title</label>
                                                            <input class="form-control" name="title" value="{{$product->title}}" placeholder="Enter product name" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" style="margin-bottom:30px;">
                                                        <div class="col-md-12">
                                                            <label for="description">Description</label>
                                                            <input class="form-control" name="description" value="{{$product->description}}" placeholder="Enter product description">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row" style="margin-bottom:30px;">
                                                        <div class="col-md-12">
                                                            <label for="price">Price</label>
                                                            <input class="form-control" name="price" value="{{$product->price}}" placeholder="Enter product title" required>
                                                        </div>
                                                    </div>
                                                    <div>
                                                      <span> Not Featured </span>
                                                      <label class="switch">
                                                          <input type="checkbox" name="is_featured" @if($product->is_featured) checked @endif >
                                                          <span class="slider round"></span>
                                                        </label>
                                                      <span> Featured </span>
                                                    </div>
                                                    <div>
                                                      <span> Inactive </span>
                                                      <label class="switch">
                                                          <input type="checkbox" name="is_active" @if($product->is_active) checked @endif >
                                                          <span class="slider round"></span>
                                                        </label>
                                                      <span> Active </span>
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

                                <a href="#" class="btn btn-danger btn-circle btn-sm" title="Delete" onclick="destroy('{{route('admin.products.destroy',$product->id)}}','{{$product->id}}','{{$product->id}}')">
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


    <!-- Create Product Modal -->
    <div class="modal fade" id="new-product-modal" tabindex="-1" role="dialog" aria-labelledby="newProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newProductModalLabel">Add new product</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{route('admin.products.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row" style="margin-bottom:30px;">
                          <div class="col-md-12">
                            <label for="categories">Categories</label>
                            <select name="categories[]" id="categories[]" class="form-control" multiple>
                              @foreach ($categories as $key => $value)
                              <option value="{{$key}}">{{$value}}</option>
                              @endforeach
                            </select>
                          </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:30px;">
                            <div class="col-md-12">
                                <label for="title">Title</label>
                                <input class="form-control" name="title" value="{{old('title')}}" placeholder="Enter product name" required>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:30px;">
                            <div class="col-md-12">
                                <label for="description">Description</label>
                                <input class="form-control" name="description" value="{{old('description')}}" placeholder="Enter product description">
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:30px;">
                            <div class="col-md-12">
                                <label for="price">Price</label>
                                <input class="form-control" name="price" value="{{old('price')}}" placeholder="Enter product price" required>
                            </div>
                        </div>
                        <div class="form-group row" style="margin-bottom:30px;">
                            <div class="col-md-12">
                                <label for="photo">Choose Photo for Product</label>
                                <input type="file" name="photo" accept="image/*"  />
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