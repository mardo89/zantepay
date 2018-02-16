@extends('layouts.main')

@section('header')

    <div id="particles-js"></div>

    <header class="header header-lp">
        <div class="masthead">
            <div class="container">
                <div class="masthead__row">
                    <div class="masthead__left">
                        <a href="/" class="logo" title="ZANTEPAY">
                            <img src="images/logo-large.png" alt="ZANTEPAY Logo">
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
                                <li><a href="#about-us">About Us</a></li>
                                <li><a href="#roadmap">Roadmap</a></li>
                                <li><a href="#ico">ICO</a></li>
                                <li><a href="#team">Team</a></li>
                                <li><a href="">FAQ</a></li>
                                <li><a href="#contacts">Contacts</a></li>
                            </ul>
                        </nav>

                        <div class="masthead__right">
                            <div class="logon-btns">
                                <a href="#sign-in-modal" class="js-popup-link btn btn--small btn--shadowed-dark">Sign In</a>
                                <a href="#sign-up-modal" class="js-popup-link btn btn--small btn--shadowed-dark">Sign Up</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="header__content white-content">
            <div class="container">
                <div class="center-logo pos-r">
                    <img src="images/logo-large.png" alt="ZANTEPAY Logo">
                </div>
                <h1 class="h2 header__title text-uppercase">Spend Bitcoin, Litecoin, Ethereum and Zantecoin in real life with just one card</h1>
                <div class="horizontal-btns">
                    <a href="{{ asset('storage/Zantepay_Whitepaper.pdf') }}" target="_blank" class="btn btn--shadowed-dark btn--260">Whitepaper</a>
                    <a href="#team" class="scroll-button btn btn--shadowed-dark btn--260">Team</a>
                    <a href="#sign-up-preico" class="js-popup-link btn btn--shadowed-dark btn--260">Register For Pre-ICO</a>
                </div>
                <div class="investors-block">
                    <h3 class="h4 text-uppercase">We are looking for seed investors</h3>
                    <a href="#become-our-seed-investors" class="js-popup-link btn btn--shadowed-dark btn--260">Become An Investor</a>
                </div>
                <h3 class="h4 text-uppercase"> 1 ETH = 14285 ZNX<br><br> Pre-sale starts in</h3>
                <div class="countdown">
                    <span class="js-countdown" data-date="2018/03/15 00:00:00"></span>
                </div>
            </div>
        </div>
    </header>

@endsection

@section('main')

    <main class="main main-lp">
        <section class="lp-section-artifical white-content">
            <div class="container">
                <h3 class="h2 headline">Artificial intelligence <span>at core</span></h3>
                <div class="row">
                    <div class="col-lg-7 col-md-6">
                        <div class="lp-head">
                            <img class="lp-head-img" src="images/head.png" srcset="images/head@2x.png 2x"
                                 alt="Artificial intelligence at core">
                            <img class="lp-head-lines" src="images/head-lines.png" srcset="images/head-lines@2x.png 2x" alt="">
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 lp-head-text">
                        <ul class="lp-features-list styl-list">
                            <li><span>Live chat</span><i class="sprite sprite-chat-icon"></i></li>
                            <li><span>Facebook messenger</span><i class="sprite sprite-messenger-icon"></i></li>
                            <li><span>FAQ</span><i class="sprite sprite-faq-icon"></i></li>
                            <li><span>KYC</span><i class="sprite sprite-kyc-icon"></i></li>
                            <li><span>Email</span><i class="sprite sprite-email-icon"></i></li>
                            <li><span>Marketing analysis</span><i class="sprite sprite-analysis-icon"></i></li>
                            <li><span>Twitter</span><i class="sprite sprite-twitter-icon"></i></li>
                        </ul>
                        <p>
                            <span class="h4 primary-color text-uppercase">OUR ULTIMATE GOAL IS</span>
                            <br>
                            To build the multiwallet where all <br> the processes are managed by AI
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="lp-section-one white-content" id="about-us">
            <div class="container">
                <div class="text-center">
                    <h3 class="h2 headline">What is a <br> ZANTECOIN</h3>
                </div>
                <div class="text-center">
                    <ul class="horizontal-icons">
                        <li>
                            <img src="images/secure.png" srcset="images/secure@2x.png 2x" alt="Secure">
                            <span>Secure</span>
                        </li>
                        <li>
                            <img src="images/blockchain_icon.png" srcset="images/blockchain_icon@2x.png 2x" alt="Blockchain Technology">
                            <span>Blockchain Technology</span>
                        </li>
                        <li>
                            <img src="images/ethereum_icon.png" srcset="images/ethereum_icon@2x.png 2x" alt="Ethereum Token">
                            <span>Ethereum Token</span>
                        </li>
                    </ul>
                </div>
                <div class="row vertical-middle-col">
                    <div class="col-md-4">
                        <div class="lp-dsc-wrap">
                            <div class="lp-dsc">
                                <img src="images/DSC-icon.png" srcset="images/DSC-icon@2x.png 2x" alt="DSC">
                                <span class="h4">Decentralized Smart Contract</span>
                            </div>
                            <div class="lp-dsc-arrow">
                                <img src="images/white-arrow.png" srcset="images/white-arrow@2x.png 2x" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 lp-center-iph">
                        <img src="images/iPhone-debit.png" srcset="images/iPhone-debit@2x.png 2x" alt="iPhone Debit card">
                    </div>
                    <div class="col-md-4">
                        With the DSC keys you have full control of your digital assets. From choice of currency, spending limits, spending history to withdrawal settings, etc.
                    </div>
                </div>
            </div>
        </section>

        <div class="lp-section-two white-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-5 offset-lg-2 offset-md-1 lp-pre-ico-col">
                        <h2 class="h2 headline">Pre - ICO Part I <span>1 ZNX=0.05€</span><span>1 ZNX = 0.00007ETH</span></h2>
                    </div>
                    <div class="col-md-5 text-center">
                        <div class="lp-progress-wrap">
                            <div class="js-lp-progress-blured lp-progress-blured"></div>
                            <div class="js-lp-progress lp-progress-top" data-percent="0.00"></div><!-- 17% - data-percet="0.17", etc.. -->
                            <div class="lp-progress-text"> 0.00% <span>distributed</span>
                            </div>
                        </div>
                        <p class="h4">0 ZNX distributed</p>
                    </div>
                </div>

                <div class="lp-section-two-btn">
                    <a href="#sign-up-preico" class="js-popup-link btn btn--shadowed-dark btn--260">Register For Pre-ICO</a>
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
                                <h3 class="h2 headline">Beginning of the ZANTECOIN pre-sale <span>February 15th</span></h3>
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
                                <h3 class="h2 headline">Pre-ICO - ICO III Part <span>February 15th - May 15th</span></h3>
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
                                <h3 class="h2 headline">Launch of ZANTEPAY wallet <span>2018 Q2</span></h3>
                                    <p>Debit card launch. Bitcoin, Litecoin, Ethereum and Zantecoin integration with Debit card and Wallet.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row row-revert p-tb-40">
                        <div class="col-md-6 content-center">
                            <div class="lp-image-container2">
                                <img src="images/card.png" srcset="images/card@2x.png 2x" class="lp-visa-card" width="480px" alt="ZANTEPAY Debit Card">
                            </div>
                        </div>
                        <div class="col-md-6 p-t-40 vertical-top-col pt-mob-0 content-center">
                            <div class="lp-content-col">
                                <h3 class="h2 headline">Debit card launch <span>2018 Q3</span></h3>
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

        <section class="lp-section-four white-content" id="ico">
            <div class="container">
                <div class="row lp-row-1">
                    <div class="col-sm-6 text-center">
                        <h3 class="h2 headline">ICO <br> 600.000.000</h3>
                        <div class="lp-image-container4">
                            <img src="images/ICO.png" alt="ICO">
                        </div>
                        <a href="{{ asset('storage/Zantepay_Whitepaper.pdf') }}" target="_blank" class="btn btn--shadowed-dark btn--260">Whitepaper</a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <h3 class="h2 headline">Token <br> distribution</h3>
                        <div class="lp-image-container4">
                            <img src="images/token-distribution.png" alt="Token Distribution">
                        </div>
                        <a href="#sign-up-preico" class="js-popup-link btn btn--shadowed-dark btn--260">Register For Pre-ICO</a>
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
                                <li>You will also have the option to manage Bitcoin, Litecoin, Ethereum and Zantecoin directly from your ZANTEPAY wallet and assign your wallets to your ZANTEPAY card according to the selected preferences.</li>
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
                                <a href="#sign-up-modal" class="js-popup-link btn btn--shadowed-light">Verify & Pre-order a FREE Debit
                                    Card</a>
                                <p class="text-lg">(currently under testing, pre-order opening soon)</p>
                                <p class="text-regular">Pre-order free debit card and receive 500ZNX!</p>
                                <p class="text-regular">Invite a friend to pre-order debit card. <br> RECEIVE 500ZNX WHEN PREORDER IS
                                    FINISHED! <br> Your friend gets 500ZNX.</p>
                            </div>
                        </div>
                        <div class="col-md-6 offset-lg-1">
                            <h2 class="h2 headline">ZANTEPAY <span>debit card</span></h2>
                            <ul class="styl-list">
                                <li>According to the selected preferences, Bitcoin, Litecoin, Ethereum and Zantecoin will let you purchase via Debit card everywhere in the world.</li>
                                <li>Directly connected to your ZANTEPAY wallet.</li>
                                <li>A multitude of management options will be available to you.</li>
                                <li>Automatic highest trading price from partner exchanges. Fee 1%. No hidden fees added.</li>
                                <li>20% cashback in ZANTECOINs.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row lp-row-2">
                        <div class="col-lg-6">
                            <div class="lp-znx-wrap">
                                <div class="lp-znx-img">
                                    <div class="lp-znx-txt"><span>600</span> ZNX</div>
                                </div>
                                <ul class="lp-znx-list">
                                    <li>Pre-Sale value: <span>30€</span></li>
                                    <li>ICO I value: <span>60€</span></li>
                                    <li>ICO II value: <span>84€</span></li>
                                    <li>ICO III value: <span>150€</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="lp-znx-wrap">
                                <div class="lp-znx-img">
                                    <div class="lp-znx-txt"><span>400</span> ZNX</div>
                                </div>
                                <ul class="lp-znx-list">
                                    <li>Pre-Sale value: <span>20€</span></li>
                                    <li>ICO I value: <span>40€</span></li>
                                    <li>ICO II value: <span>56€</span></li>
                                    <li>ICO III value: <span>100€</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="text-center lp-row-3">
                        <div class="text text-uppercase">FIRST 1000 CARDS GET A BONUS OF 1000 ZNX!</div>
                        <a href="#sign-up-preico" class="js-popup-link btn btn--shadowed-light">Register For Pre-ICO</a>
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
                        <h3 class="h2 headline">Earn ZANTECOINs with every <br> swipe of your ZANTEPAY card</h3>
                        <ul class="styl-list">
                            <li>Get 20% ZNX cashback on all purchases via ZANTEPAY debit card</li>
                            <li>Spend ZNX as the local currency with ZANTEPAY card</li>
                            <li>Exchange for Bitcoin and other cryptocurrencies</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <div class="lp-section-eight">
            <div class="container">
                <div class="text-center lp-row-1">
                    <h2 class="headline h2">Marketing plan for <span>600.000.000 ZNX</span></h2>
                </div>
                <div class="lp-table-group">
                    <h3 class="h2 headline">Bonus plan for <br> 5 mln debit card users</h3>
                    <div class="table-responsive-500">
                        <table class="table table-7-cols">
                            <thead>
                            <tr>
                                <th>Debit cards total</th>
                                <th>Pre-ordered cards</th>
                                <th>ZNX distributed as referral bonus</th>
                                <th>Pre-order value, EUR</th>
                                <th>ICO Part I value, EUR</th>
                                <th>ICO Part II value, EUR</th>
                                <th>ICO Part III value, EUR</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>0-1000</td>
                                <td>1000</td>
                                <td>1000</td>
                                <td>50</td>
                                <td>100</td>
                                <td>140</td>
                                <td>250</td>
                            </tr>
                            <tr>
                                <td>1000-5000</td>
                                <td>4000</td>
                                <td>800</td>
                                <td>40</td>
                                <td>80</td>
                                <td>112</td>
                                <td>200</td>
                            </tr>
                            <tr>
                                <td>5000-10000</td>
                                <td>5000</td>
                                <td>600</td>
                                <td>30</td>
                                <td>60</td>
                                <td>84</td>
                                <td>150</td>
                            </tr>
                            <tr>
                                <td>10000-15000</td>
                                <td>5000</td>
                                <td>400</td>
                                <td>20</td>
                                <td>40</td>
                                <td>56</td>
                                <td>100</td>
                            </tr>
                            <tr>
                                <td>15000-25000</td>
                                <td>10000</td>
                                <td>200</td>
                                <td>10</td>
                                <td>20</td>
                                <td>28</td>
                                <td>50</td>
                            </tr>
                            <tr>
                                <td>25000-50000</td>
                                <td>25000</td>
                                <td>100</td>
                                <td>5</td>
                                <td>10</td>
                                <td>14</td>
                                <td>25</td>
                            </tr>
                            <tr>
                                <td>50000-75000</td>
                                <td>25000</td>
                                <td>100</td>
                                <td>5</td>
                                <td>10</td>
                                <td>14</td>
                                <td>25</td>
                            </tr>
                            <tr>
                                <td>75000-100000</td>
                                <td>25000</td>
                                <td>80</td>
                                <td>4</td>
                                <td>8</td>
                                <td>11,2</td>
                                <td>20</td>
                            </tr>
                            <tr>
                                <td>100k - 1 mln</td>
                                <td>900000</td>
                                <td>80</td>
                                <td>4</td>
                                <td>8</td>
                                <td>11,2</td>
                                <td>20</td>
                            </tr>
                            <tr>
                                <td>1 mln - 5 mln</td>
                                <td>4000000</td>
                                <td>60</td>
                                <td>3</td>
                                <td>6</td>
                                <td>8,4</td>
                                <td>15</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="lp-table-group">
                    <h3 class="h2 headline">Cashback bonus plan <br>for turnover 1 BLN</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Turnover amount</th>
                            <th>Turnover</th>
                            <th>Cashback</th>
                            <th>ZNX distributed</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1 - 1 mln</td>
                            <td>1000000</td>
                            <td>20%</td>
                            <td>2000000</td>
                        </tr>
                        <tr>
                            <td>1 mln - 10 mln</td>
                            <td>9000000</td>
                            <td>15%</td>
                            <td>13500000</td>
                        </tr>
                        <tr>
                            <td>10 mln - 20 mln</td>
                            <td>10000000</td>
                            <td>12%</td>
                            <td>12000000</td>
                        </tr>
                        <tr>
                            <td>20 mln - 50 mln</td>
                            <td>30000000</td>
                            <td>5%</td>
                            <td>15000000</td>
                        </tr>
                        <tr>
                            <td>50 mln - 100 mln</td>
                            <td>50000000</td>
                            <td>5%</td>
                            <td>25000000</td>
                        </tr>
                        <tr>
                            <td>100 mln - 200 mln</td>
                            <td>100000000</td>
                            <td>3%</td>
                            <td>30000000</td>
                        </tr>
                        <tr>
                            <td>200 mln - 300 mln</td>
                            <td>100000000</td>
                            <td>3%</td>
                            <td>30000000</td>
                        </tr>
                        <tr>
                            <td>300 mln - 500 mln</td>
                            <td>200000000</td>
                            <td>2%</td>
                            <td>40000000</td>
                        </tr>
                        <tr>
                            <td>500 mln - 1 bln</td>
                            <td>500000000</td>
                            <td>2%</td>
                            <td>100000000</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lp-section-nineth" id="team">
            <div class="container">
                <div class="text-center lp-row-1">
                    <h2 class="headline h2">Meet the <span>ZANTEPAY team</span></h2>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <img src="images/mardo.jpg" srcset="images/mardo@2x.jpg 2x" alt="Mardo Soo">
                        <h3 class="h4">Mardo Soo <span>Founder</span> <a target="_blank" href="https://www.linkedin.com/in/mardo-soo-00a05ab0/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a> </h3>
                        <p>Mardo is a visionary, investor and entrepreneur, his expertise in sales and marketing is more than 10 years. He ran couple of successful web projects in Estonia. His latest passion is blockchain and cryptocurrency. Mardo`s talent is to find and recognize the best people for the job to make something great. He believes cryptocurrency and AI is the next big thing.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/lena.jpg" srcset="images/lena@2x.jpg 2x" alt="Lena Elvbakken">
                        <h3 class="h4">Lena Elvbakken <span>Co-Founder</span> <a target="_blank" href="https://www.linkedin.com/in/lena-elvbakken-8a238a56/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Lena, BAA, has an impressive global product marketing and sales background. Previously worked in media, technology and telecom branch for the brands like HP and Nokia. Then she moved on to online-based consulting business and recently into the fin tech branch. Lena believes that her corporate experience, leadership and passion for the technology make her a valuable asset for the team.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/ron.jpg" srcset="images/ron@2x.jpg 2x" alt="Ron Luvistsuk">
                        <h3 class="h4">Ron Luvistsuk <span>CFO</span> <a target="_blank" href="https://www.linkedin.com/in/ron-luvistsuk-a1065714/" class="soc-icon"><i class="fa fa-linkedin-square"></i></a></h3>
                        <p>Ron is bringing his experience from SEB Corporate banking. He has more than 20 years of experience in finance, deep understanding of risk, compliance and payment architecture in the EU. Ex CFO at Lukoil Baltic. As an independent financial advisor Ron has led over 600 projects, totaling in over $60m.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/stas.jpg" srcset="images/stas@2x.jpg 2x" alt="Stanislav Ivashchenko">
                        <h3 class="h4">Stanislav Ivashchenko <span>User Experience and Product Design Lead</span></h3>
                        <p>Stanislav shapes visual appearance of ZANTEPAY. He possesses more than 6 years of experience in graphic design and usability across all media and has a passion for bringing brands to life through great user-centered design.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/alex.jpg" srcset="images/alex@2x.jpg 2x" alt="Alexey Fedorenko">
                        <h3 class="h4">Alexey Fedorenko <span>Front-End Team Lead</span></h3>
                        <p>Alexey makes a great addition to our team with his commercial experience in front-end development, producing high-quality websites and exceptional user experience.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/pavel.jpg" srcset="images/pavel@2x.jpg 2x" alt="Pavel Boyko">
                        <h3 class="h4">Pavel Boyko <span>Backend Team Lead</span></h3>
                        <p>Pavel runs Advanced Software Development LTD (ASD), a Fintech software vendor. Pavel and his team has more than 10 year experience in delivering the variety of web applications, software engineering solutions, big data analytics, mastering the hardest design, UX/UI tasks. Pavel believes in blockchain and the huge potential of the industry.</p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/alexander.jpg" srcset="images/alexander@2x.jpg 2x" alt="Alexander Harutunian ">
                        <h3 class="h4">Alexander Harutunian <span>Smart Contracts / Project Manager</span></h3>
                        <p>Corporate finance and economics professional, specializing in Fintech. Previously, Alexander had a senior auditor role at KPMG, led retail and banking sector engagements. He has MBA from American University of Armenia. </p>
                    </div>
                    <div class="col-lg-4">
                        <img src="images/levon.jpg" srcset="images/levon@2x.jpg 2x" alt="Levon Hayrapetyan">
                        <h3 class="h4">Levon Hayrapetyan <span>Smart Contracts Developer</span></h3>
                        <p>Levon is an experienced software engineer specializing in cloud technologies and blockchain. Levon brings in experience from Microsoft. He has Masters of Computer Science and Applied Mathematics.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@section('footer')

    <footer class="footer footer-lp white-content" id="contacts">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="footer-cont-row">
                        <h2 class="h2 headline">Contact <br> details</h2>
                        <div class="footer-cont-text">For any questions please reach us at <a href="mailto:support@zantepay.com">support@zantepay.com</a>
                            <br> or fill out the form below:
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
                            <input class="btn btn--shadowed-dark btn--160" type="submit" value="Send">
                        </div>
                    </form>
                </div>
            </div>
            <div class="footer-logo">
                <a href="/" title="ZANTEPAY">
                    <img src="images/logo-large.png" alt="ZANTEPAY Logo">
                </a>
            </div>
            <div class="footer-social">
                <ul class="social-list">
                    <li><a target="_blank" href="https://www.facebook.com/ZANTEPAY/"><i class="fa fa-facebook"></i></a></li>
                    <li><a target="_blank" href="https://twitter.com/zantepay"><i class="fa fa-twitter"></i></a></li>
                    <li><a href="mailto:support@zantepay.com"><i class="fa fa-envelope"></i></a></li>
                </ul>
            </div>
            <div class="footer-menu">
                <ul>
                    <li><a href="">Terms & Conditions</a></li>
                    <li><a href="">Privacy Policy</a></li>
                </ul>
            </div>
            <p class="copyright"><span class="copyright-ico"></span> 2017 ZANTEPAY</p>
        </div>
    </footer>

@endsection

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
                    <input id="signin_pwd" class="logon-field" type="password" name="password" placeholder="Password">
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
                <p>By now you should have received an email from us. To reset your account password please click the link in the email.</p>
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
                    <input class="logon-field" type="password" name="password" placeholder="Password">
                </div>
                <div class="logon-group">
                    <input class="logon-field" type="password" name="confirm-password" placeholder="Confirm Password">
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
                <p>Thank you for registering with ZANTEPAY. By now you should have received a confirmation email from us. To activate your
                    account please click the link in the email.</p>
            </div>

            <a href="" id="resend-registration-email" class="btn btn--shadowed-light btn--260">Resend Email</a>
        </div>
    </div>

    <!-- sign up pre ico -->
    <div class="logon-modal logon-modal-lg mfp-hide" id="sign-up-preico">
        <div class="logon-modal-container">
            <h3 class="h4">REGISTER FOR PRE-ICO</h3>
            <div class="logon-modal-text">
                <p>ZANTECOIN’s Token Pre-ICO is created for investors with prior cryptocurrency experience. Please pay attention that the
                    minimum transaction amount is 0.1 ETH. For investors from the US the min investment amount is 10 ETH. To apply, please
                    contact <a href="mailto:support@zantepay.com">support@zantepay.com</a>. </p>
                <p>The actual opening date for the ZANTECOIN’s public Token Sale is on March 15th, 2018. To participate in ZANTECOIN’s
                    Token Pre-ICO, please enter you email below. You will be notified, once Pre-ICO starts.</p>
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
                    <input class="btn btn--shadowed-light btn--260" type="submit" value="Subscribe">
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
                <p>We are starting with ZANTEPAY ICO on 15th of March. At the moment we are looking for seed investors, offering ZNX tokens at
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
                        <input type="checkbox" name="" id="check1"><label for="check1">I’ve read, understood and agree with the <a href="#">Whitepaper</a></label>
                    </div>
                </div>
                <div class="logon-group text-left">
                    <div class="checkbox">
                        <input type="checkbox" name="" id="check2"><label for="check2">I’ve read and understood the <a href="#">Referral
                                Terms & Conditions</a></label>
                    </div>
                </div>
                <div class="logon-group text-left">
                    <div class="checkbox">
                        <input type="checkbox" name="" id="check3"><label for="check3">I’ve read, understood and agree with the <a href="#">Privacy
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