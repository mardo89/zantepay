@extends('layouts.main-white')


@section('main')

    <h2 class="h4 headline-mb">Hi there!</h2>
    <p>Password successfully changed.</p>

    <form id="frm_signin" class="inv-form">
        <div class="form-group">
            <input id="signin_email" class="input-field" type="email" name="email" placeholder="Email">
        </div>

        <div class="form-group">
            <input id="signin_pwd" class="input-field" type="password" name="password" placeholder="Password" autocomplete="off">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn--shadowed-light btn--full-w" value="Sign In" />
        </div>
    </form>

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