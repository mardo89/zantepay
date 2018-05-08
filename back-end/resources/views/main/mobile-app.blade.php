@extends('layouts.main')

@section('header')

    <header class="header">

        @parent

    </header>

@endsection

@section('main')

    <main class="main main-mobile-app">
        <div class="container">
            <div class="app-preloader">
                <div class="app-preloader__container">
                    <div class="c-preloder">
                        <div class="c-preloder__object" id="object_four"></div>
                        <div class="c-preloder__object" id="object_three"></div>
                        <div class="c-preloder__object" id="object_two"></div>
                        <div class="c-preloder__object" id="object_one"></div>
                    </div>
                </div>
            </div>
            <div class="mobile-app-container">
                <div class="phone-mock">
                    <div class="app-steps">
                        <div class="app-step is-active" id="blank-screen">
                            <img src="images/mobile-app/iphone-x.jpg" srcset="images/mobile-app/iphone-x@2x.jpg 2x" alt="">
                        </div>
                        <div class="app-step" id="start-screen">
                            <img src="images/mobile-app/start-screen.jpg" srcset="images/mobile-app/start-screen@2x.jpg 2x" alt="ZANTEPAY Alpha App.">
                            <a href="#sign-in" class="link-area signin-area"></a>
                            <a href="#sign-up" class="link-area signup-area"></a>
                            <a href="#sign-up" class="link-area signup-area signup-area--fb"></a>
                            <a href="#sign-up" class="link-area signup-area signup-area--gl"></a>
                        </div>
                        <div class="app-step" id="account-card">
                            <img src="images/mobile-app/account-card.jpg" srcset="images/mobile-app/account-card@2x.jpg 2x" alt="ZANTEPAY Alpha App. Card">
                            <a href="#account-verification-status" class="link-area account-area account-area--1"></a>
                            <a href="#account-profile" class="link-area account-area account-area--2"></a>
                            <a href="#account" class="link-area account-area account-area--3"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="account-profile">
                            <img src="images/mobile-app/account-profile.jpg" srcset="images/mobile-app/account-profile@2x.jpg 2x" alt="ZANTEPAY Alpha App. Profile">
                            <a href="#account-verification-status" class="link-area account-area account-area--1"></a>
                            <a href="#account" class="link-area account-area account-area--2"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="account-security">
                            <img src="images/mobile-app/account-security.jpg" srcset="images/mobile-app/account-security@2x.jpg 2x" alt="ZANTEPAY Alpha App. Security">
                            <a href="#account-verification-status" class="link-area account-area account-area--1"></a>
                            <a href="#account-profile" class="link-area account-area account-area--2"></a>
                            <a href="#account-card" class="link-area account-area account-area--3"></a>
                            <a href="#account" class="link-area account-area account-area--4"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="account-verification-status">
                            <img src="images/mobile-app/account-verification-status.jpg" srcset="images/mobile-app/account-verification-status@2x.jpg 2x" alt="ZANTEPAY Alpha App. Verification Status">
                            <a href="#account" class="link-area account-area account-area--1"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="account">
                            <img src="images/mobile-app/account.jpg" srcset="images/mobile-app/account@2x.jpg 2x" alt="ZANTEPAY Alpha App. Account">
                            <a href="#start-screen" class="link-area account-signout-area"></a>
                            <a href="#account-verification-status" class="link-area account-area account-area--1"></a>
                            <a href="#account-profile" class="link-area account-area account-area--2"></a>
                            <a href="#account-card" class="link-area account-area account-area--3"></a>
                            <a href="#account-security" class="link-area account-area account-area--4"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="debit-change-currency">
                            <img src="images/mobile-app/debit-change-currency.jpg" srcset="images/mobile-app/debit-change-currency@2x.jpg 2x" alt="ZANTEPAY Alpha App. Debit Card. Change currency">
                            <a href="#debit" class="link-area currency-cancel-area"></a>
                        </div>
                        <div class="app-step" id="debit">
                            <img src="images/mobile-app/debit.jpg" srcset="images/mobile-app/debit@2x.jpg 2x" alt="ZANTEPAY Alpha App. Debit Card">
                            <a href="#debit-change-currency" class="link-area currency-area"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="history-deposit">
                            <img src="images/mobile-app/history-deposit.jpg" srcset="images/mobile-app/history-deposit@2x.jpg 2x" alt="ZANTEPAY Alpha App. History Deposit">
                            <a href="#transaction-details" class="link-area h-more-area h-more-area--1"></a>
                            <a href="#transaction-details" class="link-area h-more-area h-more-area--2"></a>
                            <a href="#transaction-details" class="link-area h-more-area h-more-area--3"></a>
                            <a href="#transaction-details" class="link-area h-more-area h-more-area--4"></a>
                            <a href="#transaction-details" class="link-area h-more-area h-more-area--5"></a>
                            <a href="#history-deposit" class="link-area top-area top-area--deposit"></a>
                            <a href="#history-withdrawal" class="link-area top-area top-area--withdrawal"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="history-withdrawal">
                            <img src="images/mobile-app/history-withdrawal.jpg" srcset="images/mobile-app/history-withdrawal@2x.jpg 2x" alt="ZANTEPAY Alpha App. History Withdrawal">
                            <a href="#transaction-details" class="link-area h-more-area h-more-area--1"></a>
                            <a href="#transaction-details" class="link-area h-more-area h-more-area--2"></a>
                            <a href="#transaction-details" class="link-area h-more-area h-more-area--3"></a>
                            <a href="#transaction-details" class="link-area h-more-area h-more-area--4"></a>
                            <a href="#transaction-details" class="link-area h-more-area h-more-area--5"></a>
                            <a href="#history-deposit" class="link-area top-area top-area--deposit"></a>
                            <a href="#history-withdrawal" class="link-area top-area top-area--withdrawal"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="price">
                            <img src="images/mobile-app/price.jpg" srcset="images/mobile-app/price@2x.jpg 2x" alt="ZANTEPAY Alpha App. Price">
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="receive">
                            <img src="images/mobile-app/receive.jpg" srcset="images/mobile-app/receive@2x.jpg 2x" alt="ZANTEPAY Alpha App. Receive">
                            <a href="#wallets" class="link-area transaction-back-area"></a>
                            <a href="#wallets-address" class="link-area md-bottom-area md-bottom-area--receive"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="send">
                            <img src="images/mobile-app/send.jpg" srcset="images/mobile-app/send@2x.jpg 2x" alt="ZANTEPAY Alpha App. Send">
                            <a href="#wallets" class="link-area md-bottom-area md-bottom-area--send"></a>
                            <a href="#wallets" class="link-area transaction-back-area"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="sign-in">
                            <img src="images/mobile-app/sign-in.jpg" srcset="images/mobile-app/sign-in@2x.jpg 2x" alt="ZANTEPAY Alpha App. Sign In">
                            <a href="#sign-up" class="link-area signin-area"></a>
                            <a href="#wallets" class="link-area signup-area"></a>
                            <a href="#wallets" class="link-area signup-area signup-area--fb"></a>
                            <a href="#wallets" class="link-area signup-area signup-area--gl"></a>
                            <a href="#wallets" class="link-area signup-area signup-area--another"></a>
                        </div>
                        <div class="app-step" id="sign-up">
                            <img src="images/mobile-app/sign-up.jpg" srcset="images/mobile-app/sign-up@2x.jpg 2x" alt="ZANTEPAY Alpha App. Sign Up">
                            <a href="#sign-in" class="link-area signin-area"></a>
                            <a href="#wallets" class="link-area signup-area"></a>
                            <a href="#wallets" class="link-area signup-area signup-area--fb"></a>
                            <a href="#wallets" class="link-area signup-area signup-area--gl"></a>
                        </div>
                        <div class="app-step" id="transaction-details">
                            <img src="images/mobile-app/transaction-details.jpg" srcset="images/mobile-app/transaction-details@2x.jpg 2x" alt="
                ZANTEPAY Alpha App. Transaction Details">
                            <a href="#history-deposit" class="link-area transaction-back-area"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="wallets-address">
                            <img src="images/mobile-app/wallets-address.jpg" srcset="images/mobile-app/wallets-address@2x.jpg 2x" alt="ZANTEPAY Alpha App. Wallets Address">
                            <a href="#receive" class="link-area transaction-back-area"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                        <div class="app-step" id="wallets">
                            <img src="images/mobile-app/wallets.jpg" srcset="images/mobile-app/wallets@2x.jpg 2x" alt="ZANTEPAY Alpha App. Wallets">
                            <a href="#send" class="link-area md-bottom-area md-bottom-area--send"></a>
                            <a href="#receive" class="link-area md-bottom-area md-bottom-area--receive"></a>
                            <a href="#price" class="link-area bottom-area bottom-area--price"></a>
                            <a href="#debit" class="link-area bottom-area bottom-area--debit"></a>
                            <a href="#wallets" class="link-area bottom-area bottom-area--wallets"></a>
                            <a href="#history-deposit" class="link-area bottom-area bottom-area--history"></a>
                            <a href="#account" class="link-area bottom-area bottom-area--account"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection


@section('popups')

    @parent

@endsection