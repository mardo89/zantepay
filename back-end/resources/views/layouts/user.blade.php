<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Zantepay</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Exo+2:300,300i,400,400i,500,700" rel="stylesheet">

    <link rel="stylesheet" href="/css/main.css">
</head>
<body>

<!--[if lt IE 10]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<header class="header">
    <div class="masthead">
        <div class="container">
            <div class="masthead__row">
                <div class="masthead__left">
                    <a href="/" class="logo" title="Zantepay">
                        <img src="/images/logo-large.png" alt="Zantepay Logo">
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
                        <a  id="logout-btn" href="" class="btn btn--small btn--shadowed-dark">Logout</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

@yield('content')

<!-- JS scripts -->
<script src="/js/user.js" type="text/javascript"></script>
<script src="/js/components/jquery.magnific-popup.min.js"></script>
<script src="/js/components/datepicker.js"></script>


</body>

</html>