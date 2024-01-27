@extends('layouts.admin.main', ['pageTitle' => '', 'active' => 'discounts'])
@section('content')
<div class="card shadow mb-4">
  <div class="card-header py-3">
    <h6 class="m-0 font-weight-bold text-primary">Edit Discount Code</h6>
  </div>
  <div class="card-body">
    <form method="post" action="{{route('admin.discounts.update', $discount)}}">
      @csrf
      <input type="hidden" name="_method" value="PUT" />
      <div class="form-group row">
        <div class="col-sm-12">
          <span> Inactive </span>
          <label class="switch">
            <input type="checkbox" name="is_active" @if($discount->is_active) checked @endif >
            <span class="slider round"></span>
          </label>
          <span> Active </span>
        </div>
      </div>
      <div class="form-group row">
        <label for="code" class="col-sm-2 col-form-label">Code</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="code" id="code" value="{{$discount->code}}" placeholder="Enter Code" required disabled>
        </div>
      </div>
      <div class="form-group row">
        <label for="title" class="col-sm-2 col-form-label">Title</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" name="title" id="title" value="{{$discount->title}}" placeholder="Enter Title" required>
        </div>
      </div>
      <div class="form-group row">
        <label for="description" class="col-sm-2 col-form-label">Description</label>
          <div class="col-sm-10">
            <input type="text" class="form-control" name="description" id="description" value="{{$discount->description}}" placeholder="Enter description">
          </div>
      </div>

      <div class="form-group row">
        <label for="discount_percent" class="col-sm-2 col-form-label">Percent</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" name="discount_percent" id="discount_percent" value="{{$discount->discount_percent}}" placeholder="Enter Discount Percent">
          </div>
      </div>

      <div class="form-group row">
        <label for="fixed_amount" class="col-sm-2 col-form-label">Fixed Amount</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" name="fixed_amount" id="fixed_amount" value="{{$discount->fixed_amount}}" placeholder="Discount Fixed Amount">
          </div>
      </div>

      <div class="form-group row">
        <label for="expire_date" class="col-sm-2 col-form-label">Expire Date</label>
          <div class="col-sm-10">
            <input type="date" class="form-control" name="expire_date" id="expire_date" value="{{$discount->expire_date}}" placeholder="Enter Expire Date">
          </div>
      </div>

      <div class="form-group row">
        <label for="capacity" class="col-sm-2 col-form-label">Capacity</label>
          <div class="col-sm-10">
            <input type="number" class="form-control" name="capacity" id="capacity" value="{{$discount->capacity}}" placeholder="Enter Capacity">
          </div>
      </div>

      <div class="form-group row">
        <label for="forUser" class="col-sm-2 col-form-label">forUser</label>
        <div class="col-sm-10">
          <select name="forUser" id="forUser" class="form-control">
            <option value="">Select a User</option>
          @foreach ($users as $key => $value)
            <option value="{{$key}}" @if ($discount->forUser==$key) selected="true" @endif>{{$value}}</option>
          @endforeach
          </select>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table" width="100%" cellspacing="0" style="margin-bottom:50px;">
          <thead>
            <th>product</th>
            <th>percent</th>
            <th>amount</th>
            <th><button type="button" class="btn btn-primary" onclick="addRow()"><i class="fas fa-plus"></i></button></th>
          </thead>
          <tbody id="prosucts-table-body">
            @foreach ($discountDetails as $detailKey => $detail)
              <tr id="{{$detailKey}}">
                <td>
                  <select name="product_id[]">
                    <option value="">Select a Product</option>
                    @foreach ($products as $key => $value)
                    <option value="{{$key}}" @if ($detail->product_id==$key) selected="true" @endif>{{$value}}</option>
                    @endforeach
                  </select>
                </td>
                <td><input type="number" class="form-control" name="product_discount_percent[]" value="{{$detail->discount_percent}}" /></td>
                <td><input type="number" class="form-control" name="product_fixed_amount[]" value="{{$detail->fixed_amount}}" /></td>
                <td><button type="button" class="btn btn-danger" onclick="document.getElementById('{{$detailKey}}').remove()"><i class="fas fa-trash"></i></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="form-group row">
        <div class="col-md-12 text-right">
          <input type="submit" class="btn btn-primary" value="Save" />
          <a href="{{route('admin.discounts.index')}}" class="btn">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
  var rownum = "count($discountDetails)+1";
  function addRow() {
    // append to tbody
    var tbody = document.getElementById('prosucts-table-body');
    var row = `
        <td>
          <select name="product_id[]">
            <option value="">Select a Product</option>
            @foreach ($products as $key => $value)
            <option value="{{$key}}">{{$value}}</option>
            @endforeach
          </select>
        </td>
        <td><input type="number" class="form-control" name="product_discount_percent[]" /></td>
        <td><input type="number" class="form-control" name="product_fixed_amount[]" /></td>
        <td><button type="button" class="btn btn-danger" onclick="document.getElementById(${rownum}).remove()"><i class="fas fa-trash"></i></td>
    `;

    let tr = document.createElement("tr");
    tr.innerHTML = row;
    tbody.append(tr);
    $('select[name="product_id[]"]').selectize({
      sortField: 'text'
    });
  }
</script>
@endsection