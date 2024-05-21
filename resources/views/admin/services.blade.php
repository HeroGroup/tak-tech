@extends('layouts.admin.main', ['pageTitle' => 'Services', 'active' => 'services'])
@section('content')
<div class="row col-md-12 mb-4 filter-btns">
    <a href="#" onclick="searchServices('all')" class="filter-btn border-bottom-warning">
        <span class="text-gray-900">All</span>&nbsp;<span class="text-warning">{{$numberOfSoldServices+$numberOfFreeServices}}</span>
    </a>
    <a href="#" onclick="searchServices('1')" class="filter-btn border-bottom-success">
        <span class="text-gray-900">Sold</span>&nbsp;<span class="text-success">{{$numberOfSoldServices}}</span>
    </a>
    <a href="#" onclick="searchServices('0')" class="filter-btn border-bottom-info">
        <span class="text-gray-900">Free</span>&nbsp;<span class="text-info">{{$numberOfFreeServices}}</span>
    </a>
</div>
  <div class="card shadow mb-4">
        <div class="card-body">
            <x-paginator :route="route('admin.services.index')" :selectedCount="0" :isLastPage="$isLastPage" />
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>row</th>
                            <th>Product</th>
                            <th>Status</th>
                            <th>Activated Date</th>
                            <th>Expires</th>
                            <th>Enabled</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $row = 0; $nowTime = time(); $nowDateTime = new DateTime(); ?>
                        @foreach($services as $service)
                        <tr id="{{$service->id}}">
                            <td>{{++$row}}</td>
                            <td>{{$service->product->title}}</td>
                            <td>
                                @if($service->is_sold==1)
                                <span class="badge badge-success">sold<span>
                                @else
                                <span class="badge badge-info">free</span>
                                @endif
                            </td>
                            <td>{{substr($service->activated_at, 0, 16)}}</td>
                            <td>
                            @if($service->expire_days && $service->activated_at)
                            <?php 
                                $expire = $service->expire_days;
                                $diff = strtotime($service->activated_at. " + $expire days") - $nowTime; 
                                if ($diff > 0) {
                                    $expires_on = new DateTime($service->activated_at);
                                    $expires_on->add(new DateInterval("P$expire"."D"));
                                    $time_left = $expires_on->diff($nowDateTime);
                                    $days_left = $time_left->m*30 + $time_left->d;
                                    $hours_left = $time_left->h;
                                    $minutes_left = $time_left->i;
                                    // $seconds_left = $time_left->s;
                                    $time_left_to_show = "";
                                    if ($days_left > 0) {
                                        $time_left_to_show .= "$days_left days ";
                                    }
                                    if ($hours_left > 0) {
                                        $time_left_to_show .= "$hours_left hours ";
                                    }
                                    if ($time_left_to_show == "" && $minutes_left > 0) {
                                        $time_left_to_show .= "$minutes_left minutes ";
                                    }
                                }
                            ?>
                            @if($diff > 0)
                                @if($days_left > 15)
                                <div class="badge badge-success">{{$time_left_to_show}}</div>
                                @elseif($days_left > 8)
                                <div class="badge badge-info">{{$time_left_to_show}}</div>
                                @else
                                <div class="badge badge-warning">{{$time_left_to_show}}</div>
                                @endif
                            @else
                                <div class="badge badge-danger">expired</div>
                            @endif
                            @else
                            -
                            @endif
                            </td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" name="Enabled" id="enabled_{{$service->id}}" @if($service->is_enabled) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<script>
    var baseRoute = "{{route('admin.services.index')}}";
    function searchBase(set={}) {
        var queryString = window.location.search;
        var urlParams = new URLSearchParams(queryString);
        urlParams.delete('page');

        var params = Object.keys(set);
        params.forEach(key => {
            urlParams.set(key, set[key]);
        });

        window.location.href = `${baseRoute}?${urlParams.toString()}`;
    }
    function searchServices(filter) {
        searchBase({ 'is_sold': filter });
    }
</script>
@endsection