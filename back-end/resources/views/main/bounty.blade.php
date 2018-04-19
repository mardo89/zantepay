@extends('layouts.main')

<div id="particles-js"></div>

@section('header')

    <header class="header header-lp">

        @parent

        <div class="header__content header__lp white-content">
            <div class="container">
                <h1 class="h2 header__title text-uppercase mb-5">Bounty campaign</h1>
                <p>We’re excited to offer the opportunity for you to partner with us. Contribute to our network and earn great rewards. This is why we’ve launched our ZANTEPAY Bounty Program, where you can earn ZNX nearly every day! Participation in our Bounty Programs is easy, registration is simple, and you're simply a task away from earning big.</p>
            </div>
        </div>

    </header>

@endsection

@section('main')

    <main class="main main-lp">
        <div class="lp-section-ref white-content">
            <div class="container">
                <h2 class="h2 text-center text-uppercase">Bounty token distribution</h2>
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center">
                        <div class="lp-ref-chart">
                            <img src="images/circle-chart.png" srcset="images/circle-chart@2x.png 2x" alt="Bounty token distribution">
                            <h3>
                                <strong>6.000.000</strong>
                                ZNX tokens
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <ul class="bounty-list">
                            <li>
                                <i class="bounty-percent" style="background: #f92112;">30%</i>
                                <p>Twitter Campaign &nbsp; <span>1.800.000 ZNX</span></p>
                            </li>
                            <li>
                                <i class="bounty-percent" style="background: #99dc59;">30%</i>
                                <p>Facebook Campaign &nbsp; <span>1.800.000 ZNX</span></p>
                            </li>
                            <li>
                                <i class="bounty-percent" style="background: #93519b;">14%</i>
                                <p>YouTube Campaign &nbsp; <span>840.000 ZNX</span></p>
                            </li>
                            <li>
                                <i class="bounty-percent" style="background: #ff3a2c;">10%</i>
                                <p>Blogs, Article Campaign &nbsp; <span>600.000 ZNX</span></p>
                            </li>
                            <li>
                                <i class="bounty-percent" style="background: #3c76f8;">10%</i>
                                <p>Support Campaign &nbsp; <span>600.000 ZNX</span></p>
                            </li>
                            <li>
                                <i class="bounty-percent" style="background: #ff902c;">5%</i>
                                <p>Telegram Campaign &nbsp; <span>300.000 ZNX</span></p>
                            </li>
                            <li>
                                <i class="bounty-percent" style="background: #83cfd2;">1%</i>
                                <p>Other &nbsp; <span>60.000 ZNX</span></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="lp-section-dist text-center white-content">
            <div class="container">
                <div class="lp-panels-group">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6">
                            <div class="lp-panel">
                                <h3 class="h4"><a href="/twitter-campaign">Twitter Campaign</a></h3>
                                <a href="/twitter-campaign">
                                    <img src="images/twitter-lg-icon.png" srcset="images/twitter-lg-icon@2x.png 2x" alt="Twitter Campaign">
                                </a>
                                <a class="lp-panel__link" href="/twitter-campaign">More info</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="lp-panel">
                                <h3 class="h4"><a href="/facebook-campaign">Facebook Campaign</a></h3>
                                <a href="/facebook-campaign">
                                    <img src="images/facebook-lg-icon.png" srcset="images/facebook-lg-icon@2x.png 2x" alt="Facebook Campaign">
                                </a>
                                <a class="lp-panel__link" href="/facebook-campaign">More info</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="lp-panel">
                                <h3 class="h4"><a href="/youtube-campaign">YouTube Campaign</a></h3>
                                <a href="/youtube-campaign">
                                    <img src="images/youtube-lg-icon.png" srcset="images/youtube-lg-icon@2x.png 2x" alt="YouTube Campaign">
                                </a>
                                <a class="lp-panel__link" href="/youtube-campaign">More info</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="lp-panel">
                                <h3 class="h4"><a href="/blogs-article-campaign">Blogs, Article Campaign</a></h3>
                                <a href="/blogs-article-campaign">
                                    <img src="images/doc-icon.png" srcset="images/doc-icon@2x.png 2x" alt="Blogs, Article Campaign">
                                </a>
                                <a class="lp-panel__link" href="/blogs-article-campaign">More info</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="lp-panel">
                                <h3 class="h4"><a href="/support-campaign">Support Campaign</a></h3>
                                <a href="/support-campaign">
                                    <img src="images/group-icon.png" srcset="images/group-icon@2x.png 2x" alt="Support Campaign">
                                </a>
                                <a class="lp-panel__link" href="/support-campaign">More info</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="lp-panel">
                                <h3 class="h4"><a href="/telegram-campaign">Telegram Campaign</a></h3>
                                <a href="/telegram-campaign">
                                    <img src="images/telegram-lg-icon.png" srcset="images/telegram-lg-icon@2x.png 2x" alt="Telegram Campaign">
                                </a>
                                <a class="lp-panel__link" href="/telegram-campaign">More info</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('footer')

    <footer class="footer white-content" id="contacts">
        <div class="container">
            <div class="footer-chanels" id="channels">
                <div class="text-center">
                    <h3 class="h2 headline text-left">Our Channels</h3>
                </div>
                <div class="row">
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="https://www.facebook.com/ZANTEPAY/">Facebook</a></h4>
                        <a target="_blank" href="https://www.facebook.com/ZANTEPAY/"><i class="fa fa-facebook"></i></a>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="https://twitter.com/zantepay">Twitter</a></h4>
                        <a target="_blank" href="https://twitter.com/zantepay"><i class="fa fa-twitter"></i></a>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="http://telegram.me/zantepay">Telegram</a></h4>
                        <a target="_blank" href="http://telegram.me/zantepay"><i class="fa fa-telegram"></i></a>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="https://www.instagram.com/zantepay">Instagram</a></h4>
                        <a target="_blank" href="https://www.instagram.com/zantepay"><i class="fa fa-instagram"></i></a>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4><a href="mailto:support@zantepay.com">Support</a></h4>
                        <a href="mailto:support@zantepay.com"><i class="fa fa-envelope"></i></a>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="https://www.reddit.com/user/ZANTEPAY">Reddit</a></h4>
                        <a target="_blank" href="https://www.reddit.com/user/ZANTEPAY"><i class="fa fa-reddit"></i></a>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="https://bitcointalk.org/index.php?topic=3338226">Bitcointalk</a></h4>
                        <a target="_blank" href="https://bitcointalk.org/index.php?topic=3338226"><i class="fa fa-bitcoin"></i></a></li>
                    </div>
                </div>
            </div>
            <div class="footer-logo">
                <a href="/" title="ZANTEPAY">
                    <img src="images/logo-large.png" alt="ZANTEPAY Logo">
                </a>
            </div>
            <div class="footer-menu">
                <ul>
                    <li><a href="{{ asset('storage/Zantepay_Terms_and_Conditions.pdf') }}">Terms & Conditions</a></li>
                    <li><a href="{{ asset('storage/Zantepay_Privacy_Policy.pdf') }}">Privacy Policy</a></li>
                </ul>
            </div>
            <p class="copyright"><span class="copyright-ico"></span> 2018 ZANTEPAY</p>
        </div>
    </footer>

@endsection

@section('popups')

    @parent

@endsection