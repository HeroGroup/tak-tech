<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{config('app.name')}} | order</title>
  <link rel="stylesheet" href="/assets/css/dashlite.rtl.css" />
</head>
<body dir="rtl">
  <div class="container-fluid center">
    <div class="card center" style="margin-top: 100px; padding: 2rem; width: 400px;">
      @if ($status == 'success')
      <img src="/assets/img/success-icon.png" width="128" height="128" alt="success" />  
      @else
      <img src="/assets/img/error-icon.png" width="128" height="128" alt="error" />
      @endif
      <div class="card-inner">
        @if ($status == 'success')
        <h5 class="card-title">سفارش با موفقیت ثبت شد!</h5>
        @else
        <h5 class="card-title">خطا در ثبت سفارش!</h5>
        @endif
        <div class="card-text" style="display: flex; justify-content: space-between;">
            <label>شماره پیگیری</label>
            <label>12345678</label>
          </div>
        </div>
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