@extends('layouts.customer.main', ['pageTitle' => 'Services', 'pageTitleFa' => 'سرویس های من', 'active' => 'services'])
@section('content')

<div class="nk-block nk-block-lg">
  <table class="datatable-init nowrap nk-tb-list is-separate" data-auto-responsive="false">
    <thead>
      <tr class="nk-tb-item nk-tb-head">
        <th class="nk-tb-col"><span>ردیف</span></th>
        <th class="nk-tb-col tb-col-md"><span>عنوان</span></th>
        <th class="nk-tb-col"><span>وضعیت</span></th>
        <th class="nk-tb-col"><span>انقضا</span></th>
        <th class="nk-tb-col"><span>توضیحات</span></th>
        <th class="nk-tb-col"><span>عملیات</span></th>
      </tr>
    </thead>
    <tbody>
    <?php $row = 0; $nowTime = time(); $nowDateTime = new DateTime(); ?>
    @foreach ($services as $service)
      <tr class="nk-tb-item">
        <td class="nk-tb-col">
            <span class="tb-sub">{{++$row}}</span>
        </td>
        <td class="nk-tb-col tb-col-md">
            <span class="tb-lead">{{$service->product->title}}</span>
        </td>
        <td class="nk-tb-col">
          <span class="dot bg-warning d-sm-none"></span>
          @if($service->is_enabled)
          <span class="badge badge-sm badge-dot has-bg d-none d-sm-inline-flex bg-success">فعال</span>
          @else
          <span class="badge badge-sm badge-dot has-bg d-none d-sm-inline-flex bg-danger">غیرفعال</span>
          @endif
        </td>
        <td class="nk-tb-col">
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
                    $time_left_to_show .= "$days_left روز ";
                }
                if ($hours_left > 0) {
                    $time_left_to_show .= "$hours_left ساعت ";
                }
                if ($time_left_to_show == "" && $minutes_left > 0) {
                    $time_left_to_show .= "$minutes_left دقیقه ";
                }
            }
          ?>
          @if($diff > 0)
              @if($days_left > 15)
              <span class="badge badge-sm bg-success">{{$time_left_to_show}}</span>
              @elseif($days_left > 8)
              <span class="badge badge-sm bg-info">{{$time_left_to_show}}</span>
              @else
              <span class="badge badge-sm bg-warning">{{$time_left_to_show}}</span>
              @endif
          @else
              <span class="badge badge-sm bg-danger">expired</span>
          @endif
          @else
          -
          @endif
        </td>
        <td class="nk-tb-col">
          <span class="tb-lead">
          <a href="#" class="btn btn-icon btn-trigger btn-tooltip" data-bs-toggle="modal" data-bs-target="#noteModal-{{$service->id}}">
            <em class="icon ni ni-pen"></em> {{$service->note}}
          </a>
          </span>
        </td>
        <td class="nk-tb-col nk-tb-col-tools">
          <ul class="nk-tb-actions">
            <li>
              <a href="{{route('customer.services.download',$service->id)}}" class="btn btn-icon btn-trigger btn-tooltip">
                <em class="icon ni ni-download"></em> دانلود
              </a>
              <a href="#" class="btn btn-icon btn-trigger btn-tooltip" data-bs-toggle="modal" data-bs-target="#cartModal-{{$service->id}}">
                <em class="icon ni ni-repeat"></em> تمدید
              </a>
              @if($service->product->iType == 'limited')
              <a href="#" class="btn btn-icon btn-trigger btn-tooltip">
                <em class="icon ni ni-chart-up"></em> آمار مصرف
              </a>
              @endif
            </li>
          </ul>
        </td>

        <!-- Update Note Modal -->
        <div class="modal fade" id="noteModal-{{$service->id}}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <form action="{{route('customer.services.updateNote',$service->id)}}" class="form-validate is-alter" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="PUT">
                            <div class="form-group">
                                <label class="form-label" for="note">توضیحات</label>
                                <div class="form-control-wrap">
                                    <input type="text" class="form-control" name="note" id="note" value="{{$service->note}}" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-primary">ذخیره اطلاعات</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cartModal-{{$service->id}}">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="cartModalLabel">تمدید سرویس</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <ul class="cart-list" id="cart-list"></ul>
                  <hr />
                  <div style="display: flex; justify-content: space-between;">
                      <div style="flex: 1">مبلغ قابل پرداخت</div>
                      <div style="flex: 1; text-align: left;">
                          <span>{{number_format($service->product->price)}} تومان</span>
                      </div>
                  </div>
                  <hr />

                  <div style="display: flex; justify-content: space-between; align-items: center;">
                      <div style="flex: 2">
                          <input type="text" class="form-control" name="discount-code" id="discount-code" placeholder="کد تخفیف دارید؟">
                          <div class="form-text text-danger d-none" id="check-discount-response"></div>
                      </div>
                      <div style="flex: 1; text-align: left;">
                          <button type="button" id="check-discount-code-btn" class="btn btn-sm btn-success d-inline" onclick="checkDiscountCode()">ثبت کد</button>
                          <button type="button" id="remove-discount-code-btn" class="btn btn-sm btn-danger d-none" onclick="removeDiscountCode()">حدف کد</button>
                      </div>
                  </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-primary" onclick="renewService()">پرداخت</button>
              </div>
              </div>
          </div>
        </div>
      </tr>
      @endforeach
    </tbody>
  </table>
  <!-- .nk-tb-list -->
</div>
<!-- .nk-block -->
<script>
  function checkDiscountCode() {}
  function removeDiscountCode() {}
  function renewService() {}
</script>
@endsection