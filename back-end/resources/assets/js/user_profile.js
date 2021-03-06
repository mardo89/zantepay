require('./helpers');

$(document).ready(function () {

    $('select[name="country"]').on('change', function (event) {
        const country = $(this).val();

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
                        response.data.states.map(
                            state => $('<option />').val(state.id).text(state.name).attr('selected', (state.id == 0 ? 'selected' : ''))
                        )
                    )

                    $('select[name="area-code"]').html(
                        response.data.codes.map(
                            areaCode => $('<option />').val(areaCode.id).text(areaCode.code).attr('selected', (areaCode.id == 0 ? 'selected' : ''))
                        )
                    )

                }
            )
            .catch(
                () => {

                    $('select[name="state"]').html(
                        $('<option />').val(0).text('Other state').attr('selected', 'selected')
                    )

                    $('select[name="area-code"]').html(
                        $('<option />').val(0).text('Other code').attr('selected', 'selected')
                    )

                }
            )
    });

    $('#save-profile').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const profile = processProtectionRequest(
            'Save Profile',
            {
                'first_name': $('input[name="f-name"]').val(),
                'last_name': $('input[name="l-name"]').val(),
                'email': $('input[name="email"]').val(),
                'phone_number': $('input[name="tel"]').val(),
                'area_code': $('select[name="area-code"]').val(),
                'country': $('select[name="country"]').val(),
                'state': $('select[name="state"]').val(),
                'city': $('input[name="city"]').val(),
                'address': $('input[name="address"]').val(),
                'postcode': $('input[name="post-code"]').val(),
                'passport': $('input[name="government"]').val(),
                'expiration_date': $('input[name="expiry"]').val(),
                'birth_date': $('input[name="birth"]').val(),
                'birth_country': $('select[name="country-birth"]').val(),
            }
        );

        axios.post(
            '/user/profile',
            qs.stringify(profile)
        )
            .then(
                response => {

                    hideSpinner(button);

                    processProtectionResponse(
                        response.status,
                        () => {
                            $(this).trigger('click');
                        },
                        () => {
                            $.magnificPopup.open(
                                {
                                    items: {
                                        src: '#profile-modal'
                                    },
                                    type: 'inline',
                                    closeOnBgClick: true
                                }
                            );
                        }
                    );

                }

            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {errors, message} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('.profile_' + field).addClass('form-error');
                                $('.profile_' + field).after(
                                    $('<span />').addClass('error-text').text(error)
                                );
                            }
                        )

                        scrollToError();

                    } else {
                        showError(message);
                    }

                }
            )
    });

    $('#frm_close_account').on('submit', function (event) {
        event.preventDefault();

        const confirmed = $(this).find('input[name="close-account-confirm"]').prop('checked');

        if (!confirmed) {
            showError('You have to confirm the closing of account by clicking the checkbox.');
            return false;
        }

        const credentials = processProtectionRequest(
            'Close Account',
            {}
        );

        const button = $(this);
        showSpinner(button);
        clearErrors();

        axios.post(
            '/user/close-account',
            qs.stringify(credentials)
        )
            .then(
                response => {

                    hideSpinner(button);

                    processProtectionResponse(
                        response.status,
                        () => {
                            $(this).trigger('submit');
                        },
                        () => {
                            $.magnificPopup.open(
                                {
                                    items: {
                                        src: '#close-account-modal'
                                    },
                                    type: 'inline',
                                    closeOnBgClick: true,
                                    callbacks: {
                                        close: function close() {
                                            window.location.reload();
                                        }
                                    }
                                }
                            );
                        }
                    );

                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {message} = error.response.data;

                    if (error.response.status == 422) {

                        $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').parent().addClass('form-error');

                        scrollToError();

                    } else {
                        showError(message)
                    }
                }
            )
    });

});


