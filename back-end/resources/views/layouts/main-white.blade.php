<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Spend Bitcoin, Litecoin, Ethereum and Zantecoin in real life with just one card!">

    <title>ZANTEPAY - Bringing cryptocurrency to the mainstream</title>

    <!-- Social Networks  Open Graph -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="ZANTEPAY" />
    <meta name="twitter:description" content="ZANTEPAY multi wallet and debit card - bringing crypto currencies into the mainstream!" />
    <meta name="twitter:image" content="{{ asset('images/fb_share.jpg') }}" />

    <meta property="og:url" content="{{ asset('/') }}"/>
    <meta property="og:title" content="ZANTEPAY"/>
    <meta property="og:description" content="ZANTEPAY multi wallet and debit card - bringing crypto currencies into the mainstream!"/>
    <meta property="og:image" content="{{ asset('images/fb_share.jpg') }}"/>
    <!-- End Open Graph -->

    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#5bbad5">
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
        @yield('main')
    </div>

    <footer class="footer-center">
        <div class="footer-nav">
            <ul>
                <li><a href="{{ asset('storage/Zantepay_Terms_and_Conditions.pdf') }}" target="_blank">Terms & Conditions</a></li>
                <li><a href="{{ asset('storage/Zantepay_Privacy_Policy.pdf') }}" target="_blank">Privacy Terms</a></li>
                <li><a href="{{ asset('storage/Zantepay_Whitepaper.pdf') }}" target="_blank">Whitepaper</a></li>
            </ul>
        </div>
    </footer>

</main>


@yield('popups')

<!-- JS scripts -->
<script src="/js/main.js" type="text/javascript"></script>
<script src="/js/components/jquery.magnific-popup.min.js"></script>

<!-- Additional scripts -->
@yield('scripts')


</body>
</html>