require('./bootstrap');

//Spin function
showSpin = visible => {
    if (visible)
        $('#spin').show();
    else
        $('#spin').hide();
}

$(document).ready(function () {
    // Datepicker
    if ($('[data-toggle="datepicker"]').length) {
        $('[data-toggle="datepicker"]').datepicker();
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

                    alert('Saved');
                }
            )

    });


});


