@extends('layouts.main-white')


@section('main')

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

@endsection

@section('popups')

    <!-- reset confirmation -->
    <div class="logon-modal mfp-hide" id="reset-confirm-modal">
        <div class="logon-modal-container">
            <h3 class="h4">RIGHT ON!</h3>
            <div class="logon-modal-text">
                <p>By now you should have received an email from us. To reset your account password please click the link in the email.</p>
            </div>
        </div>
    </div>

@endsection