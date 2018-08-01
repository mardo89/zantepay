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