@extends('layouts.main')

@section('header')

    <header class="header">
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
                                <li><a href="{{ asset('storage/Zantepay_Whitepaper.pdf') }}" onclick="ga('send',  'event',  'button', 'onclick', 'whitepaper');">Whitepaper</a></li>
                                <li><a href="/#ico">ICO</a></li>
                                <li><a href="">Bounty</a></li>
                                <li><a href="/#team">Team</a></li>
                                <li>
                                    <a href="">Development</a>
                                    <ul>
                                        <li><a href="">Wallet Beta</a></li>
                                        <li><a href="">App Beta</a></li>
                                        <li><a href="">Development roadmap</a></li>
                                    </ul>
                                </li>
                                <li><a href="/faq">FAQ</a></li>
                            </ul>
                        </nav>

                        <div class="masthead__right">
                            @guest
                                <div class="logon-btns">
                                    <a href="#sign-in-modal" class="js-popup-link btn btn--small btn--shadowed-dark">Log In</a>
                                    <a href="#sign-up-modal" class="js-popup-link btn btn--small btn--shadowed-dark">Sign Up</a>
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
    </header>

@endsection

@section('main')

    <main class="main main-dashboard">
        <div class="container">
            <h2 class="h4 headline-mb">Frequently asked questions</h2>
            <div class="dashboard-top-panel-row tabs-head-faq">
                <ul class="tabs-head">
                    <li class="is-active">
                        <a href="#most-popular">Most popular</a>
                    </li>
                    <li>
                        <a href="#all-questions">All questions</a>
                    </li>
                    <li>
                        <a href="#submit-a-ticket">Submit a ticket</a>
                    </li>
                </ul>
            </div>

            <div class="tabs-wrap">
                <!-- tab Most Popular -->
                <div class="tab-body is-active" id="most-popular">
                    <div class="accordion js-accordion">
                        <div class="accordion-group">
                            <div class="accordion__head is-active">
                                <a href="#ac-item1">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">When and what exchanges ZANTEPAY token (ZNX) is going to be listed on?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item1" style="display: block;">
                                <p>ZNX token is going to be tradable after the ICO is complete, latest in August 2018. ZANTEPAY team is working actively on getting listed on several exchanges. Follow the further announcements.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item2">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">Do you have a card provider?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item2">
                                <p>Yes, we do. We are using a payment provider in Estonia to issue the cards.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item3">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">When do I get my card?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item3">
                                <p>According our roadmap the card will be available in Q4 2018.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item4">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">How do you implement AI technology?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item4">
                                <p>We use AI to automate certain processes in daily operations, for example customer support, optimizing interaction with the client, verification process, interaction with community through social media, etc. In the later stage of our project AI will be implemented into security and exchange activities.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item5">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">When do I need to submit KYC ( know your customer) documents?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item5">
                                <p>You can start KYC verification any time you want. You can buy tokens without KYC, but you will not be able to withdraw your tokens without KYC verification.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item6">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">Where is ZANTEPAY incorporated?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item6">
                                <p>ZANTEPAY is incorporated in Estonia.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item7">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">When does ICO start?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item7">
                                <p>The pre-ICO starts on 15th of March 2018, it will follow by ICO, part I, II, III until 15th of July 2018.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END tab Most Popularn -->

                <!-- tab All questions-->
                <div class="tab-body" id="all-questions">
                    <div class="accordion js-accordion">
                        <div class="accordion-group">
                            <div class="accordion__head is-active">
                                <a href="#ac-item01">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">When and what exchanges ZANTEPAY token (ZNX) is going to be listed on?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item01" style="display: block;">
                                <p>ZNX token is going to be tradable after the ICO is complete, latest in August 2018. ZANTEPAY team is working actively on getting listed on several exchanges. Follow the further announcements.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item02">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">Do you have a card provider?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item02">
                                <p>Yes, we do. We are using a payment provider in Estonia to issue the cards.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item03">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">When do I get my card?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item03">
                                <p>According our roadmap the card will be available in Q4 2018.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item04">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">How do you implement AI technology?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item04">
                                <p>We use AI to automate certain processes in daily operations, for example customer support, optimizing interaction with the client, verification process, interaction with community through social media, etc. In the later stage of our project AI will be implemented into security and exchange activities.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item05">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">When do I need to submit KYC ( know your customer) documents?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item05">
                                <p>You can start KYC verification any time you want. You can buy tokens without KYC, but you will not be able to withdraw your tokens without KYC verification.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item06">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">Where is ZANTEPAY incorporated?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item06">
                                <p>ZANTEPAY is incorporated in Estonia.</p>
                            </div>
                        </div>
                        <div class="accordion-group">
                            <div class="accordion__head">
                                <a href="#ac-item07">
                                    <i class="fa fa-chevron-down" aria-hidden="true"></i>
                                    <h3 class="text-lg">When does ICO start?</h3>
                                </a>
                            </div>
                            <div class="accordion__body" id="ac-item7">
                                <p>The pre-ICO starts on 15th of March 2018, it will follow by ICO, part I, II, III until 15th of July 2018.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END tab All questions -->

                <!-- tab Submit a ticket -->
                <div class="tab-body" id="submit-a-ticket">
                    <form id="frm_faq" class="faq-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input class="input-field" type="text" id="user_name" placeholder="Name" required>
                                </div>
                                <div class="form-group">
                                    <input class="input-field" type="text" id="user_email" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="textarea-field" id="user_question" placeholder="Your question" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-20">
                            <input class="btn btn--160 btn--shadowed-light" type="submit" value="Send">
                        </div>
                    </form>
                </div>
                <!-- END tab Submit a ticket -->
            </div>
        </div>
    </main>

@endsection


@section('popups')

    <!-- question confirmation -->
    <div class="logon-modal mfp-hide" id="confirm-question">
        <div class="logon-modal-container">
            <h3 class="h4">THANK YOU!</h3>
            <div class="logon-modal-text">
                <p>Your question has been sent.</p>
                <div>Enjoy your day!</div>
            </div>
        </div>
    </div>

@endsection