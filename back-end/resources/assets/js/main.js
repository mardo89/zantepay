require('./bootstrap');

// Spinner
const getSpinner = size => {

    return $('<div />').addClass('spinner-container').css('height', size + 'px')
        .append(
            $('<div />').addClass('spinner spinner--50')
                .append(
                    $('<div />')
                )
                .append(
                    $('<div />')
                )
                .append(
                    $('<div />')
                )
                .append(
                    $('<div />')
                )
        );
}

const showSpinner = (element, size) => {
    element.hide();
    element.after(getSpinner(size));
}

const hideSpinner = (element) => {
    element.show();
    $('.spinner-container').remove();
}

// Send activation email
const sendActivationEmail = uid => {
    axios.post(
        '/mail/activate-account',
        qs.stringify(
            {
                uid
            }
        )
    )
}

// Errors
const clearErrors = () => {
    $('.form-error').removeClass('form-error');
    $('.error-text').remove();
}

// Forms
const clearForm = form => {
    form.find('input[type="text"]').val('');
    form.find('input[type="email"]').val('');
    form.find('input[type="password"]').val('');
    form.find('textarea').val('');
    form.find('input[type="radio"]:eq(0)').prop('checked', true);

    clearErrors();
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
                },
                elementParse: function (item) {
                    $(item.src).find('form').each(
                        (index, element) => clearForm($(element))
                    );
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

    //hp shapes
    if ($('#particles-js').length) {
        var lineColor = $('.particles-js-black').length ? '#000' : '#fff';

        particlesJS(
            'particles-js',
            {
                "particles": {
                    "number": {
                        "value": 80,
                        "density": {
                            "enable": true,
                            "value_area": 800
                        }
                    },
                    "color": {
                        "value": "#f92112"
                    },
                    "shape": {
                        "type": "circle",
                        "stroke": {
                            "width": 0,
                            "color": "#000000"
                        },
                        "polygon": {
                            "nb_sides": 5
                        },
                        "image": {
                            "src": "img/github.svg",
                            "width": 100,
                            "height": 100
                        }
                    },
                    "opacity": {
                        "value": 0.7,
                        "random": false,
                        "anim": {
                            "enable": false,
                            "speed": 3,
                            "opacity_min": 0.2,
                            "sync": false
                        }
                    },
                    "size": {
                        "value": 5,
                        "random": true,
                        "anim": {
                            "enable": false,
                            "speed": 6,
                            "size_min": 0.1,
                            "sync": false
                        }
                    },
                    "line_linked": {
                        "enable": true,
                        "distance": 150,
                        "color": lineColor,
                        "opacity": 0.5,
                        "width": 1
                    },
                    "move": {
                        "enable": true,
                        "speed": 4,
                        "direction": "none",
                        "random": false,
                        "straight": false,
                        "out_mode": "out",
                        "attract": {
                            "enable": false,
                            "rotateX": 600,
                            "rotateY": 1200
                        }
                    }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": {
                        "onhover": {
                            "enable": false,
                        },
                        "onclick": {
                            "enable": false,
                        },
                        "resize": true
                    },
                    "modes": {
                        "grab": {
                            "distance": 400,
                            "line_linked": {
                                "opacity": 1
                            }
                        },
                        "bubble": {
                            "distance": 400,
                            "size": 40,
                            "duration": 2,
                            "opacity": 8,
                            "speed": 5
                        },
                        "repulse": {
                            "distance": 200
                        },
                        "push": {
                            "particles_nb": 4
                        },
                        "remove": {
                            "particles_nb": 2
                        }
                    }
                },
                "retina_detect": true,
                "config_demo": {
                    "hide_card": false,
                    "background_color": "#b61924",
                    "background_image": "",
                    "background_position": "50% 50%",
                    "background_repeat": "no-repeat",
                    "background_size": "cover"
                }
            }
        );
    }

    // Contact us
    $('#frm_contact').on('submit', function (event) {
        event.preventDefault();

        const button = $('#frm_contact').find('input[type="submit"]');
        showSpinner(button, 50);
        clearErrors();

        axios.post(
            '/mail/contact-us',
            qs.stringify(
                {
                    'name': $('#contact-name').val(),
                    'email': $('#contact-email').val(),
                    'message': $('#contact-message').val()
                }
            )
        )
            .then(
                () => {
                    hideSpinner(button);
                    clearForm($('#frm_contact'));

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#confirm-contact-us'
                            },
                            type: 'inline',
                            closeOnBgClick: true
                        }
                    );

                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {errors} = error.response.data;

                    $.each(
                        errors,
                        (field, error) => {
                            $('#contact-' + field).parents('.form-group').addClass('form-error');
                            $('#contact-' + field).after(
                                $('<span />').addClass('error-text').text(error)
                            );

                        }
                    )
                }
            )
    });

    //Log in
    $('#frm_signin').on('submit', function (event) {
        event.preventDefault();

        const button = $('#frm_signin').find('input[type="submit"]');
        showSpinner(button, 50);
        clearErrors();

        const credentials = {
            email: $('#frm_signin input[name="email"]').val(),
            password: $('#frm_signin input[name="password"]').val()
        };

        axios.post(
            '/account/login',
            qs.stringify(credentials)
        )
            .then(
                response => {
                    hideSpinner(button);

                    $.magnificPopup.close();

                    window.location.pathname = response.data.userPage;
                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {errors} = error.response.data;

                    $.each(
                        errors,
                        (field, error) => {
                            $('#frm_signin input[name="' + field + '"]').parent().addClass('form-error');
                            $('#frm_signin input[name="' + field + '"]').after(
                                $('<span />').addClass('error-text').text(error)
                            );
                        }
                    )

                }
            )
    });

    //Sing up
    $('#frm_signup').on('submit', function (event) {
        event.preventDefault();

        const button = $('#frm_signup').find('input[type="submit"]');
        showSpinner(button, 50);
        clearErrors();

        const credentials = {
            email: $('#frm_signup input[name="email"]').val(),
            password: $('#frm_signup input[name="password"]').val(),
            password_confirmation: $('#frm_signup input[name="confirm-password"]').val()
        };

        axios.post(
            '/account/register',
            qs.stringify(credentials)
        )
            .then(
                response => {
                    hideSpinner(button);

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
                        event.preventDefault();

                        sendActivationEmail(response.data.uid)
                    });
                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {errors} = error.response.data;

                    $.each(
                        errors,
                        (field, error) => {
                            $('#frm_signup input[name="' + field + '"]').parent().addClass('form-error');
                            $('#frm_signup input[name="' + field + '"]').after(
                                $('<span />').addClass('error-text').text(error)
                            );
                        }
                    )
                }
            )
    });

    //Sing up via invite
    $("#frm_invite_signup").on('submit', function (event) {
        event.preventDefault();

        const button = $('#frm_invite_signup').find('input[type="submit"]');
        showSpinner(button, 50);
        clearErrors();

        const credentials = {
            email: $('#frm_invite_signup input[name="email"]').val(),
            password: $('#frm_invite_signup input[name="password"]').val(),
            password_confirmation: $('#frm_invite_signup input[name="confirm-password"]').val()
        };

        axios.post(
            '/account/register',
            qs.stringify(credentials)
        )
            .then(
                response => {
                    hideSpinner(button);

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

                    $('.mfp-close').on('click', function (event) {
                        window.location = '/';
                    });

                    $('#resend-registration-email').on('click', function (event) {
                        event.preventDefault();

                        sendActivationEmail(response.data.uid)
                    });
                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {errors} = error.response.data;

                    $.each(
                        errors,
                        (field, error) => {
                            $('#frm_invite_signup input[name="' + field + '"]').parent().addClass('form-error');
                            $('#frm_invite_signup input[name="' + field + '"]').after(
                                $('<span />').addClass('error-text').text(error)
                            );
                        }
                    )
                }
            )
    });

    //Forgot password
    $('#frm_forgot_password').on('submit', function (event) {
        event.preventDefault();

        const button = $('#frm_forgot').find('input[type="submit"]');
        showSpinner(button, 50);
        clearErrors();

        const credentials = {
            email: $('#frm_forgot_password input[name="email"]').val(),
        };

        axios.post(
            '/account/reset-password',
            qs.stringify(credentials)
        )
            .then(
                () => {
                    hideSpinner(button);

                    $.magnificPopup.close();

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#reset-confirm-modal'
                            },
                            type: 'inline',
                            closeOnBgClick: false
                        }
                    );
                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {errors} = error.response.data;

                    $.each(
                        errors,
                        (field, error) => {
                            $('#frm_forgot_password input[name="' + field + '"]').parent().addClass('form-error');
                            $('#frm_forgot_password input[name="' + field + '"]').after(
                                $('<span />').addClass('error-text').text(error)
                            );
                        }
                    )
                }
            )
    });

    // Change password
    $('#frm_change_password').on('submit', function (event) {
        event.preventDefault();

        const button = $('#frm_change_password').find('input[type="submit"]');
        showSpinner(button, 50);
        clearErrors();

        const credentials = {
            token: $('#frm_change_password input[name="reset-token"]').val(),
            password: $('#frm_change_password input[name="password"]').val(),
            password_confirmation: $('#frm_change_password input[name="confirm-password"]').val()
        };

        axios.post(
            '/account/save-password',
            qs.stringify(credentials)
        )
            .then(
                response => {
                    hideSpinner(button);

                    window.location = response.data.nextStep
                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {errors} = error.response.data;

                    $.each(
                        errors,
                        (field, error) => {
                            $('#frm_change_password input[name="' + field + '"]').parent().addClass('form-error');
                            $('#frm_change_password input[name="' + field + '"]').after(
                                $('<span />').addClass('error-text').text(error)
                            );
                        }
                    )
                }
            )

    });

    // PRE-ICO registration
    $('#frm_ico_registration').on('submit', function (event) {
        event.preventDefault();

        const button = $('#frm_ico_registration').find('input[type="submit"]');
        showSpinner(button, 50);
        clearErrors();

        const registration = {
            'email': $('#frm_ico_registration input[name="email"]').val(),
            'currency': $('#frm_ico_registration input[name="pay-method"]:checked').val(),
            'amount': +$('#frm_ico_registration input[name="amount"]').val()
        }

        axios.post(
            '/ico-registration',
            qs.stringify(registration)
        )
            .then(
                response => {
                    hideSpinner(button);

                    $.magnificPopup.close();

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#confirm-sign-up-preico'
                            },
                            type: 'inline',
                            closeOnBgClick: true
                        }
                    );
                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {errors} = error.response.data;

                    $.each(
                        errors,
                        (field, error) => {
                            $('#frm_ico_registration input[name="' + field + '"]').parent().addClass('form-error');
                            $('#frm_ico_registration input[name="' + field + '"]').after(
                                $('<span />').addClass('error-text').text(error)
                            );
                        }
                    )
                }
            )

    });

    // Become investor
    $('#frm_investor').on('submit', function (event) {
        event.preventDefault();

        const button = $('#frm_investor').find('input[type="submit"]');
        showSpinner(button, 50);
        clearErrors();

        const registration = {
            'email': $('#frm_investor input[name="email"]').val(),
            'first-name': $('#frm_investor input[name="first-name"]').val(),
            'last-name': $('#frm_investor input[name="last-name"]').val(),
            'skype-id': $('#frm_investor input[name="skype-id"]').val(),
        }

        axios.post(
            '/seed-investor',
            qs.stringify(registration)
        )
            .then(
                () => {
                    hideSpinner(button);

                    $.magnificPopup.close();

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#confirm-seed-investors'
                            },
                            type: 'inline',
                            closeOnBgClick: true
                        }
                    );
                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {errors} = error.response.data;

                    $.each(
                        errors,
                        (field, error) => {
                            $('#frm_investor input[name="' + field + '"]').parent().addClass('form-error');
                            $('#frm_investor input[name="' + field + '"]').after(
                                $('<span />').addClass('error-text').text(error)
                            );
                        }
                    )
                }
            )

    });
});


