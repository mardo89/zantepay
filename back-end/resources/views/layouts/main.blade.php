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
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="ZANTEPAY"/>
    <meta name="twitter:description"
          content="ZANTEPAY multi wallet and debit card - bringing crypto currencies into the mainstream!"/>
    <meta name="twitter:image" content="{{ asset('images/fb_share.jpg') }}"/>

    <meta property="og:url" content="{{ asset('/') }}"/>
    <meta property="og:title" content="ZANTEPAY"/>
    <meta property="og:description"
          content="ZANTEPAY multi wallet and debit card - bringing crypto currencies into the mainstream!"/>
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
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a>
    to improve your experience.</p>
<![endif]-->

@section('header')

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
                            <li class="m-dropdown">
                                <a href="javascript:void();">Whitepaper</a>
                                <ul>
                                    <li class="m-dropdown">
                                        <a href="{{ asset('storage/Zantepay_Whitepaper.pdf') }}"
                                           onclick="ga('send',  'event',  'button', 'onclick', 'whitepaper');">English
                                        </a>
                                    </li>
                                    <li class="m-dropdown">
                                        <a href="{{ asset('storage/Zantepay_Whitepaper_Russian.pdf') }}"
                                           onclick="ga('send',  'event',  'button', 'onclick', 'whitepaper russian');">Russian
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{$menuPrefix}}#ico">ICO</a>
                            </li>

                            <li>
                                <a href="/bounty">Bounty</a>
                            </li>

                            <li>
                                <a href="{{$menuPrefix}}#team">Team</a>
                            </li>
                            <li>
                                <a href="{{$menuPrefix}}#updates">Updates</a>
                            </li>
                            <li>
                                <a href="{{$menuPrefix}}#channels">Channels</a>
                            </li>
                            <li>
                                <a href="/faq">FAQ</a>
                            </li>
                            <li class="m-dropdown">
                                <a href="javascript:void();">Development</a>
                                <ul>
                                    <!-- <li><a href="">Wallet Beta</a></li> -->
                                    <li><a href="/mobile-app">ZANTEPAY Wallet alfa</a></li>
                                    <!-- <li><a href="">Development roadmap</a></li> -->
                                </ul>
                            </li>
                            <li><a href="{{$menuPrefix}}#contacts">Contacts</a></li>
                        </ul>
                    </nav>

                    <div class="masthead__right">
                        @guest
                            <div class="logon-btns">
                                <a href="#sign-in-modal" class="js-popup-link btn btn--small btn--shadowed-dark">Log
                                    In</a>
                                <a href="#sign-up-modal" class="js-popup-link btn btn--small btn--shadowed-dark">Sign
                                    Up</a>
                            </div>
                        @endguest

                        @auth
                            <a href="user/wallet" class="btn btn--small btn--shadowed-dark">Profile</a>
                        @endauth
                    </div>

                </div>
            </div>

        </div>
    </div>

@show

@yield('main')

@yield('footer')

@section('popups')

    <!-- sign in -->
    <div class="logon-modal mfp-hide" id="sign-in-modal">
        <div class="logon-modal-container">
            <h3 class="h4">SIGN IN</h3>
            <div class="social-btns">
                <a href="/account/fb" class="btn btn--facebook"><i></i> Sign In With Facebook</a>
                <a href="/account/google" class="btn btn--google"><i></i> Sign In With Google</a>
            </div>

            <div class="or-horizontal">or</div>
            <form id="frm_signin">
                <div class="logon-group">
                    <input id="signin_email" class="logon-field" type="email" name="email" placeholder="Email">
                </div>

                <div class="logon-group">
                    <input id="signin_pwd" class="logon-field" name="password" placeholder="Password" type="password">
                </div>

                <div class="logon-group">
                    <div id="sign-in-recaptcha" class="form-recaptcha"></div>
                    <input name="captcha" type="hidden">
                </div>

                <div class="logon-submit">
                    <input class="btn btn--shadowed-light btn--260" type="submit" value="Sign In">
                </div>

                <a href="#sign-up-modal" class="js-popup-link logon-link">Sign Up</a>
                <br>
                <a href="#forgot-password" class="js-popup-link logon-link mt-10">Forgot password ?</a>
            </form>

        </div>
    </div>

    <!-- forgot password -->
    <div class="logon-modal mfp-hide" id="forgot-password">
        <div class="logon-modal-container">
            <h3 class="h4">Forgot Password?</h3><br>
            <form id="frm_forgot_password">

                <div class="logon-group">
                    <input class="logon-field" type="email" name="email" placeholder="Email">
                </div>

                <div class="logon-group">
                    <div id="reset-password-recaptcha" class="form-recaptcha"></div>
                    <input name="captcha" type="hidden">
                </div>

                <div class="logon-submit mt-35">
                    <input class="btn btn--shadowed-light btn--260" type="submit" value="Reset Password">
                </div>

                <a href="#sign-in-modal" class="js-popup-link logon-link">Sign In</a>

            </form>
        </div>
    </div>

    <!-- reset confirmation -->
    <div class="logon-modal mfp-hide" id="reset-confirm-modal">
        <div class="logon-modal-container">
            <h3 class="h4">RIGHT ON!</h3>
            <div class="logon-modal-text">
                <p>By now you should have received an email from us. To reset your account password please click the
                    link in the email.</p>
            </div>
        </div>
    </div>

    <!-- sing up -->
    <div class="logon-modal mfp-hide" id="sign-up-modal">
        <div class="logon-modal-container">
            <h3 class="h4">SIGN UP</h3>
            <div class="social-btns">
                <a href="/account/fb" class="btn btn--facebook"><i></i> Sign In With Facebook</a>
                <a href="/account/google" class="btn btn--google"><i></i> Sign In With Google</a>
            </div>

            <div class="or-horizontal">or</div>
            <form id="frm_signup">
                <div class="logon-group">
                    <input class="logon-field" type="email" name="email" placeholder="Email">
                </div>

                <div class="logon-group">
                    <input class="logon-field" name="password" placeholder="Password" type="password">
                </div>

                <div class="logon-group">
                    <input class="logon-field" name="password_confirmation" placeholder="Confirm Password"
                           type="password">
                </div>

                <div class="logon-group">
                    <div id="sign-up-recaptcha" class="form-recaptcha"></div>
                    <input name="captcha" type="hidden">
                </div>

                <div class="logon-submit">
                    <input class="btn btn--shadowed-light btn--260" type="submit" value="Sign Up">
                </div>

                <a href="#sign-in-modal" class="js-popup-link logon-link">Sign In</a>
            </form>

        </div>
    </div>

    <!-- sign up confirmation -->
    <div class="logon-modal mfp-hide" id="confirm-modal">
        <div class="logon-modal-container">
            <h3 class="h4">RIGHT ON!</h3>
            <div class="logon-modal-text">
                <p>Thank you for registering with ZANTEPAY. By now you should have received a confirmation email from
                    us. To activate your
                    account please click the link in the email.</p>
            </div>

            <a href="" id="resend-registration-email" class="btn btn--shadowed-light btn--260">Resend Email</a>
        </div>
    </div>

    <!-- join our newsletter -->
    <div class="logon-modal logon-modal--400 mfp-hide" id="newsletter-modal">
        <div class="logon-modal-container">
            <h3 class="h4 text-uppercase">Join our newsletter</h3>
            <div class="logon-modal-text">
                <p>Join ZANTEPAY newsletter and get a chance to win 300 EUR worth of ZNX every week!</p>
                <p class="primary-color" style="font-size: 60px;line-height: 1;margin: -25px 0 30px;"><b>€ 300</b></p>
            </div>
            <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://app.mailjet.com/widget/iframe/2gIc/77A" width="100%" height="auto"></iframe>
        </div>
    </div>

@show


<!-- JS scripts -->
<script src="/js/main.js" type="text/javascript"></script>
<script src="/js/mobile_app.js"></script>
<script src="/js/components/particles.min.js"></script>
<script src="/js/components/jquery.magnific-popup.min.js"></script>
<script src="/js/components/circle-progress.min.js"></script>
<script src="/js/components/jquery.countdown.min.js"></script>

<!-- Additional scripts -->
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
<script src='https://cdn.useproof.com/proof.js?acc=zUljMP8UW6TQiskC506GpuxFCSv2' async></script>

<!-- McAfee Kibo -->
<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>

<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-110056018-1"></script>
<script> window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());
    gtag('config', 'UA-110056018-1');
</script>


</body>
</html>