@extends('layouts.main-white')


@section('main')

    <div class="invitation-wrap">
        <div class="row vertical-middle-col">

            <div class="col-md-6">
                <img src="/images/iPhone-debit.png" srcset="/images/iPhone-debit@2x.png 2x" alt="iPhone Debit card">
            </div>

            <div class="col-md-6">

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
                        <input id="signin_pwd" class="input-field" name="password" placeholder="Password" type="password">
                    </div>

                    <div class="logon-group">
                        <div id="sign-in-recaptcha" class="form-recaptcha"></div>
                        <input name="captcha" type="hidden">
                    </div>

                    <button type="submit" class="btn btn--shadowed-light btn--160 mt-20">Sign In</button>

                    <hr>

                    <div class="social-btns">
                        <a href="/account/fb" class="btn btn--facebook"><i></i> Sign In With Facebook</a>
                        <a href="/account/google" class="btn btn--google"><i></i> Sign In With Google</a>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

<!-- Google Captcha -->
<script>

    var signInWidgetID;

    var onloadCallback = function() {

        signInWidgetID = grecaptcha.render('sign-in-recaptcha', {
            'sitekey' : '{{$captcha}}',
            'theme' : 'light'
        });


    };

</script>

<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit'></script>
