<!DOCTYPE html>
<html lang="fa" class="js">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- Fav Icon  -->
        <link rel="shortcut icon" href="/assets/img/favicon.png" />
        <!-- Page Title  -->
        <title>{{ config('app.name')}} | {{$pageTitle}}</title>
        <!-- StyleSheets  -->
        <link rel="stylesheet" href="/assets/css/styles.rtl.css" />
        <style>
            .external-login-provider {
                border: 1px solid lightgray; 
                border-radius: 20px; 
                display: inline-block; 
                padding: 8px;
                text-decoration: none;
                width: 200px; 
            }

            .external-login-provider > img {
                width: 24px; 
                height: 24px;
            }
        </style>
    </head>

    <body class="has-rtl nk-body bg-white npc-default pg-auth" dir="rtl">
        <div class="nk-app-root">
            <!-- main @s -->
            <div class="nk-main">
                <!-- wrap @s -->
                <div class="nk-wrap nk-wrap-nosidebar">
                    <!-- content @s -->
                    <div class="nk-content">
                        <div class="nk-split nk-split-page nk-split-md">
                            <div class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white">
                                <div class="nk-block nk-block-middle nk-auth-body">
                                    <div class="brand-logo pb-5">
                                        <a href="/" class="logo-link">
                                            <img class="logo-light logo-img logo-img-lg" src="/assets/img/logo.png" srcset="/assets/img/logo2x.png 2x" alt="لوگو" />
                                        </a>
                                    </div>
                                    <div class="nk-block-head">
                                        <div class="nk-block-head-content">
                                            <h5 class="nk-block-title">{{$pageTitleFa}}</h5>
                                        </div>
                                    </div>
                                    
                                    @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                      <ul>
                                      @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                      @endforeach
                                      </ul>
                                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif
                                    
                                    @yield('content')
                                    
                                    @if (isset($withExtrnalLoginProviders))
                                    <div class="text-center pt-4 pb-3">
                                      <h6 class="overline-title overline-title-sap"><span>یا</span></h6>
                                    </div>
                                    <div class="text-center">
                                      <a href="{{route('auth.redirect', ['provider' => 'google'])}}" class="text-center external-login-provider">
                                        <span>ورود با </span> &nbsp; <img src="/assets/img/Google_logo.png" alt="گوگل" />
                                      </a>
                                      <a href="#" class="text-center external-login-provider">
                                        <span>ورود با </span> &nbsp; <img src="/assets/img/Apple_logo.png" alt="اپل" />
                                      </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <!-- .nk-split-content -->
                            <div class="nk-split-content nk-split-stretch bg-abstract"></div>
                            <!-- .nk-split-content -->
                        </div>
                        <!-- .nk-split -->
                    </div>
                    <!-- wrap @e -->
                </div>
                <!-- content @e -->
            </div>
            <!-- main @e -->
        </div>
        <!-- app-root @e -->
        <!-- JavaScript -->
        <script src="/assets/js/bundle.js"></script>
        <script src="/assets/js/scripts.js"></script>
        
    </body>
</html>