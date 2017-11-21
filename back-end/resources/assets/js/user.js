require('./bootstrap');

//Spin function
const showSpin = visible => {
    if (visible)
        $('#spin').show();
    else
        $('#spin').hide();
}

const updateStates = country => {
    axios.get(
        '/user/states',
        {
            params: {
                country
            }
        }
    )
        .then(
            response => {

                $('select[name="state"]').html(
                    response.data.map(
                        state => $('<option />').val(state.id).text(state.name)
                    )
                )

            }
        )

}

$(document).ready(function () {
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

    // Logout
    $('#logout-btn').on('click', function (event) {
        event.preventDefault();

        showSpin(true);

        axios.post(
            '/auth/logout',
            qs.stringify(
                {}
            )
        )
            .then(
                () => {
                    showSpin(false);

                    $.magnificPopup.close();

                    window.location.pathname = '/';
                }
            )
    });

    // Profile
    $('#user-profile').on('submit', function (event) {
        event.preventDefault();

        showSpin(true);

        const profile = {
            'first_name': $('input[name="f-name"]').val(),
            'last_name': $('input[name="l-name"]').val(),
            'email': $('input[name="email"]').val(),
            'phone_number': $('input[name="tel"]').val(),
            'country': $('select[name="country"]').val(),
            'state': $('select[name="state"]').val(),
            'city': $('input[name="city"]').val(),
            'address': $('input[name="address"]').val(),
            'postcode': $('input[name="postcode"]').val(),
            'passport': $('input[name="government"]').val(),
            'expiration_date': $('input[name="expiry"]').val(),
            'birth_date': $('input[name="birth"]').val(),
            'birth_country': $('input[name="country-birth"]').val(),
        }

        axios.post(
            '/user/profile',
            qs.stringify(profile)
        )
            .then(
                response => {
                    showSpin(false);

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#profile-modal'
                            },
                            type: 'inline',
                            closeOnBgClick: false
                        }
                    );
                }
            )

    });

    // States update
    $('select[name="country"]').on('change', function (event) {
        updateStates($(this).val());
    });

});


