<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{config('app.name')}} | order</title>
  <link rel="stylesheet" href="/assets/css/styles.rtl.css" />
</head>
<body dir="rtl">
  <div class="container-fluid center">
    <div class="card center" style="margin-top: 100px; padding: 2rem; width: 500px;">
      @if ($status == 'success')
      <img src="/assets/img/success-icon.png" width="128" height="128" alt="موفق" />  
      @else
      <img src="/assets/img/error-icon.png" width="128" height="128" alt="خطا" />
      @endif
      <div class="card-inner">
        @if ($status == 'success')
        <h5 class="card-title">{{$message}}</h5>
        <div class="card-text" style="display: flex; justify-content: space-between;">
          <label>شماره پیگیری</label>
          <label>{{isset($ref_id) ? $ref_id : ''}}</label>
        </div>
        @else
        <h5 class="card-title">خطا در ثبت سفارش!</h5>
        <h6 class="center">{{$message}}</h6>
        @endif  
      </div>
        
      @if ($status == 'success')
      <a href="{{route('downloadZip',$now_ts)}}" target="blank" class="btn btn-success">دانلود تنظیمات</a>
      <hr/>
      <div style="width: 100%; text-align:c center;padding: 8px;">
        <form action="#" method="#">
          @csrf
          <input type="email" name="email" id="email" />
          <input type="submit" value="ارسال تنظیمات به ایمیل" class="btn btn-info" />
        </form>
      </div>
      <hr/>
      @endif
      <a href="/" class="btn btn-primary">بازگشت به صفحه اصلی</a>
    </div>
  </div>
  
  <script>
    // if success clear cart from storage
    var status = "{{$status}}";
    if (status === 'success') {
      localStorage.removeItem("cart");
    }
  </script>
</body>
</html>