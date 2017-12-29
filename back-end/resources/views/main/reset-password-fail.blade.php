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
                        <p>Unable to restore password because this token is invalid or expired. Please try again.</p>

                        <form id="frm_forgot_password">
                            <div class="logon-group">
                                <input class="logon-field" type="email" name="email" placeholder="Email">
                            </div>

                            <div class="logon-submit mt-35">
                                <input class="btn btn--shadowed-light btn--260" type="submit" value="Reset Password">
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

@section('popups')

    <!-- reset confirmation -->
    <div class="logon-modal mfp-hide" id="reset-confirm-modal">
        <div class="logon-modal-container">
            <h3 class="h4">RIGHT ON!</h3>
            <div class="logon-modal-text">
                <p>By now you should have received an email from us. To reset your account password please click the link  in the email.</p>
            </div>
        </div>
    </div>

@endsection