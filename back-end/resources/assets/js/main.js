require('./bootstrap');

// Signup Err
const showSignupErr = (errorMessage, errorsList) => {
    console.error(errorMessage);

    const errors = _.flatMap(
        errorsList,
        error => error
    );

    console.table(errors);
}

// Login Err
showLoginErr = (result, show) => {
    $('#login_err').text(result.type);
    $('#login_err_msg').text(result.msg);
    if (show)
        $('.login_err').show();
    else
        $('.login_err').hide();
}

//Spin function
showSpin = visible => {
    if (visible)
        $('#spin').show();
    else
        $('#spin').hide();
}

// Send activation email
sendActivationEmail = uid => {
    axios.post(
        '/mail/activate-account',
        qs.stringify(
            {
                uid
            }
        )
    )
}

// FB login
fbLogin = (id, email) => {
    axios.post(
        '/auth/fb-login',
        qs.stringify(
            {
                id,
                email
            }
        )
    )
}

// Google login
gLogin = (id, email) => {
    axios.post(
        '/auth/g-login',
        qs.stringify(
            {
                id,
                email
            }
        )
    )
}

$(document).ready(function () {
    // Count down
    if ($('.js-countdown').length) {
        var date = $('.js-countdown').data('date');
        $('.js-countdown').countdown(date)
            .on('update.countdown', function (event) {
                var dateFormat = '<span class="countdown-group"><span class="countdown-txt-lg">%D</span><span class="countdown-txt-sm">Days</span></span>';
                dateFormat += '<span class="countdown-dots">:</span><span class="countdown-group"><span class="countdown-txt-lg">%H</span><span class="countdown-txt-sm">Hours</span></span>';
                dateFormat += '<span class="countdown-dots">:</span><span class="countdown-group"><span class="countdown-txt-lg">%M</span><span class="countdown-txt-sm">Minutes</span></span>';
                dateFormat += '<span class="countdown-dots">:</span><span class="countdown-group"><span class="countdown-txt-lg">%S</span><span class="countdown-txt-sm">Seconds</span></span>';

                $(this).html(event.strftime(dateFormat));
            })
            .on('finish.countdown', function (event) {
                $(this).html('This offer has expired!').parent().addClass('disabled');
            });
    }

    // Circle progress bar
    if ($('.js-lp-progress').length) {
        var percent = $('.js-lp-progress').data('percent') * 1;
        $('.js-lp-progress-blured').circleProgress({
            value: percent,
            emptyFill: "rgba(255,255,255,.6)",
            size: 292,
            thickness: 12,
            startAngle: -Math.PI / 2,
            fill: {
                color: "#f92112"
            }
        });
        $('.js-lp-progress').circleProgress({
            value: percent,
            emptyFill: "rgba(255,255,255,.6)",
            size: 285,
            thickness: 6,
            startAngle: -Math.PI / 2,
            fill: {
                color: "#f92112"
            }
        });
    }

    // Hamburger
    $(document).on('click', '.hamburger', function () {
        $('.masthead__menu').slideToggle();
        $('.hamburger').toggleClass('is-active');
    });

    // Popups
    if ($('.js-popup-link').length) {
        $('.js-popup-link').magnificPopup({
            type: 'inline',
            midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
            mainClass: 'mfp-fade',
            fixedContentPos: false,
            callbacks: {
                open: function () {
                    $('body').addClass('noscroll');
                },
                close: function () {
                    $('body').removeClass('noscroll');
                }
            }
        });
    }

    // Scroll navigation
    $('.header-lp .navigation a, .scroll-button').on('click', function (e) {
        e.preventDefault();
        var target = this.hash;
        $target = $(target);
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 900, 'swing', function () {
            window.location.hash = target;
        });
    });

    // Datepicker
    if ($('[data-toggle="datepicker"]').length) {
        $('[data-toggle="datepicker"]').datepicker();
    }

    // Contact us
    $('#frm_contact').on('submit', function (event) {
        event.preventDefault();

        axios.post(
            '/mail/contact-us',
            qs.stringify(
                {
                    'name': $('#user-name').val(),
                    'email': $('#user-email').val(),
                    'message': $('#user-message').val()
                }
            )
        )

    });

    //Log in
    $("#frm_signin").on('submit', function (event) {
        event.preventDefault();

        showSpin(true);

        const credentials = {
            email: $('#signin_email').val(),
            password: $('#signin_pwd').val()
        };

        axios.post(
            '/auth/login',
            qs.stringify(credentials)
        )
            .then(
                () => {
                    showSpin(false);

                    $.magnificPopup.close();

                    window.location.href = '/profile';
                }
            )
            .catch(
                error => {
                    showSpin(false);

                    const {message, errors} = error.response.data;

                    showLoginErr(message, errors);
                }
            )
    });

    $('.fb_signin').on('click', function (event) {
        event.preventDefault();

        FB.getLoginStatus(
            response => {
                let credentials = {
                    id: 0,
                    email: '',
                };

                if (response.status === 'connected') {
                    FB.api(
                        '/me?fields=email',
                        response => fbLogin(response.id, response.email)
                    );
                } else {
                    FB.login(
                        () => {
                            FB.api(
                                '/me?fields=email',
                                response => fbLogin(response.id, response.email)
                            );
                        },
                        {
                            scope: 'email'
                        }
                    );
                }
            }
        );
    });

    $('.g_signin').on('click', function (event) {
        event.preventDefault();

        gapi.load(
            'client:auth2',
            () => {
                gapi.client.init({
                    'apiKey': 'AIzaSyDTxK1GiXU-EUONddFh2tlpPL_JcrJ3I2c',
                    'clientId': '837606368945-5gj2gfd2fsbgeh94qa1tec738vhiq1u7.apps.googleusercontent.com',
                    'scope': 'profile'
                }).then(function () {
                    console.log('Yo');
                });

            }
        );


    });

    // Log out
    $("#btn_logout").on('click', function (event) {

        showSpin(true);

        $.ajax({
            data: {
                submitStr: 'logoutSubmit'
            },
            method: "POST",
            url: "src/login/userAccount.php",
            success: function (response) {
                var result = JSON.parse(response);

                window.location.href = 'index.php';

                showSpin(false);
            }
        });

        //event.preventDefault();
    });

    //Sing up
    $("#frm_signup").on('submit', function (event) {
            event.preventDefault();

            showSpin(true);

            const credentials = {
                email: $('#signup_email').val(),
                password: $('#signup_pwd').val(),
                password_confirmation: $('#signup_cnf_pwd').val()
            };

            axios.post(
                '/auth/register',
                qs.stringify(credentials)
            )
                .then(
                    response => {
                        sendActivationEmail(response.data.uid);

                        showSpin(false);

                        $.magnificPopup.close();

                        $.magnificPopup.open(
                            {
                                items: {
                                    src: '#confirm-modal'
                                },
                                type: 'inline',
                                closeOnBgClick: false
                            }
                        );

                        $('#resend-registration-email').on('click', function (event) {
                            sendActivationEmail(response.data.uid)
                        });
                    }
                )
                .catch(
                    error => {
                        showSpin(false);

                        const {message, errors} = error.response.data;

                        showSignupErr(message, errors);
                    }
                )
        }
    );
});


