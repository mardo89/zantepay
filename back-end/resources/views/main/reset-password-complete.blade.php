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
                        <h2 class="h4 headline-mb">Hi there!</h2>
                        <p>Password successfully changed.</p>
                        <form id="frm_signin" class="inv-form">
                            <div class="form-group">
                                <input id="signin_email" class="input-field" type="email" name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input id="signin_pwd" class="input-field" type="password" name="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn--shadowed-light btn--160 mt-20">Sign In</button>
                            <hr>
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

@section('popups')
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
@endsection