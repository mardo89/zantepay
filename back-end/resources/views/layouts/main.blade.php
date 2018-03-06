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
    <meta property="og:url" content="http://test.zantepay.com"/>
    <meta property="og:title" content="ZANTEPAY"/>
    <meta property="og:description" content="This is ZANTEPAY"/>
    <meta property="og:image" content="http://test.zantepay.com/images/logo-large.png"/>
    <!-- End Open Graph -->

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

@yield('header')

@yield('main')

@yield('footer')

@yield('popups')

<!-- JS scripts -->
<script src="/js/main.js" type="text/javascript"></script>
<script src="/js/components/particles.min.js"></script>
<script src="/js/components/jquery.magnific-popup.min.js"></script>
<script src="/js/components/circle-progress.min.js"></script>
<script src="/js/components/jquery.countdown.min.js"></script>

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