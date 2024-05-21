@extends('layouts.admin.main', ['pageTitle' => 'Import Services', 'active' => 'products'])
@section('content')
<div class="card shadow">
  <div class="card-body">
    <form method="post" action="{{route('admin.products.importServices.post')}}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" value="{{$id}}" />
      <div class="form-group row mb-4">
        <div class="col-md-12">
          <label for="csvFile">Choose CSV data file</label>
          <input type="file" name="csvFile" accept=".csv"  />
        </div>
      </div>
      <div class="form-group row mb-4">
        <div class="col-md-12">
          <label for="zipFile">Choose config ZIP file</label>
          <input type="file" name="zipFile" accept=".zip"  />
        </div>
      </div>
      <div class="form-group row mb-4">
        <div class="col-md-12" style="text-align:center;">
          <input type="submit" class="btn btn-success" value="Import" />
        </div>
      </div>
    </form>
  </div>
</div>
@endsection