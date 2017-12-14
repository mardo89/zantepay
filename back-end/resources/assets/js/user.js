require('./bootstrap');

// Spinner
const getSpinner = size => {

    return $('<div />').addClass('spinner-container').css('height', size + 'px').css('width', '160px')
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

// Errors
const clearErrors = () => {
    $('.form-error').removeClass('form-error');
    $('.error-text').remove();
}

const showError = errorMessage => {
    $.magnificPopup.open(
        {
            items: {
                src: '#error-modal'
            },
            type: 'inline',
            closeOnBgClick: true,
            callbacks: {
                elementParse: function (item) {
                    $(item.src).find('#error-message').text(errorMessage);
                }
            }
        }
    );
}

// Validate file
const validateFile = file => {
    const isValidType = file.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i);
    const isValidSize = (file.size).toFixed(0) < 4194304;

    if (isValidType && isValidSize) {
        return true;
    }

    return false;
}

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

    // Logout
    $('#logout-btn').on('click', function (event) {
        event.preventDefault();

        axios.post(
            '/auth/logout',
            qs.stringify(
                {}
            )
        )
            .then(
                () => {
                    // $.magnificPopup.close();

                    window.location.pathname = '/';
                }
            )
    });

    // Profile
    $('#user-profile').on('submit', function (event) {
        event.preventDefault();

        showSpinner($('#user-profile').find('button[type="submit"]'), 38);

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
                    hideSpinner($('#user-profile').find('button[type="submit"]'));

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
            )
            .catch(
                error => {
                    hideSpinner($('#user-profile').find('button[type="submit"]'));

                    const {errors, message} = error.response.data;

                    $.each(
                        errors,
                        (field, error) => {
                            $('.profile_' + field).addClass('form-error');
                        }
                    )

                    showError(message);
                }
            )

    });

    // States update
    $('select[name="country"]').on('change', function (event) {
        const country = $(this).val();

        axios.get(
            '/states',
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
                            state => $('<option />').val(state.id).text(state.name).attr('selected', (state.id == 0 ? 'selected' : ''))
                        )
                    )

                }
            )
            .catch(
                () => {
                    $('select[name="state"]').html(
                        $('<option />').val(0).text('Other state').attr('selected', 'selected')
                    )
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

    // Invite
    $('#invite-friend').on('click', function (event) {
        event.preventDefault();

        showSpinner($('#invite-friend'), 50);
        clearErrors();

        const invite = {
            'email': $('#friend-email').val()
        }

        axios.post(
            '/user/invite-friend',
            qs.stringify(invite)
        )
            .then(
                response => {
                    hideSpinner($('#invite-friend'));

                    $('input[name="email"]').val('');

                    sendInvitationEmail(response.data.email);

                    $('#invites-list tbody')
                        .prepend(
                            $('<tr />')
                                .append(
                                    $('<td />').css('width', '100').addClass('col-center')
                                        .append(
                                            $('<div />').addClass('thumb-60')
                                                .append(
                                                    $('<img />')
                                                        .attr('src', '/images/avatar.png')
                                                        .attr('alt', response.data.email)
                                                )
                                        )
                                )
                                .append(
                                    $('<td />').text(response.data.email)
                                )
                                .append(
                                    $('<td />')
                                        .append(
                                            $('<span />')
                                                .addClass('primary-color')
                                                .text(response.data.status)
                                        )
                                )
                                .append(
                                    $('<td />').text('')
                                )
                                .append(
                                    $('<td />').css('width', '160').addClass('col-center')
                                        .append(
                                            $('<a />')
                                                .attr('href', '')
                                                .addClass('send-link resend-invitation')
                                                .text('Resend')
                                        )
                                )
                        )
                }
            )
            .catch(
                error => {
                    hideSpinner($('#invite-friend'));

                    $('#friend-email').parent().addClass('form-error');

                    const {message} = error.response.data;

                    showError(message);
                }
            )
    });

    $('#invites-list').on('click', '.resend-invitation', function (event) {
        event.preventDefault();

        const email = $(this).parents('tr').find('td:eq(1)').text();

        axios.post(
            '/mail/invite-friend',
            qs.stringify(
                {
                    email
                }
            )
        )
            .catch(
                error => {

                    const {message} = error.response.data;

                    showError(message);
                }
            )
    });

    // Debit Card
    $('#dc_design').on('submit', function (event) {
        event.preventDefault();

        showSpinner($('#dc_design').find('input[type="submit"]'), 38);

        const card = {
            'design': $('input[name="card-type"]:checked').val()
        }

        axios.post(
            '/user/debit-card',
            qs.stringify(card)
        )
            .then(
                response => {
                    hideSpinner($('#dc_design').find('input[type="submit"]'));

                    window.location = response.data.nextStep
                }
            )
            .catch(
                error => {
                    hideSpinner($('#dc_design').find('input[type="submit"]'));

                    const {message} = error.response.data;

                    showError(message);
                }
            )
    });

    $('#dc_documents').on('submit', function (event) {
        event.preventDefault();

        let card = new FormData();

        if ($('input[name="confirm"]').prop('checked')) {
            card.append('verify_later', 1);
        } else {
            card.append('verify_later', 0);

            let isFilesValid = true;

            $.each(
                $('#document-files')[0].files,
                (index, file) => {
                    if (!validateFile(file)) {
                        isFilesValid = false;

                        return false;
                    }

                    card.append('document_files[]', file);
                }
            )

            if (!isFilesValid) {
                showError('Incorrect files format.');

                return false;
            }
        }

        showSpinner($('#dc_documents').find('input[type="submit"]'), 38);

        axios.post(
            '/user/debit-card-documents',
            card
        )
            .then(
                response => {
                    hideSpinner($('#dc_documents').find('input[type="submit"]'));

                    window.location = response.data.nextStep
                }
            )
            .catch(
                error => {
                    hideSpinner($('#dc_documents').find('input[type="submit"]'));

                    const {message} = error.response.data;

                    showError(message);
                }
            )

    });

    $('#dc_address').on('submit', function (event) {
        event.preventDefault();

        let card = new FormData();

        if ($('input[name="confirm"]').prop('checked')) {
            card.append('verify_later', 1);
        } else {
            card.append('verify_later', 0);

            let isFilesValid = true;

            $.each(
                $('#address-files')[0].files,
                (index, file) => {
                    if (!validateFile(file)) {
                        isFilesValid = false;

                        return false;
                    }

                    card.append('address_files[]', file);
                }
            )

            if (!isFilesValid) {
                showError('Incorrect files format.');

                return false;
            }
        }

        showSpinner($('#dc_address').find('input[type="submit"]'), 38);

        axios.post(
            '/user/debit-card-address',
            card
        )
            .then(
                response => {
                    hideSpinner($('#dc_address').find('input[type="submit"]'));

                    window.location = response.data.nextStep
                }
            )
            .catch(
                error => {
                    hideSpinner($('#dc_address').find('input[type="submit"]'));

                    const {message} = error.response.data;

                    showError(message);
                }
            )

    });

});


