require('./bootstrap');

const getSpinner = size => {

    return $('<div />').addClass('spinner spinner--' + size)
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
        );
}

const showSpinner = element => {
    element.addClass('is-loading').prop('disabled', true);
    element.append(getSpinner(30));
}

const hideSpinner = (element) => {
    element.removeClass('is-loading').prop('disabled', false);
    element.find('.spinner').remove();
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

    // Tabs
    $(document).on('click', '.tabs-head a', function (e) {
        e.preventDefault();
        var thisHref = $(this).attr('href');
        $('.tabs-head li').removeClass('is-active');
        $('.tab-body').removeClass('is-active');
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
                    // $.magnificPopup.close();

                    window.location.pathname = '/';
                }
            )
    });

    // Profile
    $('#save-profile').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

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
                () => {
                    hideSpinner(button);

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

                    } else {
                        showError(message);
                    }

                }
            )
    });

    // Profile Settings
    $('.remove-document').on('click', function (event) {
        event.preventDefault();

        const file = {
            'did': $(this).parents('li').attr('id'),
        }

        axios.post(
            '/user/profile-settings/remove-document',
            qs.stringify(file)
        )
            .then(
                () => {
                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#remove-document-modal'
                            },
                            type: 'inline',
                            closeOnBgClick: true,
                            callbacks: {
                                close: function () {
                                    window.location.reload();
                                }
                            }

                        }
                    );
                }
            )
            .catch(
                error => {
                    const {message} = error.response.data;

                    showError(message);
                }
            )
    });

    $('#upload-identity-documents').on('submit', function (event) {
        event.preventDefault();

        let documents = new FormData();

        let isFilesValid = true;

        $.each(
            $('#document-files')[0].files,
            (index, file) => {
                if (!validateFile(file)) {
                    isFilesValid = false;

                    return false;
                }

                documents.append('document_files[]', file);
            }
        )

        if (!isFilesValid) {
            showError('Incorrect files format.');

            return false;
        }

        const button = $('#upload-identity-documents').find('button[type="submit"]');
        showSpinner(button);

        axios.post(
            '/user/profile-settings/upload-identity-documents',
            documents
        )
            .then(
                () => {
                    hideSpinner(button);

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#upload-documents-modal'
                            },
                            type: 'inline',
                            closeOnBgClick: true,
                            callbacks: {
                                close: function () {
                                    window.location.reload();
                                }
                            }

                        }
                    );

                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {message} = error.response.data;

                    showError(message);
                }
            )

    });

    $('#upload-address-documents').on('submit', function (event) {
        event.preventDefault();
        clearErrors();

        let documents = new FormData();

        let isFilesValid = true;

        $.each(
            $('#address-files')[0].files,
            (index, file) => {
                if (!validateFile(file)) {
                    isFilesValid = false;

                    return false;
                }

                documents.append('address_files[]', file);
            }
        )

        if (!isFilesValid) {
            $('.drag-drop-area').after(
                $('<div />').addClass('error-text').text('Incorrect files format.')
            );

            return false;
        }

        const button = $('#upload-identity-documents').find('button[type="submit"]');
        showSpinner(button);

        axios.post(
            '/user/profile-settings/upload-address-documents',
            documents
        )
            .then(
                () => {
                    hideSpinner(button);

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#upload-documents-modal'
                            },
                            type: 'inline',
                            closeOnBgClick: true,
                            callbacks: {
                                close: function () {
                                    window.location.reload();
                                }
                            }

                        }
                    );

                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {message, errors} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('.drag-drop-area').after(
                                    $('<div />').addClass('error-text').text(error)
                                );
                            }
                        )

                    } else {
                        showError(message);
                    }
                }
            )

    });

    $('#change-password').on('submit', function (event) {
        event.preventDefault();

        const button = $(this).find('input[type="submit"]');
        showSpinner(button);
        clearErrors();

        const password = {
            'current-password': $(this).find('input[name="current-password"]').val(),
            'password': $(this).find('input[name="password"]').val(),
            'password_confirmation': $(this).find('input[name="confirm-password"]').val(),
        }


        axios.post(
            '/user/profile-settings/change-password',
            qs.stringify(password)
        )
            .then(
                () => {
                    hideSpinner(button);

                    $('#change-password input[type="password"]').val('');

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#change-password-modal'
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

                    const {errors, message} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('#change-password input[name="' + field + '"]').parent().addClass('form-error');
                                $('#change-password input[name="' + field + '"]').after(
                                    $('<span />').addClass('error-text').text(error)
                                );
                            }
                        )

                    } else {
                        showError(message)
                    }
                }
            )
    });

    // States update
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

        const button = $('#invite-friend');
        showSpinner(button);
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
                    hideSpinner(button);

                    $('input[name="email"]').val('');

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
                    hideSpinner(button);

                    const {message, errors} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('#friend-email').parent().addClass('form-error');
                                $('#friend-email').after(
                                    $('<span />').addClass('error-text').text(error)
                                );
                            }
                        )

                    } else {
                        showError(message);
                    }
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

    // Wallet
    $('.wallet').on('click', '#copy-address',function (e) {
        e.preventDefault();

        const address = $(this).parents('.wallet').find('.address').text();

        let tmpEl = $('<input />').val(address);

        $('body').append(tmpEl);

        tmpEl.select();

        document.execCommand("copy");

        tmpEl.remove();
    })

    $('.create-address').on('click', function (event) {
        event.preventDefault();


        const button = $(this);
        showSpinner(button);
        clearErrors();

        axios.post(
            '/user/wallet/address',
            qs.stringify({})
        )
            .then(
                response => {
                    hideSpinner(button);

                    console.log(response);

                    const wrapper = button.parent();

                    wrapper
                        .before(
                            $('<div />')
                                .addClass('col col-sm-auto text-lg wordwrap address')
                                .text(response.data.address)
                        )
                        .before(
                            $('<div />')
                                .addClass('col col-md-3')
                                .append(
                                    $('<a />')
                                        .addClass('btn btn--shadowed-light btn--medium btn--130 mt-sm-15')
                                        .attr(
                                            {
                                                id: 'copy-address',
                                                href: ''
                                            }
                                        )
                                        .text('Copy')
                                )
                        );

                    wrapper.remove();

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#wallet-address-modal'
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

                    const {message} = error.response.data;

                    if (error.response.status == 422) {
                        $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').parent().addClass('form-error');
                    } else {
                        showError(message)
                    }
                }
            )
    });

    // Debit Card
    $('#dc_design').on('submit', function (event) {
        event.preventDefault();

        const button = $('#dc_design').find('button[type="submit"]');
        showSpinner(button);

        const card = {
            'design': $('input[name="card-type"]:checked').val()
        }

        axios.post(
            '/user/debit-card',
            qs.stringify(card)
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

                    const {message} = error.response.data;

                    showError(message);
                }
            )
    });

    $('#dc_documents').on('submit', function (event) {
        event.preventDefault();
        clearErrors();

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
                $('.drag-drop-area').after(
                    $('<div />').addClass('error-text').text('Incorrect files format.')
                );

                return false;
            }
        }

        const button = $('#dc_documents').find('button[type="submit"]');
        showSpinner(button);

        axios.post(
            '/user/debit-card-documents',
            card
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

                    const {message, errors} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('.drag-drop-area').after(
                                    $('<div />').addClass('error-text').text(error)
                                );
                            }
                        )

                    } else {
                        showError(message);
                    }

                }
            )

    });

    $('#dc_address').on('submit', function (event) {
        event.preventDefault();
        clearErrors();

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
                $('.drag-drop-area').after(
                    $('<div />').addClass('error-text').text('Incorrect files format.')
                );

                return false;
            }
        }

        const button = $('#dc_address').find('button[type="submit"]');
        showSpinner(button);

        axios.post(
            '/user/debit-card-address',
            card
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

                    const {message, errors} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('.drag-drop-area').after(
                                    $('<div />').addClass('error-text').text(error)
                                );
                            }
                        )

                    } else {
                        showError(message);
                    }
                }
            )

    });

});


