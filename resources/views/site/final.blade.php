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
        
      @if ($status == 'success' && isset($now_ts))
      <a href="{{route('downloadZip',$now_ts)}}" target="blank" class="btn btn-success">دانلود تنظیمات</a>
      <hr/>
      <div style="width: 100%; text-align:c center;padding: 8px;">
        <input type="email" name="email" id="email" />
        <button class="btn btn-info" onclick="sendConfigToEmail('{{$now_ts}}')">ارسال تنظیمات به ایمیل</button>
        <div class="form-text text-danger d-none" id="email-response"></div>
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

    function sendConfigToEmail(file) {
      if (!file) {
        return;
      }
      var email = document.getElementById("email").value;
      if (!email) {
        return;
      }
      var responseText = document.getElementById("email-response");
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "{{route('sendConfigToEmail')}}", true);
      xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
      const body = { 
          '_token': "{{csrf_token()}}",
          file,
          email,
      };

      xhr.onreadystatechange = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          var responseJson = JSON.parse(xhr.response);
          responseText.innerHTML = responseJson.message;
          if (responseJson.status === 1) {
            responseText.classList.remove("text-danger", "d-none");
            responseText.classList.add("text-success", "d-block");
          } else {
            responseText.classList.remove("text-success", "d-none");
            responseText.classList.add("text-danger", "d-block");
          }
        }
      };
      xhr.send(JSON.stringify(body));
    }
  </script>
</body>
</html>