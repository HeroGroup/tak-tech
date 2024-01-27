<!DOCTYPE html>
<html lang="fa" class="js">
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <!-- Fav Icon  -->
        <link rel="shortcut icon" href="/assets/img/favicon.png" />
        <!-- Page Title  -->
        <title>{{config('app.name')}} | {{$title}}</title>
        <!-- StyleSheets  -->
        <link rel="stylesheet" href="/assets/css/styles.rtl.landing.css" />
        <link rel="stylesheet" href="/assets/css/styles.rtl.css" />
    </head>

    <body class="has-rtl nk-body bg-white npc-landing" dir="rtl">
        <div class="nk-app-root">
            <!-- main @s -->
            <div class="nk-main">
                <header class="header has-header-main-s1 bg-lighter">
                    <div class="header-main header-main-s1 is-sticky is-transparent">
                        <div class="container header-container">
                            <div class="header-wrap">

                                <div class="header-toggle">
                                    <button class="menu-toggler" data-target="mainNav">
                                        <em class="menu-on icon ni ni-menu"></em>
                                        <em class="menu-off icon ni ni-cross"></em>
                                    </button>
                                </div>

                                <div class="nk-header-tools">
                                    <ul class="nk-quick-nav">
                                        <li class="dropdown carts-dropdown hide-mb-xs">
                                            <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                                                <div class="icon-status icon-status-na" id="cart-icon"><em class="icon ni ni-cart"></em></div>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end">
                                                <div class="dropdown-head">
                                                    <span class="sub-title nk-dropdown-title">سب خرید</span>
                                                </div>
                                                <div class="dropdown-body">
                                                    <ul class="cart-list" id="cart-list">
                                                        <li style="padding: 10px 0; text-align: center;">سبد خرید خالی است<li>
                                                    </ul>
                                                </div>
                                                
                                                <div class="dropdown-foot center" style="padding:0.5rem;" id="cart-bottom">
                                                    <a href="#pricing">مشاهده محصولات</a>
                                                </div>
                                            </div>
                                        </li>
                                        @if (auth()->user())
                                        <li class="dropdown user-dropdown">
                                            <a href="#" class="dropdown-toggle me-n1" data-bs-toggle="dropdown">
                                                <div class="user-toggle">
                                                    <div class="user-avatar sm">
                                                        <em class="icon ni ni-user-alt"></em>
                                                    </div>
                                                    <div class="user-info d-none d-xl-block">
                                                        <div class="user-name dropdown-indicator">{{auth()->user()?->name}}</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                                                <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                                    <div class="user-card">
                                                        <div class="user-avatar">
                                                            <span>name</span>
                                                        </div>
                                                        <div class="user-info">
                                                            <span class="lead-text">{{auth()->user()?->name}}</span>
                                                            <span class="sub-text">{{auth()->user()?->email}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dropdown-inner">
                                                    <ul class="link-list">
                                                        <li>
                                                            <a href="{{route('customer.transactions')}}">
                                                                <em class="icon ni ni-wallet"></em>
                                                                <span>موجودی کیف پول &nbsp; {{number_format(auth()->user()?->wallet) ?? 0}} &nbsp; تومان</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{route('customer.dashboard')}}">
                                                                <em class="icon ni ni-dashboard"></em>
                                                                <span>داشبورد</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dark-switch" href="#"><em class="icon ni ni-moon"></em><span>حالت تاریک</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="dropdown-inner">
                                                    <ul class="link-list">
                                                        <li>
                                                            <a href="#" onclick="document.getElementById('logout-form').submit();"><em class="icon ni ni-signout"></em><span>خروج</span></a>
                                                            <form method="POST" action="{{route('auth.logout')}}" id="logout-form">
                                                                @csrf
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- <div class="header-logo">
                                    <a href="/" class="logo-link">
                                        <img class="logo-light logo-img" src="/assets/images/logo.png" srcset="/assets/images/logo2x.png 2x" alt="لوگو" />
                                        <img class="logo-dark logo-img" src="/assets/images/logo-dark.png" srcset="/assets/images/logo-dark2x.png 2x" alt="لوگوی تاریک" />
                                    </a>
                                </div> -->

                                <nav class="header-menu" data-content="mainNav">
                                    <ul class="menu-list">
                                        <li class="menu-item"><a href="#home" class="menu-link">صفحه اصلی</a></li>
                                        <li class="menu-item"><a href="#service" class="menu-link">خدمات</a></li>
                                        <li class="menu-item"><a href="#feature" class="menu-link">امکانات</a></li>
                                        <li class="menu-item"><a href="#pricing" class="menu-link">محصولات</a></li>
                                        <li class="menu-item"><a href="#reviews" class="menu-link">نظرات</a></li>
                                    </ul>
                                    @unless (auth()->user())
                                    <ul class="menu-btns">
                                        <li>
                                            <a href="{{route('auth.login')}}" class="btn btn-primary btn-lg"> ورود / ثبت نام</a>
                                        </li>
                                    </ul>
                                    @endunless
                                </nav>
                            </div>
                            <!-- .header-warp-->
                        </div>
                        <!-- .container-->
                    </div>
                    <!-- .header-main-->
                    @unless (isset($removeHeader) && $removeHeader==true)
                    <div class="header-content my-auto py-5" id="home">
                        <div class="container">
                            <div class="row flex-lg-row-reverse align-items-center justify-content-between g-gs">
                                <div class="col-lg-6 mb-n3 mb-lg-0">
                                    <div class="header-image header-image-s2">
                                        <img src="/assets/img/warp-desktop.png" alt="" />
                                    </div>
                                    <!-- .header-image -->
                                </div>
                                <!-- .col- -->
                                <div class="col-lg-5 col-md-10">
                                    <div class="header-caption">
                                        <h1 class="header-title">قابل استفاده روی تمام دستگاه ها</h1>
                                        <div class="header-text">
                                            <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است.</p>
                                        </div>
                                    </div>
                                    <!-- .header-caption -->
                                </div>
                                <!-- .col -->
                            </div>
                            <!-- .row -->
                        </div>
                        <!-- .container -->
                    </div>
                    <!-- .header-content -->
                    @endunless
                </header>
                <!-- .header -->
                
                @yield('content')
                
                <footer class="footer bg-lighter" id="footer">
                    <div class="container">
                        <div class="row g-3 align-items-center justify-content-md-between py-4 py-md-5">
                            <div class="col-md-3">
                                <!-- <div class="footer-logo">
                                    <a href="/" class="logo-link">
                                        <img class="logo-light logo-img" src="/assets/images/logo.png" srcset="/assets/images/logo2x.png 2x" alt="لوگو" />
                                        <img class="logo-dark logo-img" src="/assets/images/logo-dark.png" srcset="/assets/images/logo-dark2x.png 2x" alt="لوگوی تاریک" />
                                    </a>
                                </div> -->
                                <!-- .footer-logo -->
                            </div>
                            <!-- .col -->
                            <div class="col-md-9 d-flex justify-content-md-end">
                                <ul class="link-inline gx-4">
                                    <li><a href="#">راهنما</a></li>
                                    <li><a href="#">خدمات</a></li>
                                    <li><a href="#">تماس</a></li>
                                </ul>
                                <!-- .footer-nav -->
                            </div>
                            <!-- .col -->
                        </div>
                        <hr class="hr border-light mb-0 mt-n1" />
                        <div class="row g-3 align-items-center justify-content-md-between py-4">
                            <div class="col-md-8">
                                <div class="text-base">© تمام حقوق محفوظ است.</div>
                            </div>
                            <!-- .col -->

                        </div>
                        <!-- .row -->
                    </div>
                    <!-- .container -->
                </footer>
                <!-- .footer -->
            </div>
            <!-- main @e -->
        </div>
        
        <div class="finalize-box" id="finalize-box">
            <a href="#" data-bs-toggle="modal" data-bs-target="#cartModal">نهایی کردن خرید</a>
            <div>
                <span id="cart-sum" class="cart-sum"></span>
                &nbsp;
                <span>تومان</span>
            </div>
        </div>

        <!-- Cart Modal -->
        <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel">نهایی کردن خرید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="cart-list" id="cart-list"></ul>
                <hr />
                <div style="display: flex; justify-content: space-between;">
                    <div>مبلغ قابل پرداخت</div>
                    <div>
                        <span class="cart-sum"></span>
                        &nbsp;
                        <span>تومان</span>
                    </div>
                </div>
                <hr />

                <div style="display: flex; justify-content :space-between; align-items: center;">
                    <div>
                        <input type="text" class="form-control" name="discount-code" id="discount-code" placeholder="کد تخفیف دارید؟">
                    </div>
                    <button type="button" class="btn btn-sm btn-success" onclick="checkDiscountCode()">ثبت کد</button>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="submitOrder()">پرداخت</button>
            </div>
            </div>
        </div>
        </div>
        <!-- app-root @e -->
        <!-- JavaScript -->
        <script src="/assets/js/bundle.js"></script>
        <script src="/assets/js/scripts.js"></script>
        <script>
            var userId = "{{auth()->user()?->id}}";
            var cartFromServer = JSON.parse("{{$cart}}".replace(/&quot;/g,'"'));
            var cartFromStorage = JSON.parse(localStorage.getItem('cart'));
            var chosenCart;

            if (Object.keys(cartFromServer || {}).length > 0) {
                chosenCart = cartFromServer;
            } else if (Object.keys(cartFromStorage || {}).length > 0) {
                chosenCart = cartFromStorage;
            }
            
            var cart = {};
            if (chosenCart) {
                cart = chosenCart;
                updateCartElement();
                var cartElements = Object.keys(cart);
                cartElements.forEach(element => {
                    setPricingActionHtml(element, cart[element].title, cart[element].price, cart[element].count);
                });
            }

            function setPricingActionHtml(id, title, price, count) {
                var pricingActionElement = document.getElementById(`pricing-action-${id}`);
                if (count === 0) {
                    pricingActionElement.innerHTML = `<button class="btn btn-primary btn-lg btn-block" onclick="addToCart(${id}, '${title}', '${price}', 'increase')">
                        <span>انتخاب</span>
                    </button>`;
                } else if (count >= 1) {
                    pricingActionElement.innerHTML = `<button class="btn btn-primary btn-lg" onclick="addToCart(${id}, '${title}', '${price}', 'increase')">
                        <span>+</span>
                    </button>
                    <button id="${id}-count" class="btn" style="width:50px;">${count}</button>
                    <button class="btn btn-primary btn-lg" onclick="addToCart(${id}, '${title}', '${price}', 'decrease')">
                        <span>-</span>
                    </button>`;
                }
            }

            function updateCartElement() {
                // var cartElement = document.getElementById("cart-list");
                var cartElements = document.getElementsByClassName("cart-list");
                var cartIcon = document.getElementById("cart-icon");
                var cartBottom = document.getElementById("cart-bottom");
                var finalizeBox = document.getElementById("finalize-box");
                // var cartSum = document.getElementById("cart-sum");
                var cartSums = document.getElementsByClassName("cart-sum");
                var cartKeys = Object.keys(cart);
                var list = ``;

                if (cartKeys.length > 0) {
                    // icon-status-info
                    cartIcon.classList.remove("icon-status-na");
                    cartIcon.classList.add("icon-status-info");
                    finalizeBox.style.display = "flex";
                    var totalSum = 0;

                    cartKeys.forEach((item) => {
                        var itemSum = cart[item].price * cart[item].count
                        totalSum += itemSum;
                        list += `<li class="cart-item" style="padding:10px;display:flex;">
                            <div style="flex: 2">${cart[item].title}</div>
                            <div style="flex: 1; text-align: center;">${cart[item].count}</div>
                            <div style="flex: 2; text-align: end;">${new Intl.NumberFormat().format(itemSum)} تومان</div>
                            </li>`;
                    });

                    cartBottom.innerHTML = 
                        `<div style="width: 100%; display: flex;">
                            <a style="flex: 1;" href="#" data-bs-toggle="modal" data-bs-target="#cartModal">نهایی کردن خرید</a>
                            <div style="flex: 1; text-align: end;">${new Intl.NumberFormat().format(totalSum)} تومان</div>
                        </div>`;

                    // cartSum.innerHTML = `${new Intl.NumberFormat().format(totalSum)}`;
                    Array.prototype.forEach.call(cartSums, function(element) {
                        element.innerHTML = `${new Intl.NumberFormat().format(totalSum)}`;
                    });
                } else {
                    // icon-status-na
                    cartIcon.classList.remove("icon-status-info");
                    cartIcon.classList.add("icon-status-na");
                    finalizeBox.style.display = "none";

                    cartBottom.innerHTML = `<a href="#pricing">مشاهده محصولات</a>`;
                }

                // cartElement.innerHTML = list;
                Array.prototype.forEach.call(cartElements, function(element) {
                    element.innerHTML = list;
                });
            }

            function addToCart(productId, productTitle, productPrice, type) {
                var productCount = document.getElementById(`${productId}-count`);
                if (Object.keys(cart).includes(`${productId}`)) {
                    // available in cart
                    var currentValue = cart[productId].count;

                    if (type === "increase") {
                        var nextValue = currentValue + 1;
                        cart[productId].count = nextValue;
                    } else if (type === "decrease") {
                        var nextValue = currentValue - 1;
                        if (currentValue === 0 || nextValue === 0) {
                            delete cart[productId];
                            setPricingActionHtml(productId, productTitle, productPrice, 0);
                            productCount.innerHTML = 0;
                        } else {
                            cart[productId].count = nextValue;
                        }
                    }

                    productCount.innerHTML = nextValue;
                } else {
                    // not available in cart
                    if (type === "increase") {
                        cart[productId] = {
                            count: 1,
                            title: productTitle,
                            price: productPrice,
                        };
                        setPricingActionHtml(productId, productTitle, productPrice, 1);
                    }
                }

                // store in storage
                localStorage.setItem('cart', JSON.stringify(cart));
                
                if (userId) {
                    sendCartToServer("{{route('addToCart')}}");
                }

                updateCartElement();
            }

            function submitOrder() {
                sendCartToServer("{{route('submitOrder')}}", true);
            }

            function sendCartToServer(route, render=false) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", route, true);
                xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                const body = { 
                    '_token': "{{csrf_token()}}",
                    cart: JSON.stringify(cart),
                };

                xhr.onreadystatechange = () => {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        if (render) {
                            document.write(xhr.response);
                        }
                    }
                };
                xhr.send(JSON.stringify(body));
            }

            function checkDiscountCode() {
                //
            }
        </script>
    </body>
</html>