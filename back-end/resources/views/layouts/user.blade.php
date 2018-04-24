<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ZANTEPAY</title>

    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <link rel="mask-icon" href="favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
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
                    <p>This operation needs additional protection. Please provide secret key you received by email.</p>
                </div>

                <div class="logon-group">
                    <input class="logon-field" type="text" name="signature" placeholder="Secret Key">
                </div>

                <div class="logon-submit">
                    <input class="btn btn--shadowed-light btn--260" type="submit" value="Subscribe">
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

<!-- Jivosite -->
<script type='text/javascript'>
    (function () {
        var widget_id = 'v9Bhvg9GGa';
        var d = document;
        var w = window;

        function l() {
            var s = document.createElement('script');
            s.type = 'text/javascript';
            s.async = true;
            s.src = '//code.jivosite.com/script/widget/' + widget_id;
            var ss = document.getElementsByTagName('script')[0];
            ss.parentNode.insertBefore(s, ss);
        }

        if (d.readyState == 'complete') {
            l();
        } else {
            if (w.attachEvent) {
                w.attachEvent('onload', l);
            } else {
                w.addEventListener('load', l, false);
            }
        }
    })();
</script>

<!-- Sumo -->
{{--<script src="//load.sumome.com/" data-sumo-site-id="1b320c3e8fabe2b7fc8de8f8f6a818fc6abdb6eb272f1b25fe8ca580f0bbf5f4"--}}
{{--async="async"></script>--}}

<!-- Useproof -->
<script id=proof-script>!function () {
        function b() {
            var a = (new Date).getTime(), b = document.createElement('script');
            b.type = 'text/javascript', b.async = !0, b.src = 'https://cdn.getmoreproof.com/embed/latest/proof.js?' + a;
            var c = document.getElementsByTagName('script')[0];
            c.parentNode.insertBefore(b, c)
        }

        var a = window;
        a.attachEvent ? a.attachEvent('onload', b) : a.addEventListener('load', b, !1), window.proof_config = {
            acc: 'zUljMP8UW6TQiskC506GpuxFCSv2',
            v: '1.1'
        }
    }()</script>

<!-- McAfee Kibo -->
<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-110056018-1"></script>
<script> window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'UA-110056018-1');</script>

</body>

</html>