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
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

@yield('header')

@yield('main')

@yield('footer')

@yield('popups')

<!-- JS scripts -->
<script src="/js/main.js" type="text/javascript"></script>
<script src="/js/components/jquery.magnific-popup.min.js"></script>
<script src="/js/components/circle-progress.min.js"></script>
<script src="/js/components/jquery.countdown.min.js"></script>

</body>
</html>