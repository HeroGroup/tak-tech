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
    <?php $row = 0; $nowTime = time(); ?>
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
              $total_left = explode('.', round($diff / (60 * 60 * 24), 2));
              $days_left = $total_left[0] ?? 0;
              $hours_left = floor(($total_left[1] ?? 0) / 100 * 24);
              $time_left_to_show = "";
              if ($days_left > 0) {
                $time_left_to_show .= "$days_left روز ";
              }
              if ($hours_left > 0) {
                $time_left_to_show .= "$hours_left ساعت";
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
              <a href="{{route('customer.services.download',['id'=>$service->id,'files'=>'all'])}}" class="btn btn-icon btn-trigger btn-tooltip">
                <em class="icon ni ni-download"></em> دانلود
              </a>
              <a href="#" class="btn btn-icon btn-trigger btn-tooltip" onclick="createCart('{{$service->id}}','{{$service->product_id}}','{{$service->product->title}}','{{$service->product->price}}')" data-bs-toggle="modal" data-bs-target="#cartModal-{{$service->id}}">
                <em class="icon ni ni-repeat"></em> تمدید
              </a>
              <!-- @if($service->product->iType == 'limited')
              <a href="#" class="btn btn-icon btn-trigger btn-tooltip">
                <em class="icon ni ni-chart-up"></em> آمار مصرف
              </a>
              @endif -->
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
                <div style="display: flex; justify-content: space-between;">
                  <div style="flex: 1">موجودی کیف پول</div>
                  <div style="flex: 1; text-align: left;">
                    <span class="cart-sum">{{number_format(auth()->user()->wallet)}} تومان</span>
                  </div>
                </div>
                  
                <hr />
                  
                <div style="display: flex; justify-content: space-between;">
                  <div style="flex: 1">مبلغ قابل پرداخت</div>
                    <div style="flex: 1; text-align: left;">
                      <span class="cart-sum">{{number_format($service->product->price - auth()->user()->wallet)}} تومان</span>
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
  var serviceId;
  var cart = {};
  var discountCodeEnabled = false;
  var wallet = "{{auth()->user()->wallet}}";

  function createCart(_serviceId, productId, title, price) {
    serviceId = _serviceId;
    cart[productId] = {count: 1, title, price};
  }
  function implementDiscountCodeOnUserCart(data) {
    var applied = false;
    if (!discountCodeEnabled) {
      var discount = data.discount;
      var discountDetails = data.discountDetails;
      var cartSums = document.getElementsByClassName("cart-sum");
      if (discountDetails) {
        // process user cart
        jQuery.each(cart, function(index, value) {
          for (var i=0; i < discountDetails.length; i++) {
            if (index == discountDetails[i].product_id) {
              if (discountDetails[i].discount_percent) {
                cart[index].finalPrice = cart[index].price * ((100 - parseInt(discountDetails[i].discount_percent)) / 100);
              } else if (discountDetails[i].fixed_amount) {
                cart[index].finalPrice = cart[index].price - discountDetails[i].fixed_amount;
              }
              cart[index].finalPrice = cart[index].finalPrice < 0 ? 0 : cart[index].finalPrice;
              Array.prototype.forEach.call(cartSums, function(element) {
                element.innerHTML = `
                  <s>${new Intl.NumberFormat().format(cart[index].price)} تومان</s>
                  <div class="text-success">${new Intl.NumberFormat().format(cart[index].finalPrice)} تومان</div>
                `;
              });
              applied = true;
            }
          }
        });

        discountCodeEnabled = true;
      } else if (discount) {
        // manipulate final price
        var basePrice = 0;
        var finalPrice = 0;

        Object.keys(cart).forEach((item) => {
            basePrice += cart[item].price * cart[item].count;
        });

        if (discount.discount_percent) {
            finalPrice = basePrice * (100 - parseInt(discount.discount_percent)) / 100;
        } else if (discount.fixed_amount) {
            finalPrice = basePrice - discount.fixed_amount;
        }

        finalPrice = finalPrice <= 0 ? 0 : finalPrice;

        var cartSums = document.getElementsByClassName("cart-sum");
        Array.prototype.forEach.call(cartSums, function(element) {
          element.innerHTML = `
            <s>${new Intl.NumberFormat().format(basePrice)} تومان</s>
            <div class="text-success">${new Intl.NumberFormat().format(finalPrice)} تومان</div>
          `;
        });
        applied = true;
      }
    }
    return applied;
  }
  function checkDiscountCode() {
    removeDiscountCode(false);
    var code = document.getElementById("discount-code");
    if (!code.value) {
      return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', `/discounts/checkDiscountCode/${code.value}`, true);
    xhr.addEventListener("load", function() {
      var responseText = document.getElementById("check-discount-response");
      var checkDiscountCodeBtn = document.getElementById("check-discount-code-btn");
      var removeDiscountCodeBtn = document.getElementById("remove-discount-code-btn");
      var response = JSON.parse(xhr.response);

      if(response.status === 1) {
        var applied = implementDiscountCodeOnUserCart(response.data);
        responseText.innerHTML = response.message;
                        
        responseText.classList.remove("text-danger", "d-none");
        responseText.classList.add("text-success", "d-block");

        checkDiscountCodeBtn.classList.remove("d-inline");
        checkDiscountCodeBtn.classList.add("d-none");
                        
        removeDiscountCodeBtn.classList.remove("d-none");
        removeDiscountCodeBtn.classList.add("d-inline");
        if (!applied) {
          responseText.innerHTML = 'این کد تخفیف برای محصولات انتخابی شما معتبر نمی باشد';
        }
      } else {
        responseText.innerHTML = response.message;
        responseText.classList.remove("text-success", "d-none");
        responseText.classList.add("text-danger", "d-block");
      }
    });
    xhr.send();
  }
  function removeDiscountCode(clear=true) {
    if (clear) {
      document.getElementById("discount-code").value = "";
    }

    var basePrice = 0;
    // remove all finalPrices from cart
    jQuery.each(cart, function(index, value) {
      delete cart[index].finalPrice;
      basePrice = cart[index].price;
    });

    // change button
    var checkDiscountCodeBtn = document.getElementById("check-discount-code-btn");
    var removeDiscountCodeBtn = document.getElementById("remove-discount-code-btn");

    checkDiscountCodeBtn.classList.add("d-inline");
    checkDiscountCodeBtn.classList.remove("d-none");
                        
    removeDiscountCodeBtn.classList.add("d-none");
    removeDiscountCodeBtn.classList.remove("d-inline");

    var helpText = document.getElementById("check-discount-response");
    helpText.classList.add("d-none");
    helpText.classList.remove("d-block");

    var cartSums = document.getElementsByClassName("cart-sum");
    Array.prototype.forEach.call(cartSums, function(element) {
      element.innerHTML = `${new Intl.NumberFormat().format(basePrice-wallet)} تومان`;
    });

    discountCodeEnabled = false;
  }
  function renewService() {
    // send cart and discount code to server
    var route = "{{route('customer.services.renew')}}";
    const xhr = new XMLHttpRequest();
    xhr.open("POST", route, true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    const body = { 
      '_token': "{{csrf_token()}}",
      id: serviceId,
      cart: JSON.stringify(cart),
      discountCode: document.getElementById("discount-code").value,
    };

    xhr.onreadystatechange = () => {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        window.location = xhr.response;
      }
    };
    xhr.send(JSON.stringify(body));
  }
</script>
@endsection