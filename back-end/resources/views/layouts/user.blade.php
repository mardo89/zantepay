<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ZANTEPAY</title>

    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link href="https://fonts.googleapis.com/css?family=Exo+2:300,300i,400,400i,500,700" rel="stylesheet">

    <link rel="stylesheet" href="/css/main.css">
</head>
<body>

<!--[if lt IE 10]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a>
    to improve your experience.</p>
<![endif]-->

<header class="header">
    <div class="masthead">
        <div class="container">
            <div class="masthead__row">
                <div class="masthead__left">
                    <a href="/" class="logo" title="ZANTEPAY">
                        <img src="/images/logo-large.png" alt="ZANTEPAY Logo">
                    </a>
                </div>

                <div class="hamburger hamburger--slider">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>

                <div class="masthead__menu">
                    <nav class="navigation">
                        <ul>
                            @yield('main-menu')
                        </ul>
                    </nav>

                    <div class="masthead__right">
                        @if(\Illuminate\Support\Facades\Auth::user()->role === \App\Models\DB\User::USER_ROLE_ADMIN || \Illuminate\Support\Facades\Auth::user()->role === \App\Models\DB\User::USER_ROLE_MANAGER)
                            <div class="logon-btns">
                                <a href="/admin/users" class="btn btn--small btn--shadowed-dark">Manage</a>
                                <a id="logout-btn" href="" class="btn btn--small btn--shadowed-dark">Logout</a>
                            </div>
                        @else
                            <a id="logout-btn" href="" class="btn btn--small btn--shadowed-dark">Logout</a>
                        @endauth
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

@yield('content')

@yield('popups')

<!-- Error saving profile confirmation -->
<div class="logon-modal mfp-hide" id="error-modal">
    <div class="logon-modal-container">
        <h3 class="h4 primary-color">ERROR!</h3>
        <div class="logon-modal-text">
            <p id="error-message"></p>
        </div>
    </div>
</div>


<!-- Confirmation modal -->
<div class="logon-modal logon-modal--560 mfp-hide" id="confirmation-modal">
    <div class="logon-modal-container">
        <h3 class="h4" id="confirmation-message"></h3>
        <div class="row justify-content-center">
            <div class="col-sm-4 col-6">
                <a href="#" class="btn btn--shadowed-light btn--260" id="accept_action">Yes</a>
            </div>
            <div class="col-sm-4 col-6">
                <a href="#" class="btn btn--shadowed-light btn--260" id="reject_action">No</a>
            </div>
        </div>
    </div>
</div>


<!-- Protection modal -->
<div class="logon-modal logon-modal--560 mfp-hide" id="protection-modal">
    <div class="logon-modal-container">
        <h3 class="h4" id="confirmation-message"></h3>
        <div class="row justify-content-center">

            <form id="frm_protection">

                <div class="logon-modal-text">
                    <p>Please use the secret token from your email to confirm this operation.</p>
                </div>

                <div class="logon-group">
                    <input class="logon-field" type="text" name="signature" placeholder="Secret Key">
                </div>

                <div class="logon-submit">
                    <input class="btn btn--shadowed-light btn--260" type="submit" value="Confirm">
                </div>

            </form>

        </div>
    </div>
</div>


<!-- JS scripts -->
<script src="/js/user.js" type="text/javascript"></script>
<script src="/js/components/particles.min.js"></script>
<script src="/js/components/jquery.magnific-popup.min.js"></script>
<script src="/js/components/datepicker.js"></script>
<script src="/js/components/jquery.countdown.min.js"></script>

@yield('scripts')

</body>

</html>