require('./bootstrap');

$(document).ready(function () {
    //hamburger
    $(document).on('click', '.hamburger', function () {
        $('.masthead__menu').slideToggle();
        $('.hamburger').toggleClass('is-active');
    });

    // Datepicker
    if ($('[data-toggle="datepicker"]').length) {
        $('[data-toggle="datepicker"]').datepicker();
    }

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

    // Tabs
    $(document).on('click', '.tabs-head a', function (e) {
        e.preventDefault();
        var thisHref = $(this).attr('href');
        $(this).closest('.tabs-head').find('li').removeClass('is-active');
        $(thisHref).closest('.tabs-wrap').find('.tab-body').removeClass('is-active');
        $(this).parent().addClass('is-active');
        $(thisHref).addClass('is-active');
        if (thisHref != '#profile') {
            $('.dashboard-top-panel-row .form-group').hide();
        } else {
            $('.dashboard-top-panel-row .form-group').show();
        }
    });

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

    // Logout
    $('#logout-btn').on('click', function (event) {
        event.preventDefault();

        axios.post(
            '/account/logout',
            qs.stringify(
                {}
            )
        )
            .then(
                () => {
                    window.location = '/';
                }
            )
    });

    // Copy link
    $('#copy-link').on('click', function () {
        const refLink = $('input[name="referral"]').val();

        let tmpEl = $('<input />').val(refLink);

        $('body').append(tmpEl);

        tmpEl.select();

        document.execCommand("copy");

        tmpEl.remove();
    })

});


