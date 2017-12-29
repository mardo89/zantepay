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
                        <p>Your password was reset. Please input new password for your account</p>
                        <form id="frm_change_password" class="inv-form">
                            <input type="hidden" name="reset-token" value="{{ $resetToken }}" />

                            <div class="form-group">
                                <input id="signup_pwd" class="input-field" type="password" name="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <input id="signup_cnf_pwd" class="input-field" type="password" name="confirm-password" placeholder="Confirm Password">
                            </div>
                            <button type="submit" class="btn btn--shadowed-light btn--160 mt-20">Apply</button>
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

@endsection