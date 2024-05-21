<!DOCTYPE html>
<html lang="fa" class="js">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="shortcut icon" href="/assets/img/favicon.png" />
        <title>{{config('app.name')}} | {{$pageTitle ?? 'Dashboard'}}</title>
        <link rel="stylesheet" href="/assets/css/styles.rtl.css" />
        <link rel="stylesheet" href="/assets/css/toastr.min.css" />
    </head>

    <body class="has-rtl nk-body bg-lighter npc-general has-sidebar" dir="rtl">
        <div class="nk-app-root">
            <div class="nk-main">
                @include('layouts.customer.sidebar')
                <div class="nk-wrap">
                    @include('layouts.customer.topbar', ['pageTitleFa' => $pageTitleFa])
                    <div class="nk-content">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
                                <div class="nk-content-body">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="nk-footer">
                        <div class="container-fluid">
                            <div class="nk-footer-wrap">
                                <div class="nk-footer-copyright">© تمام حقوق محفوظ است. </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/assets/js/bundle.js"></script>
        <script src="/assets/js/scripts.js"></script>
        <script src="/assets/js/logout.js"></script>
        
        <script>
            $(document).ready(function() {
                if("{{\Illuminate\Support\Facades\Session::has('message')}}" === "1") {
                    var message = "{{\Illuminate\Support\Facades\Session::get('message')}}";
                    var type = "{{\Illuminate\Support\Facades\Session::get('type')}}" || 'info';
                    
                    if (type === 'error') {
                        toastr.error(message);
                    } else if (type === 'success') {
                        toastr.success(message);
                    } else {
                        toastr.info(message);
                    }
                }
            });

            function markAllAsRead() {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "{{route('customer.notifications.markAllAsRead')}}", true);
                xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
                const body = {
                    _token: "{{csrf_token()}}",
                };

                xhr.onreadystatechange = () => {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        var responseJson = JSON.parse(xhr.response);
                        if (responseJson.status === 1) {
                            var bellIcon = document.getElementById("bell-icon");
                            bellIcon.classList.add("icon-status-na");
                            bellIcon.classList.remove("icon-status-info");

                            let notReadNotifications =
                                document.querySelectorAll(".not-read");
                            notReadNotifications.forEach((item) => {
                                item.classList.remove("not-read");
                            });
                        } else {
                            // do nothing
                        }
                    }
                };
                xhr.send(JSON.stringify(body));
            }

            async function shareData(data) {
                try {
                    await navigator.share(data);
                } catch (e) {
                    console.error(`Error: ${e}`);
                    // canNotShareData();
                }
            }

            function canBrowserShareData(data) {
                if (!navigator.share || !navigator.canShare) {
                    return false;
                }

                return navigator.canShare(data);
            }

            function invite(code) {
                const shareUrl = `/register?invite_code=${code}`;
                const sharedDataSample = {
                    title: "دعوت از دوستان",
                    text: "خرید vpn بدون قطعی و بالاترین سرعت",
                    url: shareUrl,
                };

                if (canBrowserShareData(sharedDataSample)) {
                    shareData(sharedDataSample);
                } else {
                    // canNotShareData();
                    // copy to cliboard
                    navigator.clipboard.writeText(shareUrl);
                    toastr.success('لینک دعوت کپی شد.');
                }
            }

            function canNotShareData() {
                alert('متاسفانه مرورگر شما از این قابلیت پشتیبانی نمی کند.');
            }
        </script>
    </body>
</html>