@extends('layouts.main')

@section('header')

    <div id="particles-js"></div>

    <header class="header header-lp">
        @parent

        <div class="h-banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-xl-7">
                        The only official URL for Zantepay is <a href="https://zantepay.com">https://zantepay.com</a>. If you receive
                        confirmation
                        of your participation in the Token Sale, the only valid email is <a href="mailto:support@zantepay.com">support@zantepay.com</a>
                    </div>
                    <div class="col-md-6 col-xl-5">
                        <div class="row">
                            <div class="col-lg">
                                Official channels:
                            </div>
                            <div class="col-lg-9">
                                <ul class="social-list">
                                    <li><a target="_blank" href="https://www.facebook.com/ZANTEPAY/"><i class="fa fa-facebook"></i></a></li>
                                    <li><a target="_blank" href="https://twitter.com/zantepay"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="mailto:support@zantepay.com"><i class="fa fa-envelope"></i></a></li>
                                    <li><a target="_blank" href="http://telegram.me/zantepay"><i class="fa fa-telegram"></i></a></li>
                                    <li><a target="_blank" href="https://www.reddit.com/user/ZANTEPAY"><i class="fa fa-reddit"></i></a></li>
                                    <li><a target="_blank" href="https://www.instagram.com/zantepay"><i class="fa fa-instagram"></i></a>
                                    </li>
                                    <li><a target="_blank" href="https://medium.com/@zantepay"><i class="fa fa-medium"></i></a>
                                    </li>
                                    <li><a target="_blank" href="https://bitcointalk.org/index.php?topic=3338226"><i class="fa fa-bitcoin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0)" class="fa fa-close js-close-banner" title="Close"></a>
            </div>
        </div>

    </header>

@endsection

@section('main')

    <main class="main main-f-height">
        <section class="white-content full-h-section">
            <div class="container">
                <div class="maintenance-group text-center">
                    <div class="spin-logo">
                        <img src="images/spin-logo.png" src="images/spin-logo.png" alt="">
                    </div>
                    <h2 class="h2 text-uppercase">404 Error</h2>
                    <p>We are really sorry but the page you requested cannot be found.<br> it seems that the page you were trying to reach doesn’t exist anymore, or maybe it has just moved. We think the best thing to do is to start again from the home page. Feel free to contact us if the problem persists or if you definitely can not find what you are looking for. Thank you very much.</p>
                    <a href="mailto:support@zantepay.com" class="btn btn--shadowed-dark btn--260">Email Us</a>
                </div>
            </div>
        </section>
    </main>

@endsection


@section('popups')

    @parent

@endsection