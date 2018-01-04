<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ZANTEPAY</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Exo+2:300,300i,400,400i,500,700" rel="stylesheet">

    <link rel="stylesheet" href="/css/main.css">
</head>
<body>

<!--[if lt IE 10]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a>
    to improve your experience.</p>
<![endif]-->

<header class="header header-transparent">
    <div class="masthead">
        <div class="container">
            <div class="masthead__row">
                <div class="masthead__left">
                    <a href="/" class="logo" title="ZANTEPAY">
                        <img src="/images/logo-large-wsh.png" alt="ZANTEPAY Logo">
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>


<main class="main main-inv">
    <div class="container">
        <div class="invitation-wrap">
            <div class="row vertical-middle-col">
                <div class="col-md-6">
                    <img src="/images/iPhone-debit.png" srcset="/images/iPhone-debit@2x.png 2x" alt="iPhone Debit card">
                </div>
                <div class="col-md-6">
                    @yield('main')
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-center">
        <div class="footer-nav">
            <ul>
                <li><a href="">Referral Terms & Conditions</a></li>
                <li><a href="">Privacy Terms</a></li>
                <li><a href="{{ asset('storage/Zantepay_Whitepaper.pdf') }}" target="_blank">Whitepaper</a></li>
            </ul>
        </div>
    </footer>

</main>


@yield('popups')

<!-- JS scripts -->
<script src="/js/main.js" type="text/javascript"></script>
<script src="/js/components/jquery.magnific-popup.min.js"></script>

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

</body>
</html>