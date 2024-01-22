<!-- main header @s -->
<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ms-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="html/index.html" class="logo-link">
                    <img class="logo-light logo-img" src="./images/logo.png" srcset="./images/logo2x.png 2x" alt="لوگو" />
                    <img class="logo-dark logo-img" src="./images/logo-dark.png" srcset="./images/logo-dark2x.png 2x" alt="لوگوی تاریک" />
                </a>
            </div>
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown notification-dropdown">
                        <?php
                        $notifications = \App\Models\Mailbox::where('user_id', auth()->user()?->id)->orderBy('id', 'desc')->take(3)->get();
                        $unreadNotifications = \App\Models\Mailbox::where(['user_id' => auth()->user()?->id, 'is_read' => 0])->count();
                        ?>
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-bs-toggle="dropdown">
                            <div class="icon-status @if($unreadNotifications > 0) icon-status-info @else icon-status-na @endif" id="bell-icon">
                                <em class="icon ni ni-bell"></em>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-end">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">اطلاع رسانی ها</span>
                                <a href="#" onclick="markAllAsRead()">علامت گذاری همه به عنوان خوانده شده</a>
                            </div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                    @foreach ($notifications as $notification)
                                    <div class="nk-notification-item dropdown-inner @if ($notification->is_read==0) not-read @endif">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">{{$notification->subject}}</div>
                                            <div class="nk-notification-time">{{$notification->description}}</div>
                                            <div class="nk-notification-time">{{jdate('Y/m/d ساعت H:i', $notification->created_at->timestamp)}}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <!-- .nk-notification -->
                            </div>
                            <!-- .nk-dropdown-body -->
                            <div class="dropdown-foot center">
                                <a href="{{route('customer.notifications')}}">مشاهده همه</a>
                            </div>
                        </div>
                    </li>
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
                                        <a href="{{route('customer.orders')}}">
                                            <em class="icon ni ni-wallet"></em>
                                            <span>موجودی کیف پول &nbsp; {{auth()->user()?->wallet ?? 0}} &nbsp; تومان</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('customer.profile')}}"><em class="icon ni ni-user-alt"></em><span>مشاهده پروفایل</span></a>
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
                </ul>
            </div>
        </div>
        <!-- .nk-header-wrap -->
    </div>
    <!-- .container-fliud -->
</div>
<!-- main header @e -->
