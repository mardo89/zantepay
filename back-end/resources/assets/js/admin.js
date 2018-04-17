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
            midClick: true,
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
    $(document).on('click', '.tabs-head a', function(e) {
        e.preventDefault();
        var thisHref = $(this).attr('href');
        $(this).closest('.tabs-head').find('li').removeClass('is-active');
        $(thisHref).closest('.tabs-wrap').children('.tab-body').removeClass('is-active');
        $(this).parent().addClass('is-active');
        $(thisHref).addClass('is-active');
        if ( thisHref != '#profile') {
            $('.dashboard-top-panel-row .form-group').hide();
        } else {
            $('.dashboard-top-panel-row .form-group').show();
        }
    });

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
            .catch(
                error => {
                    const {message} = error.response.data;

                    showError(message)
                }
            )
    });

});


