@extends('layouts.site', ['title' => 'home', 'cart' => $cart])
@section('content')
    <section class="section section-service pb-0" id="service">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-sm-7 col-md-6 col-9">
                    <div class="section-head">
                        <h4 class="title">امکانات تک وی پی ان</h4>
                    </div>
                    <!-- .section-head -->
                </div>
                <!-- .col -->
            </div>
            <!-- .row -->
            <div class="section-content">
                <div class="row justify-content-center text-center g-gs">
                    <div class="col-8 col-sm-6 col-md-3">
                        <div class="service service-s2">
                            <div class="service-icon styled-icon styled-icon-s2">
                                <img src="/assets/img/guaranty.svg" alt="ضمانت بازگشت وجه">
                            </div>
                            <div class="service-text">
                                <h6 class="title">ضمانت بازگشت وجه</h6>
                                <p>بازگشت وجه تا ۷ روز (بدون نیاز به ارائه دلیل)</p>
                            </div>
                        </div>
                        <!-- .service -->
                    </div>
                    <!-- .col -->
                    <div class="col-8 col-sm-6 col-md-3">
                        <div class="service service-s2">
                            <div class="service-icon styled-icon styled-icon-s2">
                                <img src="/assets/img/security.svg" alt="نهایت امنیت">
                            </div>
                            <div class="service-text">
                                <h6 class="title">نهایت امنیت</h6>
                                <p>رمزنگاری اطلاعات شما با تکنولوژی های روز دنیا</p>
                            </div>
                        </div>
                        <!-- .service -->
                    </div>
                    <!-- .col- -->
                    <div class="col-8 col-sm-6 col-md-3">
                        <div class="service service-s2">
                            <div class="service-icon styled-icon styled-icon-s2">
                                <img src="/assets/img/multi-country.svg" alt="اتصال به چند کشور">
                            </div>
                            <div class="service-text">
                                <h6 class="title">اتصال به چند کشور</h6>
                                <p>قابلیت اتصال VPN به بیش از ۱۰ کشور مختلف</p>
                            </div>
                        </div>
                        <!-- .service -->
                    </div>
                    <!-- .col- -->
                    <div class="col-8 col-sm-6 col-md-3">
                        <div class="service service-s2">
                            <div class="service-icon styled-icon styled-icon-s2">
                                <img src="/assets/img/all-devices.svg" alt="مناسب تمام دستگاه ها">
                            </div>
                            <div class="service-text">
                                <h6 class="title">مناسب تمام دستگاه ها</h6>
                                <p>سازگار با تمام دستگاه‌ها و سیستم‌عامل‌ها</p>
                            </div>
                        </div>
                        <!-- .service -->
                    </div>
                    <!-- .col -->
                </div>
                <!-- .row -->
            </div>
        </div>
    </section>
    <!-- .container -->
    <section class="section section-feature" id="feature">
        <div class="container">
            <div class="row align-items-center justify-content-between g-gs">
                <div class="col-lg-5">
                    <div class="img-block img-block-s1 left">
                        <img src="/assets/img/map.svg" alt="تصویر" />
                    </div>
                </div>
                <!-- .col -->
                <div class="col-lg-6">
                    <div class="text-block">
                        <h4 class="title">دارای سرورهای متعدد</h4>
                        <p>اتصال به سرور‌هایی از معتبر‌ترین ارائه دهندگان جهان با نهایت سرعت و بدون قطعی</p>
                    </div>
                    <!-- .text-block -->
                </div>
                <!-- .col -->
            </div>
            <!-- .row -->
        </div>
        <!-- .container -->
    </section>
    <!-- .section -->
    <section class="section section-pricing bg-lighter" id="pricing">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-md-5 col-sm-7">
                    <div class="section-head text-center">
                        <h5 class="title">سرویس مورد نظر خود را انتخاب کنید</h5>
                    </div>
                    <!-- .section-head -->
                </div>
                <!-- .col -->
            </div>
            <!-- .row -->
            @if (isset($products) && count($products) > 0)
                <div class="row justify-content-center g-gs">
                @foreach ($products as $product)
                    <?php $features = explode('-', $product->description); ?>
                    <div class="col-xl-4 col-lg-4 col-sm-6">
                        <div class="pricing pricing-s3 @if($product->is_featured) pricing-featured @endif card card-shadow card-bordered round-xl">
                            <div class="card-inner card-inner-lg">
                                <?php $image_url = $product->image_url ?? "/assets/img/undraw_rocket.svg"; ?>
                                <div class="center mb-4">
                                    <img src="{{$image_url}}" alt="product image" width="64" height="64">
                                </div>
                                <h4 class="title pb-2 fw-normal center">{{$product->title}}</h4>
                                <span class="pb-4 fw-medium center sub-title">{{number_format($product->price)}} تومان / {{$product->period ?? 'ماهانه'}}</span>
                                <ul class="list list-success list-check gy-2">
                                    @foreach ($features as $feature)
                                    <li>{{$feature}}</li>  
                                    @endforeach
                                </ul>
                                <div class="pricing-action" id="pricing-action-{{$product->id}}">
                                    <a href="#" class="btn btn-outline-light btn-lg btn-block" onclick="addToCart({{$product->id}}, '{{$product->title}}', '{{$product->price}}', 'increase', '{{$product->CNT}}')"><span>انتخاب</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        <!-- .container -->
    </section>
    <!-- .section -->
    <section class="section section-testimonial pb-0" id="reviews">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-xl-7 col-md-8 col-10">
                    <div class="section-head">
                        <h4 class="title">نظرات مشتریان</h4>
                    </div>
                </div>
                <!-- .col -->
            </div>
            <!-- .row -->
            <div class="row g-gs justify-content-center">
            <div class="col-lg-4">
                    <div class="card card-shadow round-xl">
                        <div class="card-inner card-inner-lg">
                            <div class="review review-s2">
                                <!-- <div class="review-portrait review-portrait-s1">
                                    <div class="portrait portrait-s1">
                                        <img src="/assets/images/client/client-a.png" alt="" />
                                    </div>
                                </div> -->
                                <div class="review-content">
                                    <div class="review-rating rating rating-sm">
                                        <ul class="rating-stars">
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                        </ul>
                                    </div>
                                    <div class="review-text">
                                        <p class="comment-content">واقعا از خدمات شما متشکرم . خیلی خوبه که یک سایتی هستش که می‌تونیم همیشه با اطمینان باهاش کارکرد و پاسخ گوی مشتری هستند و هرچقدر سوال هم بپرسی حوصلشون سر نمیره .</p>
                                        <h6 class="review-name text-dark">خانم رایندی</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- .review -->
                        </div>
                    </div>
                    <!-- .testimonial-block -->
                </div>
                <!-- .col -->
                <div class="col-lg-4">
                    <div class="card card-shadow round-xl">
                        <div class="card-inner card-inner-lg">
                            <div class="review review-s2">
                                <!-- <div class="review-portrait review-portrait-s1">
                                    <div class="portrait portrait-s1">
                                        <img src="/assets/images/client/client-a.png" alt="" />
                                    </div>
                                </div> -->
                                <div class="review-content">
                                    <div class="review-rating rating rating-sm">
                                        <ul class="rating-stars">
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                        </ul>
                                    </div>
                                    <div class="review-text">
                                        <p>من یه راننده‌ی اسنپ هستم و تقریبا ۱۰ ساعت از روز رو برای مسیریابی از وی پی ان استفاده می‌کنم. دمتون گرم فقط همین!</p>
                                        <h6 class="review-name text-dark">آقای مسلمی</h6>
                                    </div>
                                </div>
                            </div>
                            <!-- .review -->
                        </div>
                    </div>
                    <!-- .testimonial-block -->
                </div>
                <!-- .col -->
                <div class="col-lg-4">
                    <div class="card card-shadow round-xl">
                        <div class="card-inner card-inner-lg">
                            <div class="review review-s2">
                                <!-- <div class="review-portrait review-portrait-s1">
                                    <div class="portrait portrait-s1">
                                        <img src="/assets/images/client/client-b.png" alt="" />
                                    </div>
                                </div> -->
                                <div class="review-content">
                                    <div class="review-rating rating rating-sm">
                                        <ul class="rating-stars">
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                            <li><em class="icon ni ni-star-fill"></em></li>
                                        </ul>
                                    </div>
                                    <div class="review-text">
                                    <p>سلام و خسته نباشید خدمت بچه‌های پر‌تلاش تک وی پی ان، از من خواستین نظر بدم و من میگم شما کارتون حرف نداره و حلالتون باشه من کاملا راضیم مخصوصا از آقا امیر که یه مدت حکم گوگل رو برای من داشتن</p>
                                        <h6 class="review-name text-dark">آقای اصفهانی</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- .testimonial-block -->
                </div>
                <!-- .col -->
            </div>
            <!-- .row -->
        </div>
        <!-- .container -->
    </section>
    <!-- .section -->
@endsection