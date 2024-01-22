<!DOCTYPE html>
<html lang="fa">

<head>
    <title>{{ env('APP_NAME')}} | {{$pageTitle}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">

    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="/assets/css/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/fonts/font-awesome/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="/assets/fonts/flaticon/font/flaticon.css">

    <!-- Favicon icon -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" >

    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="/assets/css/style.css">

</head>
<body id="top" dir="rtl">
<div class="page_loader"></div>

<div class="login-16">
    <div class="container">
        <div class="row login-box">
            <div class="col-lg-5 form-section">
                <div class="form-inner">
                    <h3>{{$pageTitleFa}}</h3>
                    
                    @yield('content')
                    
                </div>
            </div>
        </div>
    </div>
</div>


<!-- External JS libraries -->
<script src="/assets/js/jquery-3.6.0.min.js"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/jquery.validate.min.js"></script>
<script src="/assets/js/app.js"></script>

</body>

</html>
