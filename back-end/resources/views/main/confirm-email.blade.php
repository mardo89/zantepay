@extends('layouts.main-white')


@section('main')

    <h2 class="h4 headline-mb">Congratulations!</h2>
    <p>
        Your email has been successfully confirmed. Now you can log in to your ZANTEPAY account and start using the cryptocurrency of
        tomorrow.
    </p>

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
            <a href="/account/fb" class="btn btn--facebook"><i></i> Sign In With Facebook</a>
            <a href="/account/google" class="btn btn--google"><i></i> Sign In With Google</a>
        </div>
    </form>

@endsection