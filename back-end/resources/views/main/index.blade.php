@extends('layouts.main')

@section('header')

    <div id="particles-js"></div>

    <header class="header header-lp">
        @parent

        <div class="h-banner">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-xl-6">
                        The only official URL for Zantepay is <a href="https://zantepay.com">https://zantepay.com</a>. If you receive
                        confirmation
                        of your participation in the Token Sale, the only valid email is <a href="mailto:support@zantepay.com">support@zantepay.com</a>
                    </div>
                    <div class="col-lg-7 col-xl-6">
                        <div class="row">
                            <div class="col-lg">
                                Official channels:
                            </div>
                            <div class="col-lg-10">
                                <ul class="social-list">
                                    <li><a target="_blank" href="https://www.facebook.com/ZANTEPAY/"><i class="fa fa-facebook"></i></a></li>
                                    <li><a target="_blank" href="https://twitter.com/zantepay"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="mailto:support@zantepay.com"><i class="fa fa-envelope"></i></a></li>
                                    <li><a target="_blank" href="https://t.me/zantepay_ico"><i class="fa fa-telegram"></i></a></li>
                                    <li><a target="_blank" href="https://www.reddit.com/user/ZANTEPAY"><i class="fa fa-reddit"></i></a></li>
                                    <li><a target="_blank" href="https://www.instagram.com/zantepay"><i class="fa fa-instagram"></i></a>
                                    </li>
                                    <li><a target="_blank" href="https://medium.com/@zantepay"><i class="fa fa-medium"></i></a>
                                    </li>
                                    <li><a target="_blank" href="https://bitcointalk.org/index.php?topic=3338226"><i class="fa fa-bitcoin"></i></a></li>
                                    <li><a target="_blank" href="https://www.youtube.com/channel/UCP0ASZEKKM1DzFlhRu3FIpA"><i class="fa fa-youtube"></i></a></li>
                                    <li><a target="_blank" href="https://www.linkedin.com/company/zantepay/"><i class="fa fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0)" class="fa fa-close js-close-banner" title="Close"></a>
            </div>
        </div>

        <div class="header__content white-content">
            <div class="container">
                <div class="center-logo pos-r">
                    <img src="images/logo-large.png" alt="ZANTEPAY Logo">
                </div>
                <h1 class="h2 header__title text-uppercase">Spend BTC, LTC, ETH and Zantecoin in real life <br> with just one card</h1>
                <div class="row p-t-60">
                    <div class="col-lg-4 text-center">
                        <img src="images/zantepay-card-mobile.png" srcset="images/zantepay-card-mobile@2x.png 2x" alt="Zantepay App">
                    </div>
                    <div class="col-lg-8 p-t-40">
                        <div class="row mb-10">
                            <div class="col-lg-6">
                                <h3 class="h2 text-uppercase mb-30"> 1 ETH = {{ $ico['znxRate'] }} ZNX</h3>
                            </div>
                            <div class="col-lg-6">
                                @guest
                                    <a href="#sign-up-modal" class="js-popup-link btn btn--shadowed-dark btn--200">Buy Tokens NOW</a>
                                @endguest
                                @auth
                                    <a href="user/wallet" class="btn btn--shadowed-dark btn--200">Buy Tokens NOW</a>
                                @endauth
                            </div>
                        </div>
                        <div class="countdown">
                            <h3 class="h4 text-uppercase">{{ $ico['name'] }} ends in</h3>
                            <span class="js-countdown" data-date="{{ $ico['endDate'] }}"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($ico['showProgress'])

            <div class="white-content container">

                <div class="ico-progress">
                    <div class="ico-progress-left">
                        <span class="h4">ZNX</span>
                        <span class="text-lg text-lg-center">0</span>
                        <span class="text-lg">ETH</span>
                    </div>
                    <div class="ico-progress-bar">
                        <!-- please use "is-right" class if <50% and "is-left" if >50% -->
                        <div class="ico-progress-bar-group {{ $ico['relativeBalance']['progressClass'] }}"
                             style="width:{{ $ico['relativeBalance']['percent'] }}%;">
                            <span class="h4">{{ $ico['znxAmount'] }}</span>
                            <span class="text-lg">{{ $ico['ethAmount'] }}</span>
                            <div class="ico-progress-bar-line"></div>
                            <img src="images/coin-ico.png" srcset="images/coin-ico@2x.png 2x" alt="ZANTECOIN">
                        </div>
                    </div>
                    <div class="ico-progress-right">
                        <span class="h4">{{ $ico['znxLimit'] }}</span>
                        <span class="text-lg">{{ $ico['ethLimit'] }}</span>
                    </div>
                </div>

                <!-- <h3 class="h4 text-center">
                    Sold total during private sale and pre-ICO:
                    <p> {{ $ico['prevAmount'] }} ZNX</p>
                </h3> -->

            </div>

        @endif

        <div class="hp-video-section container text-center white-content">
            <h2 class="h2 headline">What is <br> ZANTEPAY</h2>
            <div>
                <a href="https://www.youtube.com/watch?v=7fuQrhaqXV8" class="js-popup-video">
                    <i class="fa fa-youtube-play"></i>
                    <img src="images/zantecoin-thumb.jpg" srcset="images/zantecoin-thumb@2x.jpg 2x" alt="What is ZANTECOIN?">
                </a>
            </div>
        </div>

    </header>

@endsection

@section('main')

    <main class="main main-lp">
        <section class="lp-section-one white-content" id="about-us">
            <div class="container">
                <div class="text-center">
                    <h3 class="h2 headline text-left">ZANTECOIN - The Most <br> Valuable Coin </h3>
                </div>
                <div class="row vertical-middle-col mt-40 p-t-30">
                    <div class="text-center col-lg-3 col-sm-4 offset-lg-1">
                        <img src="images/zantecoin2.png" srcset="images/zantecoin2@2x.png 2x" alt="Zantecoin">
                    </div>
                    <div class="col-lg-7 col-sm-8 offset-lg-1">
                        <ul class="styl-list">
                            <li>New mainstream currency</li>
                            <li>BTC, ETH, LTC free conversion to ZNX</li>
                            <!-- <li>Mastercard - accepted everywhere</li> -->
                            <li>Can be used to cover service fees in ZANTEPAY ecosystem</li>
                            <li>50% discount on all services in ZANTEPAY ecosystem when using ZANTECOIN</li>
                            <li>ERC 20 token</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <div class="lp-section-feed white-content" id="updates">
            <div class="container">

                <div class="text-center">
                    <h2 class="h2 headline">ZANTEPAY <br>updates</h2>
                </div>

                <div class="lp-feed">
                    <div class="row">
                        <div class="col-lg-6">
                            @foreach($feed['leftColumnRss'] as $feedItem)

                                @if($feedItem['link'])
                                    <a href="{{ $feedItem['link'] }}" target="_blank" class="lp-feed__col">
                                        <div class="lp-feed__text">
                                            <time datetime="{{ $feedItem['datetime'] }}">{{ $feedItem['published'] }}</time>
                                            <p>{{ $feedItem['title'] }}</p>
                                        </div>
                                    </a>
                                @else
                                    <div class="lp-feed__col">
                                        <div class="lp-feed__text">
                                            <time datetime="{{ $feedItem['datetime'] }}">{{ $feedItem['published'] }}</time>
                                            <p>{{ $feedItem['title'] }}</p>
                                        </div>
                                    </div>
                                @endif

                            @endforeach
                        </div>

                        <div class="col-lg-6">
                            @foreach($feed['rightColumnRss'] as $feedItem)

                                @if($feedItem['link'])
                                    <a href="{{ $feedItem['link'] }}" target="_blank" class="lp-feed__col">
                                        <div class="lp-feed__text">
                                            <time datetime="{{ $feedItem['datetime'] }}">{{ $feedItem['published'] }}</time>
                                            <p>{{ $feedItem['title'] }}</p>
                                        </div>
                                    </a>
                                @else
                                    <div class="lp-feed__col">
                                        <div class="lp-feed__text">
                                            <time datetime="{{ $feedItem['datetime'] }}">{{ $feedItem['published'] }}</time>
                                            <p>{{ $feedItem['title'] }}</p>
                                        </div>
                                    </div>
                                @endif

                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="lp-section-two white-content">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-7 offset-lg-1 lp-pre-ico-col">
                        <h2 class="h2 headline">{{ $ico['name'] }} <span>1 ZNX = {{ $ico['euroRate'] }}
                                €</span><span>1 ZNX = {{ $ico['ethRate'] }} ETH</span></h2>
                    </div>
                    <div class="col-md-5 text-center">
                        <div class="lp-progress-wrap">
                            <div class="js-lp-progress-blured lp-progress-blured"></div>
                            <div class="js-lp-progress lp-progress-top" data-percent="{{ $ico['relativeBalance']['value'] }}"></div>
                            <div class="lp-progress-text"> {{ $ico['relativeBalance']['percent'] }}% <span>distributed</span>
                            </div>
                        </div>
                        <p class="h4">{{ $ico['znxAmount'] }} ZNX distributed</p>
                    </div>
                </div>

                <div class="lp-section-two-btn">
                    <!-- <a href="#sign-up-preico" class="js-popup-link btn btn--shadowed-dark btn--260" onclick=" ga('send',  'event',  'button', 'onclick', 'register_for_pre_ico');">Register For Pre-ICO</a> -->
                    @guest
                        <a href="#sign-up-modal" class="js-popup-link btn btn--shadowed-dark btn--260">Buy Tokens NOW</a>
                    @endguest

                    @auth
                        <a href="user/wallet" class="btn btn--shadowed-dark btn--260">Buy Tokens NOW</a>
                    @endauth
                </div>
            </div>
        </div>

        <section class="lp-section-three" id="roadmap">
            <div class="container">
                <div class="row p-b-60">
                    <div class="offset-md-2 col-md-3 vertical-middle-col"><span class="h2 primary-color text-uppercase">Roadmap</span></div>
                    <div class="col-md-6">
                        <h2 class="headline h2">More than 1 million people to use ZANTEPAY debit card by 2020</h2>
                    </div>
                </div>

                <div class="hp-roadmap">
                    <div class="row p-tb-40">
                        <div class="col-md-6 content-center">
                            <div class="lp-image-container">
                                <img src="images/rocket-picture.jpg" srcset="images/rocket-picture@2x.jpg 2x" alt="Rocket Image">
                            </div>
                        </div>
                        <div class="col-md-6 vertical-middle-col content-center">
                            <div class="lp-content-col">
                                <h3 class="h2 headline">Beginning of the ZANTECOIN pre-sale <span>March 15<sup>th</sup></span></h3>
                                <p>Token distribution Debit Card pre-ordering start.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row row-revert p-tb-40">
                        <div class="col-md-6 content-center">
                            <div class="lp-image-container3">
                                <img src="images/zantecoin.png" srcset="images/zantecoin@2x.png 2x" alt="Rocket Image">
                            </div>
                        </div>
                        <div class="col-md-6 vertical-middle-col content-center">
                            <div class="lp-content-col">
                                <h3 class="h2 headline">Pre-ICO - ICO III Part <span>March 15<sup>th</sup> - July 15<sup>th</sup></span>
                                </h3>
                            </div>
                        </div>
                    </div>

                    <div class="row p-tb-40">
                        <div class="col-md-6 content-center">
                            <div class="lp-image-container">
                                <img src="images/iPhone.jpg" srcset="images/iPhone@2x.jpg 2x" alt="ZANTEPAY wallet">
                            </div>
                        </div>
                        <div class="col-md-6 vertical-middle-col content-center">
                            <div class="lp-content-col">
                                <h3 class="h2 headline">Launch of ZANTEPAY wallet <span>2018 Q3</span></h3>
                                <p>Debit card launch. Bitcoin, Litecoin, Ethereum and Zantecoin integration with Debit card and Wallet.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row row-revert p-tb-40">
                        <div class="col-md-6 content-center">
                            <div class="lp-image-container2">
                                <img src="images/card.png" srcset="images/card@2x.png 2x" class="lp-visa-card" width="480px"
                                     alt="ZANTEPAY Debit Card">
                            </div>
                        </div>
                        <div class="col-md-6 p-t-40 vertical-top-col pt-mob-0 content-center">
                            <div class="lp-content-col">
                                <h3 class="h2 headline">Debit card launch <span>2018 Q4</span></h3>
                                <p>Debit card launch. Bitcoin, Litecoin, Ethereum and Zantecoin integration with Debit card and Wallet.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row lp-row-1">
                        <div class="col-md-6 content-center">
                            <div class="lp-image-container">
                                <img src="images/rocket-image.jpg" srcset="images/rocket-image@2x.jpg 2x" alt="Rocket Image">
                            </div>
                        </div>
                        <div class="col-md-6 vertical-middle-col content-center">
                            <div class="lp-content-col">
                                <h3 class="h2 headline">Biggest cryptocurrency wallet worldwide! <span>2019</span></h3>
                                <p>Become the biggest cryptocurrency wallet worldwide! Join today!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="lp-section-reviews">
            <div class="container">
                <div class="text-center">
                    <h2 class="h2 headline text-left">Expert reviews</h2>
                </div>
                <div class="row p-t-60 mt-20 lp-rate text-center">
                    <div class="col-lg col-md-4 mb-20">
                        <a href="https://www.trackico.io/ico/zantepay/" target="_blank" rel="nofollow" title="ZANTEPAY on TrackICO">
                            <img border="0" src="https://www.trackico.io/widget/square/zantepay/400.png" width="150px" height="150px" alt="ZANTEPAY TrackICO rating">
                        </a>
                    </div>
                    <div class="col-lg col-md-4 mb-20">
                        <div>
                            <img src="images/icobench.png" width="200" alt="ICObench Logo">
                        </div>
                        <a href="https://icobench.com/ico/zantepay" target="_blank" rel="nofollow" title="Zantepay on ICObench"><img border="0" src="https://icobench.com/rated/zantepay?shape=horizontal&size=m" width="200" alt="Zantepay ICO rating"/></a>
                    </div>
                    <div class="col-lg col-md-4 mb-20">
                        <a href="https://icomarks.com/ico/zantepay" target="_blank" rel="nofollow" title="Zantepay (PreICO)"><img border="0" src="https://icomarks.com/widget/z/zantepay/square.svg" width="150px" height="150px" alt="Zantepay (PreICO) ICO rating"/></a>
                    </div>
                    <div class="col-lg col-md-4 mb-20">
                        <a href="https://www.coinschedule.com/ico/zantecoin" target="_blank" rel="nofollow" title="Zantepay Coinschedule">
                            <img border="0" src="images/coinschedule.jpg" srcset="images/coinschedule@2x.jpg 2x" alt="Zantepay Coinschedule" style="border:1px solid #ccc;"/>
                        </a>
                    </div>
                    <div class="col-lg col-md-4 mb-20">
                        <a href="https://foundico.com/ico/zantepay.html" target="_blank" rel="nofollow" title="ZANTEPAY on Foundico.com"><img width="150" height="150" border="0" src="https://foundico.com/widget/?p=12067&f=s" alt="ZANTEPAY score on Foundico.com" /></a>
                    </div>
                </div>
                <div class="row content-center">
                    <div class="col-lg-4 col-md-4 mb-20">
                        <a href="https://foxico.io/project/zantepay" target="_blank" title="ZANTEPAY on FoxICO"><img border="0" src="https://foxico.io/rating/zantepay" width="176px" height="72px" alt="ZANTEPAY ICO rating"/></a>
                    </div>
                </div>
            </div>
        </div>

        <section class="lp-section-four white-content" id="ico">
            <div class="container">
                <div class="row lp-row-1 content-center">
                    <div class="col-sm-6 text-center">
                        <h3 class="h2 headline">ICO <br> 50.000.000</h3>
                        <div class="lp-image-container4">
                            <img src="images/ICO.png" alt="ICO">
                        </div>
                        <h2 class="h2 headline ico-chart-headline mb-30">{{ $ico['name'] }} <span>1 ZNX = {{ $ico['euroRate'] }}€</span><span>1 ZNX = {{ $ico['ethRate'] }}
                                ETH</span></h2>
                        @guest
                            <a href="#sign-up-modal" class="js-popup-link btn btn--shadowed-dark btn--260">Buy Tokens NOW</a>
                        @endguest

                        @auth
                            <a href="user/wallet" class="btn btn--shadowed-dark btn--260">Buy Tokens NOW</a>
                        @endauth
                    </div>
                </div>

                <div class="row row-revert">
                    <div class="col-md-6 offset-md-1">
                        <div class="border-right-image">
                            <img src="images/refer-friend.jpg" alt="Refer a friend">
                        </div>
                    </div>
                    <div class="col-md-5 vertical-middle-col">
                        <h3 class="h2 headline">Refer a friend for a <span>20% commission</span></h3>
                        <p>Each holder of ZANTECOIN (ZNX) tokens will be entitled to a referral commission, paid weekly; this will be
                            constituted of 20% net transaction revenue.</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="lp-bg-1">
            <section class="lp-section-five">
                <div class="container">
                    <div class="row row-revert">
                        <div class="col-md-7">
                            <img src="images/iPhone-debit.jpg" srcset="images/iPhone-debit@2x.jpg 2x" alt="ZanteWallet">
                        </div>
                        <div class="col-md-5 vertical-middle-col">
                            <h2 class="h2 headline">What is <span>ZANTEPAY wallet</span></h2>
                            <ul class="styl-list">
                                <li>The ZANTEPAY wallet application will let you purchase, sell, send, receive and exchange your ZANTECOINs
                                    in the simplest and safest way possible.
                                </li>
                                <li>Its beauty and its ease of use.</li>
                                <li>Your latest ZANTEPAY card transactions or your exchange history.</li>
                                <li>You will also have the option to manage Bitcoin, Litecoin, Ethereum and Zantecoin directly from your
                                    ZANTEPAY wallet and assign your wallets to your ZANTEPAY card according to the selected preferences.
                                </li>
                                <li>Buy BTC and altcoins with SEPA payment or with debit/credit card.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <section class="lp-section-sixth">
                <div class="container">
                    <div class="row lp-row-1">
                        <div class="col-lg-5 col-md-6 content-center">
                            <div class="lp-image-container2">
                                <img src="images/card.png" srcset="images/card@2x.png 2x" alt="Zantpay Debit Card">
                            </div>
                            <div class="text-center">
                                <p class="text-regular">ZANTEPAY FREE debit card pre-order will be available shortly after ICO (after 15th July).</p>
                                <p class="text-regular">First 1000 card pre-orders will get rewarded 500 ZNX in bonus.</p>
                                <a href="#sign-up-modal" class="js-popup-link btn btn--shadowed-light">Sign up and participate in ICO</a>
                            </div>
                        </div>
                        <div class="col-md-6 offset-lg-1">
                            <h2 class="h2 headline">ZANTEPAY <span>debit card</span></h2>
                            <ul class="styl-list">
                                <li>Allows making purchases with Bitcoin, Litecoin, Ethereum or Zantecoin everywhere in the world.</li>
                                <li>Connected directly to your ZANTEPAY wallet.</li>
                                <li>Provides automatic highest trading price from partner exchanges. 1%exchange fee. No hidden charges.</li>
                                <li>20% cashback in ZANTECOIN.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="lp-arch-group">
                        <div class="text-center">
                            <h2 class="h2 headline">ZANTEPAY <span>payment architecture</span></h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <img src="images/zantepay-payment-architecture.png" alt="ZANTEPAY payments architecture">
                            </div>
                            <div class="col-lg-6">
                                <ol class="num-list">
                                    <li>Bob swipes the card in the shop. </li>
                                    <li>Card provider sends request to Zantepay backend;</li>
                                    <li>Zantepay backend is connected with exchange through API to determine BTC market price;</li>
                                    <li>Zantepay backend takes information from Bob ‘s wallet on his BTC balance and calculates how much Bob can spend with debit card;</li>
                                    <li>Zantepay backend sends information to Card provider;</li>
                                    <li>Card provider confirms transaction (in accordance with transaction limits);</li>
                                    <li>Card provider confirms transaction;</li>
                                    <li>Amount of BTC from Bob ‘s wallet will be deducted;</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <section class="lp-section-seventh white-content">
            <div class="container">
                <div class="row lp-row-1">
                    <div class="col-xl-2 offset-xl-2 col-lg-3 offset-lg-1 vertical-middle-col">
                        <div class="h2 text-right"><span>Traffic is THE KING</span></div>
                    </div>
                    <div class="col-lg-7">
                        <h2 class="h2 headline">Our goal is to become the biggest cryptocurrency wallet worldwide by the end of 2019</h2>
                    </div>
                </div>

                <div class="lp-headline-row">
                    <h2 class="h2 headline">Earn ZANTECOINs with every <br> swipe of your ZANTEPAY card</h2>
                </div>
                <div class="row lp-row-2">
                    <div class="col-md-6 vertical-middle-col content-center">
                        <div class="lp-coin-value">
                            <div class="lp-coin-text"> 20% <span
                                        class="h4">ZANTECOINs cashback <br> on all purchases <br> via Debit card</span></div>
                            <div class="lp-coin-img">
                                <img src="images/Coin.png" srcset="images/Coin@2x.png 2x" alt="ZANTEPAY Coin">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="lp-table-group">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="50%">Debit Card total <br> turnover, EUR</th>
                                        <th width="50%">Cashback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1 - 1 mln</td>
                                        <td>20%</td>
                                    </tr>
                                    <tr>
                                        <td>1 mln - 10 mln</td>
                                        <td>15%</td>
                                    </tr>
                                    <tr>
                                        <td>10 mln - 20 mln</td>
                                        <td>12%</td>
                                    </tr>
                                    <tr>
                                        <td>20 mln - 50 mln</td>
                                        <td>5%</td>
                                    </tr>
                                    <tr>
                                        <td>50 mln - 100 mln</td>
                                        <td>5%</td>
                                    </tr>
                                    <tr>
                                        <td>100 mln - 200 mln</td>
                                        <td>3%</td>
                                    </tr>
                                    <tr>
                                        <td>200 mln - 300 mln</td>
                                        <td>3%</td>
                                    </tr>
                                    <tr>
                                        <td>300 mln - 500 mln</td>
                                        <td>2%</td>
                                    </tr>
                                    <tr>
                                        <td>500 mln - 1 bln</td>
                                        <td>2%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="lp-section-nineth" id="team">
            <div class="container">
                <div class="text-center lp-row-1">
                    <h2 class="headline h2">Meet <span>ZANTEPAY team</span></h2>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <img src="images/mardo.jpg" srcset="images/mardo@2x.jpg 2x" alt="Mardo Soo">
                        <h3 class="h4">Mardo Soo <span>Founder</span> <a target="_blank"
                                                                         href="https://www.linkedin.com/in/mardo-soo-00a05ab0/"
                                                                         class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Mardo is a visionary, investor and entrepreneur, his expertise in sales and marketing is more than 10 years. He
                            ran couple of successful web projects in Estonia. His latest passion is blockchain and cryptocurrency. Mardo`s
                            talent is to find and recognize the best people for the job to make something great. He believes cryptocurrency
                            and AI is the next big thing.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/lena.jpg" srcset="images/lena@2x.jpg 2x" alt="Lena Elvbakken">
                        <h3 class="h4">Lena Elvbakken <span>Co-Founder</span> <a target="_blank"
                                                                                 href="https://www.linkedin.com/in/lena-elvbakken-8a238a56/"
                                                                                 class="soc-icon"><i class="fa fa-linkedin-square"></i></a>
                        </h3>
                        <p>Lena, BAA, has an impressive global product marketing and sales background. Previously worked in media,
                            technology and telecom branch for the brands like HP and Nokia. Then she moved on to online-based consulting
                            business and recently into the fin tech branch. Lena believes that her corporate experience, leadership and
                            passion for the technology make her a valuable asset for the team.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/daniel.jpg" srcset="images/daniel@2x.jpg 2x" alt="Daniel Raissar">
                        <h3 class="h4">Daniel Raissar <span>CTO</span> <a target="_blank" href="https://www.linkedin.com/in/daniel-raissar-58684848/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Daniel is an early adaptor of cryptocurrencies. BScIT from IT College. One of the founding members of Estonian Cryptocurrency Association. Has the experience of trading cryptocurrencies successfully for over 5 years.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/ron.jpg" srcset="images/ron@2x.jpg 2x" alt="Ron Luvistsuk">
                        <h3 class="h4">Ron Luvistsuk <span>CFO</span> <a target="_blank"
                                                                         href="https://www.linkedin.com/in/ron-luvistsuk-a1065714/"
                                                                         class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Ron is bringing his experience from SEB Corporate banking. He has more than 20 years of experience in finance,
                            deep understanding of risk, compliance and payment architecture in the EU. Ex CFO at Lukoil Baltic. As an
                            independent financial advisor Ron has led over 600 projects, totaling in over $60m.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/kristi.jpg" srcset="images/kristi@2x.jpg 2x" alt="Kristi Sild">
                        <h3 class="h4">Kristi Sild <span>Legal <br> Attorney at Law/ Partner, LEXTAL</span> <a target="_blank" href="https://www.linkedin.com/in/kristi-sild-16745093/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Kristi is an experienced attorney and leads the legal team of Zantepay project. Kristi is a partner in Lawfirm
                            LEXTAL, one of the biggest lawfirms in Estonia, having also offices in Latvia and Lithuania. Kristi has wide
                            legal experience in fintech field, including regulatory matters and other legal questions related to innovative
                            financing and financial services. Her expertise also covers services related to crypto currencies and ICOs.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/stas.jpg" srcset="images/stas@2x.jpg 2x" alt="Stanislav Ivashchenko">
                        <h3 class="h4">Stanislav Ivashchenko <span>User Experience and Product Design Lead</span> <a target="_blank" href="https://www.linkedin.com/in/stanislav-ivashchenko-2352054/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Stanislav shapes visual appearance of ZANTEPAY. He possesses more than 6 years of experience in graphic design
                            and usability across all media and has a passion for bringing brands to life through great user-centered
                            design.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/alex.jpg" srcset="images/alex@2x.jpg 2x" alt="Alexey Fedorenko">
                        <h3 class="h4">Alexey Fedorenko <span>Front-End Team Lead</span> <a target="_blank" href="https://www.linkedin.com/in/alexey-ffedorenko/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Alexey makes a great addition to our team with his commercial experience in front-end development, producing
                            high-quality websites and exceptional user experience.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/pavel.jpg" srcset="images/pavel@2x.jpg 2x" alt="Pavel Boyko">
                        <h3 class="h4">Pavel Boyko <span>Backend Team Lead</span> <a target="_blank" href="https://www.linkedin.com/in/pavel-boiko-a1123a3/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Pavel runs Advanced Software Development LTD (ASD), a Fintech software vendor. Pavel and his team has more than
                            10 year experience in delivering the variety of web applications, software engineering solutions, big data
                            analytics, mastering the hardest design, UX/UI tasks. Pavel believes in blockchain and the huge potential of the
                            industry.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/alexander.jpg" srcset="images/alexander@2x.jpg 2x" alt="Alexander Harutunian ">
                        <h3 class="h4">Alexander Harutunian <span>Smart Contracts / Project Manager</span> <a target="_blank" href="https://www.linkedin.com/in/alex113115/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Corporate finance and economics professional, specializing in Fintech. Previously, Alexander had a senior auditor
                            role at KPMG, led retail and banking sector engagements. He has MBA from American University of Armenia. </p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/oleg.jpg" srcset="images/oleg@2x.jpg 2x" alt="Oleg Pyvovarenko">
                        <h3 class="h4">Oleg Pyvovarenko <span>DM Team Lead</span> <a target="_blank" href="https://www.linkedin.com/in/oleg-pyvovarenko-b307b324/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Data-driven product marketing manager with 7+ years of experience in various global projects. Experienced in
                            gambling, betting, finance projects, ICO and crypto exchanges. Worked with huobi.pro, Aeron.aero, xchange.io
                            etc. </p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/vadim.jpg" srcset="images/vadim@2x.jpg 2x" alt="Vadim Ivanenko">
                        <h3 class="h4">Vadim Ivanenko <span>Bounty Manager</span> <a target="_blank" href="https://www.linkedin.com/in/vivanenko/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Vadim is a serial entrepreneur, blockchain evangelist and crypto investor. He is a CEO and Founder at Luft,
                            offering B2B solutions for the blockchain startups. His role at ZANTEPAY is to design and lead its Bounty
                            Programme.</p>
                    </div>
                </div>

                <div class="text-center lp-row-1">
                    <h2 class="headline h2"><span>Advisors</span></h2>
                </div>

                <div class="row">
                    <div class="col-lg-4">
                        <img src="images/dmitry.jpg" srcset="images/dmitry@2x.jpg 2x" alt="Dmitri Laush">
                        <h3 class="h4">Dmitri Laush <span style="font-size:0.83em;">Foreign Exchange, Integration Partner, Co-Founder of Admiral Markets</span>
                        <a target="_blank" href="https://www.linkedin.com/in/dmitrilaush/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a>
                        </h3>
                        <p>Experienced Investor with a demonstrated history of working 15 years in the financial services industry. Co-
                            founder of Admiral Markets investment firms operating under the Admiral Markets trademark. Dmitri is an advisor
                            and investor in various blockchain projects and startups. Founder of own crypto fund and a few blockchain
                            projects. Entrepreneur, Blockchain Evangelist and a true believer that decentralization, peer-to-peer (P2P)
                            governance systems and cryptocurrencies can help define a new path for the progress of humanity.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/cristobal.jpg" srcset="images/cristobal@2x.jpg 2x" alt="Cristobal Alonso">
                        <h3 class="h4">Cristobal Alonso <span style="font-size:0.83em;">Founder/ Global CEO @ SWG</span>
                        <a target="_blank" href="https://www.linkedin.com/in/cristobalalonso/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a>
                        </h3>
                        <p>Cristobal is the Global Capo of Startup Wise Guys – Europe’s leading B2B accelerator and the leading acceleration
                            platform for global founders and preferred deal flow partner for VC funds in Northern Europe and the CEE.
                            Cristobal’s unique background combines extensive CxO experience in different mobile operators in Europe, leading
                            several transformations and turnarounds, together with CEO startup experience both in B2B and B2C environments.
                            Cristobal has been involved in more than 15 startups in 20 different countries as a founder, early CEO,
                            investor, and lately as an advisor.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/oscar.jpg" srcset="images/oscar@2x.jpg 2x" alt="Oscar Sanjuán Martínez">
                        <h3 class="h4 h4--auto">Oscar Sanjuán Martínez <span style="font-size:0.83em;">Cloud Computing Expert, Master in Web Engineering and Ph.D. in Artificial Intelligence</span>
                        <a target="_blank" href="https://www.linkedin.com/in/osanjuan/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a>
                        </h3>
                        <p>With 20 years of experience in Computer Science and Information Technologies, He has published over 160 articles in journals and conferences. He has taught more than 60 seminars and conferences in Europe and Latin America on Software Engineering. Oscar spends his time building successful Engineering Teams and supporting organizations on their travel to the Cloud in both Private and Public Sectors.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/juan.jpg" srcset="images/juan@2x.jpg 2x" alt="Juan Alonso-Villalobos">
                        <h3 class="h4">Juan Alonso-Villalobos <span style="font-size:0.83em;">Fintech Programs Managing Director @ SWG</span>
                        <a target="_blank" href="https://www.linkedin.com/in/juanalonsovillalobos/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a>
                        </h3>
                        <p>Spanish serial entrepreneur. More than 20 years in retail banking and payments interationally. Senior Advisor to
                            several PE and VC funds. StartupWiseGuys Fintech programs Managing director.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/farid.jpg" srcset="images/farid@2x.jpg 2x" alt="Farid Singh">
                        <h3 class="h4 h4--auto">Farid Singh <span style="font-size:0.83em;">Innovation Consultant, User centric Product Manager, Blue Ocean Strategist & Tech Expert</span>
                        <a target="_blank" href="https://www.linkedin.com/in/farid-singh-823b391/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a>
                        </h3>
                        <p>With over 10 years of experience in TMT and deep tech, Farid focuses on getting new tech to people. He helps move
                            new technology to a mainstream product and leverages the benefits of new technology to drive adoption. </p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/rauno.jpg" srcset="images/rauno@2x.jpg 2x" alt="Rauno Klettenberg">
                        <h3 class="h4">Rauno Klettenberg <span style="font-size:0.83em;">Board Member at FinanceEstonia </span>
                        <a target="_blank" href="https://www.linkedin.com/in/rauno-klettenberg-48521a30/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a>
                        </h3>
                        <p>Ex Chairman of the Board at Nasdaq Tallinn. Ex General Manager of Handelsbanken. Member of the Board at Finance
                            Estonia. Rauno is an experienced Board Member with a demonstrated history of working in the financial services
                            industry. Expert in Asset Management, Management, Mergers & Acquisitions, and Financial Risk. He is a strong
                            business development professional with a Master's Degree focused in Accounting and Finance from Estonian
                            Business School.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('footer')

    <footer class="footer white-content">
        <div class="container">
            <div class="footer-partners" id="partners">
                <div class="text-center">
                    <h2 class="h2 headline text-left">Partners</h2>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <a target="_blank" rel="nofollow" href="http://www.kryptoraha.ee">
                            <img src="images/estonian-crypto-association.png" srcset="images/estonian-crypto-association@2x.png 2x" alt="estonian crypto association">
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <a target="_blank" rel="nofollow" href="https://startupwiseguys.com/">
                            <img src="images/swg.png" srcset="images/swg@2x.png 2x" alt="StartupWiseGuys">
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <a target="_blank" rel="nofollow" href="https://www.home.neustar/">
                            <img src="images/neustar.svg" width="220" alt="neustar">
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <a target="_blank" rel="nofollow" href="https://aws.amazon.com">
                            <img src="images/aws.png" alt="neustar">
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <a target="_blank" rel="nofollow" href="https://veriff.me/">
                            <img src="images/veriff.svg" alt="veriff">
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <a target="_blank" rel="nofollow" href="https://www.mongodb.com/">
                            <img src="images/mongodb-logo-white.png"  srcset="images/mongodb-logo-white@2x.png 2x" alt="mongoDB">
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-chanels" id="channels">
                <div class="text-center">
                    <h2 class="h2 headline text-left">Our Channels</h2>
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
                        <h4><a target="_blank" href="https://t.me/zantepay_ico">Telegram ICO</a></h4>
                        <a target="_blank" href="https://t.me/zantepay_ico"><i class="fa fa-telegram"></i></a>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="http://telegram.me/zantepay" style="font-size: 0.95em;">Telegram Channel</a></h4>
                        <a target="_blank" href="http://telegram.me/zantepay"><i class="fa fa-telegram"></i></a>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="https://www.instagram.com/zantepay">Instagram</a></h4>
                        <a target="_blank" href="https://www.instagram.com/zantepay"><i class="fa fa-instagram"></i></a>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="https://medium.com/@zantepay">Medium</a></h4>
                        <a target="_blank" href="https://medium.com/@zantepay"><i class="fa fa-medium"></i></a>
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
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="https://www.youtube.com/channel/UCP0ASZEKKM1DzFlhRu3FIpA">YouTube</a></h4>
                        <a target="_blank" href="https://www.youtube.com/channel/UCP0ASZEKKM1DzFlhRu3FIpA"><i class="fa fa-youtube-play"></i></a></li>
                    </div>
                    <div class="col-sm-3 col-6">
                        <h4><a target="_blank" href="https://www.linkedin.com/company/zantepay/">LinkedIn</a></h4>
                        <a target="_blank" href="https://www.linkedin.com/company/zantepay/"><i class="fa fa-linkedin"></i></a></li>
                    </div>
                </div>
            </div>

            <div class="row footer-cont-group" id="contacts">
                <div class="col-md-8 offset-md-2">
                    <div class="footer-cont-headline">
                        <h2 class="h2 headline">Contact <br> details</h2>
                    </div>
                    <div class="footer-cont-row row">
                        <div class="col-md-6">
                            <div class="footer-cont-text h5">ZANTEPAY OÜ <br> Rävala 19, 10143, <br> Tallinn, Estonia</div>
                        </div>
                        <div class="col-md-6">
                            <div class="footer-cont-text">For any questions please reach us at <a href="mailto:support@zantepay.com">support@zantepay.com</a> <br> or fill out the form below:</div>
                        </div>
                    </div>
                    <form id="frm_contact">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input id="contact-name" class="lp-form-input" type="text" name="name" placeholder="Name">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input id="contact-email" class="lp-form-input" type="email" name="email" placeholder="Email">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea id="contact-message" class="lp-form-textarea" rows="5" name="message"
                                      placeholder="Your message"></textarea>
                        </div>
                        <div class="text-center">
                            <input class="btn btn--shadowed-dark btn--160" type="submit" value="Send"
                                   onclick="ga('send',  'event',  'button', 'onclick', 'send');">
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="footer-logo mb-30">
                <a href="/" title="ZANTEPAY">
                    <img src="images/logo-large.png" alt="ZANTEPAY Logo">
                </a>
            </div>
            <div class="text-center"> 
                <h3 class="h2 mb-30">JOIN OUR NEWSLETTER</h3>
                <iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://app.mailjet.com/widget/iframe/2gIc/77E" width="100%" height="140"></iframe>
            </div>
            <div class="footer-menu">
                <ul>
                    <li><a href="{{ asset('storage/Zantepay_Terms_and_Conditions.pdf') }}" target="_blank">Terms & Conditions</a></li>
                    <li><a href="{{ asset('storage/Zantepay_Privacy_Policy.pdf') }}" target="_blank">Privacy Policy</a></li>
                    <li><a href="{{ asset('storage/Zantepay-AML-Policy.pdf') }}" target="_blank">AML/CTF Policy</a></li>
                </ul>
            </div>
            <p class="copyright"><span class="copyright-ico"></span> 2018 ZANTEPAY</p>
        </div>
    </footer>

@endsection

@section('popups')

    @parent

    <!-- sign up pre ico -->
    <div class="logon-modal logon-modal-lg mfp-hide" id="sign-up-preico">
        <div class="logon-modal-container">
            <h3 class="h4">REGISTER FOR PRE-ICO</h3>
            <div class="logon-modal-text">
                <p>ZANTECOIN’s Token Pre-ICO is created for investors with prior cryptocurrency experience. Please pay attention that the
                    minimum transaction amount is 0.1 ETH. For investors from the US the min investment amount is 10 ETH. To apply, please
                    contact <a href="mailto:support@zantepay.com">support@zantepay.com</a>. </p>
                <p>The actual opening date for the ZANTECOIN’s public Token Sale is on March 15<sup>th</sup>, 2018. To participate in
                    ZANTECOIN’s Token Pre-ICO, please enter you email below. You will be notified, once Pre-ICO starts.</p>
            </div>

            <form id="frm_ico_registration">
                <div class="logon-group">
                    <input class="logon-field" type="email" name="email" placeholder="Email">
                </div>
                <div class="logon-group">
                    <input class="logon-field" type="text" name="amount" placeholder="Estimated Amount (optional)">
                </div>
                <div class="text-gray text-sm">The payment will be made in ETH</div>
                <div class="logon-submit">
                    <input class="btn btn--shadowed-light btn--260" type="submit" value="Subscribe"
                           onclick="ga('send',  'event',  'button', 'onclick', 'subscribe');">
                </div>
            </form>
        </div>
    </div>

    <!-- pre ico confirmation -->
    <div class="logon-modal mfp-hide" id="confirm-sign-up-preico">
        <div class="logon-modal-container">
            <h3 class="h4">THANK YOU!</h3>
            <div class="logon-modal-text">
                <p>Your application has been submitted. Once the ZANTECOIN pre-ICO starts, you'll get a personal invitation to participate
                    in it.</p>
                <div>Enjoy your day!</div>
            </div>
        </div>
    </div>

    <!-- contact us confirmation -->
    <div class="logon-modal mfp-hide" id="confirm-contact-us">
        <div class="logon-modal-container">
            <h3 class="h4">THANK YOU!</h3>
            <div class="logon-modal-text">
                <p>Your message has been sent.</p>
                <div>Enjoy your day!</div>
            </div>
        </div>
    </div>

    <!-- Become our seed investors -->
    <div class="logon-modal logon-modal-md mfp-hide" id="become-our-seed-investors">
        <div class="logon-modal-container">
            <h3 class="h4">BECOME OUR SEED INVESTOR</h3>
            <div class="logon-modal-text">
                <p>We are starting with ZANTEPAY ICO on 15<sup>th</sup> of March. At the moment we are looking for seed investors, offering
                    ZNX tokens at
                    the discounted price. If you are interested to become a seed investor, please fill out the contact form and we will get
                    back to you.</p>
            </div>

            <form id="frm_investor">
                <div class="logon-group">
                    <input class="logon-field" type="text" name="first-name" placeholder="First Name">
                </div>
                <div class="logon-group">
                    <input class="logon-field" type="text" name="last-name" placeholder="Last Name">
                </div>
                <div class="logon-group">
                    <input class="logon-field" type="text" name="skype-id" placeholder="Skype ID">
                </div>
                <div class="logon-group">
                    <input class="logon-field" type="email" name="email" placeholder="Email">
                </div>
                <div class="logon-submit">
                    <input class="btn btn--shadowed-light btn--260" type="submit" value="Submit">
                </div>
            </form>
        </div>
    </div>

    <!-- Investor confirmation -->
    <div class="logon-modal mfp-hide" id="confirm-seed-investors">
        <div class="logon-modal-container">
            <h3 class="h4">THANK YOU!</h3>
            <div class="logon-modal-text">
                <p>Your application has been submitted.</p>
                <div>Enjoy your day!</div>
            </div>
        </div>
    </div>

    <!-- Terms of use -->
    <div class="logon-modal mfp-hide" id="terms-of-use">
        <div class="logon-modal-container">
            <h3 class="h4">TERMS OF USE</h3>
            <form action="">
                <div class="logon-group text-left">
                    <div class="checkbox">
                        <input type="checkbox" name="" id="check1"><label for="check1">I’ve read, understood and agree with the <a
                                    href="{{ asset('storage/Zantepay_Whitepaper.pdf') }}" target="_blank"
                                    onclick="ga('send',  'event',  'button', 'onclick', 'whitepaper');">Whitepaper</a></label>
                    </div>
                </div>
                <div class="logon-group text-left">
                    <div class="checkbox">
                        <input type="checkbox" name="" id="check2"><label for="check2">I’ve read and understood with the <a
                                    href="{{ asset('storage/Zantepay_Terms_and_Conditions.pdf') }}" target="_blank">
                                Terms & Conditions</a></label>
                    </div>
                </div>
                <div class="logon-group text-left">
                    <div class="checkbox">
                        <input type="checkbox" name="" id="check3"><label for="check3">I’ve read, understood and agree with the <a
                                    href="{{ asset('storage/Zantepay_Privacy_Policy.pdf') }}" target="_blank">Privacy
                                Terms</a></label>
                    </div>
                </div>
                <div class="logon-submit">
                    <input class="btn btn--shadowed-light btn--260" type="submit" value="Next">
                </div>
            </form>
        </div>
    </div>

@endsection