@extends('layouts.main')

@section('header')

    <div id="particles-js"></div>

    <header class="header header-lp">
        <div class="masthead">
            <div class="container">
                <div class="masthead__row">
                    <div class="masthead__left">
                        <a href="/" class="logo" title="ZANTEPAY">
                            <img src="/images/logo-large.png" alt="ZANTEPAY Logo">
                        </a>
                    </div>
                </div>
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
                        <img src="/images/spin-logo.png" alt="">
                    </div>
                    <h2 class="h2 text-uppercase">The site is under maintenance</h2>
                    <p>Sorry for the inconvenience. We will be back, shortly. <br> Should you have any questions, please do not hesitate to reach out to us.</p>
                    <a href="mailto:support@zantepay.com" class="btn btn--shadowed-dark btn--260">Email Us</a>
                </div>
            </div>
        </section>
    </main>

@endsection


@section('popups')

@parent

@endsection