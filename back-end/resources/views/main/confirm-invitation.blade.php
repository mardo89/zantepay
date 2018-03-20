@extends('layouts.main-white')


@section('main')

    <div class="invitation-wrap invitation-wrap-3col">
        <div class="row">
            <div class="col-lg-4 inv-col1">
                <h2 class="h2 headline headline--black p-b-60 p-t-40">Refer a friend for a <br> <span>20%</span> commission</h2>
                <div class="row p-b-60 inv-card-row">
                    <img src="images/wh-card-sm.png" srcset="images/wh-card-sm@2x.png 2x" alt="Zantepay Mastercad">
                </div>
                <div class="row vertical-middle-col inv-bonus-row">
                    <div class="col-md-8">
                        <h2 class="h2 headline headline--black">Verify and pre-order a <span class="primary-color">FREE</span> card</h2>
                    </div>
                    <div class="col-md-4 pl-0">
                        <img src="images/get-500-znx.png" srcset="images/get-500-znx@2x.png 2x" alt="get 500 ZNX">
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4 text-center">
                <img src="images/iphone-wallets.png" srcset="images/iphone-wallets@2x.png 2x" alt="Zantepay Wallets">
            </div>

            <div class="col-sm-6 col-lg-4 inv-content-col">

                <h2 class="h4 headline-mb">Hi there!</h2>
                <p>You’ve been invited to join ZANTEPAY ICO and pre-order a <span class="primary-color">FREE</span> ZANTEPAY card.</p>

                <form id="frm_invite_signup" class="inv-form">
                    <div class="form-group">
                        <input id="signup_email" class="input-field" type="email" name="email" placeholder="Email">
                    </div>

                    <div class="form-group">
                        <input id="signup_pwd" class="input-field" type="password" name="password" placeholder="Password">
                    </div>

                    <div class="form-group">
                        <input id="signup_cnf_pwd" class="input-field" type="password" name="confirm-password"
                               placeholder="Confirm Password">
                    </div>

                    <div class="form-group">
                        <input class="input-field" type="hidden" name="referral" value="{{ $referralToken }}" readonly>
                    </div>

                    <button type="submit" class="btn btn--shadowed-light btn--160 mt-20">Sign Up</button>

                    <hr>

                    <div class="social-btns">
                        <a href="/account/fb" class="btn btn--facebook"><i></i> Sign Up With Facebook</a>
                        <a href="/account/google" class="btn btn--google"><i></i> Sign Up With Google</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

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