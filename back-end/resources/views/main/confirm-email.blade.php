@extends('layouts.main')

@section('header')
    <header class="header header-transparent">
        <div class="masthead">
            <div class="container">
                <div class="masthead__row">
                    <div class="masthead__left">
                        <a href="/" class="logo" title="ZANTEPAY">
                            <img src="/images/logo-large-wsh.png" alt="ZANTEPAY Logo">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
@endsection

@section('main')
    <main class="main main-inv">
        <div class="container">
            <div class="invitation-wrap">
                <div class="row vertical-middle-col">
                    <div class="col-md-6">
                        <img src="/images/iPhone-debit.png" srcset="/images/iPhone-debit@2x.png 2x" alt="iPhone Debit card">
                    </div>
                    <div class="col-md-6">
                        <h2 class="h4 headline-mb">Congratulations!</h2>
                        <p>Your email has been successfully confirmed. Now you can log in to your ZANTEPAY account and start using the cryptocurrency of tomorrow.</p>
                        <form id="frm_signin" class="inv-form">
                            <div class="form-group">
                                <input id="signin_email" class="input-field" type="email" name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input id="signin_pwd" class="input-field" type="password" name="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn--shadowed-light btn--160 mt-20">Sign In</button>
                            <hr>
                            <div class="social-btns">
                                <a href="/auth/fb" class="btn btn--facebook"><i></i> Sign In With Facebook</a>
                                <a href="/auth/google" class="btn btn--google"><i></i> Sign In With Google</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer-center">
            <div class="footer-nav">
                <ul>
                    <li><a href="">Referral Terms & Conditions</a></li>
                    <li><a href="">Privacy Terms</a></li>
                    <li><a href="{{ asset('storage/Zantepay_Whitepaper.pdf') }}" target="_blank">Whitepaper</a></li>
                </ul>
            </div>
        </footer>

    </main>
@endsection