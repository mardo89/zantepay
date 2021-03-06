@extends('layouts.main-white')

@section('main')

    <h2 class="h4 headline-mb">Hi there!</h2>
    <p>Your password was reset. Please input new password for your account</p>

    <form id="frm_change_password" class="inv-form">
        <input type="hidden" name="reset-token" value="{{ $resetToken }}"/>

        <div class="form-group">
            <input id="signup_pwd" class="input-field" name="password" placeholder="Password" type="password">
        </div>

        <div class="form-group">
            <input id="signup_cnf_pwd" class="input-field" name="password_confirmation" placeholder="Confirm Password" type="password">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn--shadowed-light btn--full-w" value="Apply" />
        </div>
    </form>

@endsection

@section('popups')

@endsection