require('./bootstrap');

// Spinner
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
        $('.tabs-head li').removeClass('is-active');
        $('.tab-body').removeClass('is-active');
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
                    window.location.pathname = '/';
                }
            )
    });

    // Change user role
    $('select[name="user-role"]').on('change', function (event) {
        event.preventDefault();

        clearErrors();

        const userInfo = {
            'uid': $('#user-profile-id').val(),
            'role': $(this).val(),
        }

        axios.post(
            '/admin/profile',
            qs.stringify(userInfo)
        )
            .then(
                () => {
                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#save-profile-modal'
                            },
                            type: 'inline',
                            closeOnBgClick: true
                        }
                    );
                }
            )
            .catch(
                error => {
                    $('#user-profile select[name="user-role"]').parents('.form-group').addClass('form-error');

                    const {message} = error.response.data;

                    showError(message)
                }
            )
    });

    // Delete user
    $('#remove-user').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const userInfo = {
            'uid': $('#user-profile-id').val(),
        }

        axios.post(
            '/admin/profile/remove',
            qs.stringify(userInfo)
        )
            .then(
                () => {
                    hideSpinner(button);

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#remove-profile-modal'
                            },
                            type: 'inline',
                            closeOnBgClick: true,
                            callbacks: {
                                close: function() {
                                    window.location.pathname = '/admin/users'
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

                    showError(message)
                }
            )
    });

    // Approve documents
    $('.approve-documents').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const document = {
            'uid': $('#user-profile-id').val(),
            'type': $(this).parent().find('input[name="document-type"]').val(),
        }

        axios.post(
            'document/approve',
            qs.stringify(document)
        )
            .then(
                () => {
                    hideSpinner(button);

                    $(this).find('button[type="submit"]').remove();

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#approve-documents-modal'
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

                    showError(message)
                }
            )

    });

    // Decline documents
    $('.decline-documents').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const document = {
            'uid': $('#user-profile-id').val(),
            'type': $(this).parent().find('input[name="document-type"]').val(),
            'reason': $(this).parents('.row').find('input[name="decline-reason"]').val()
        }

        axios.post(
            'document/decline',
            qs.stringify(document)
        )
            .then(
                () => {
                    hideSpinner(button);

                    $(this).find('button[type="submit"]').remove();

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#decline-documents-modal'
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

                    showError(message)
                }
            )

    });

    // Filters
    $('input[name="search-by-email"]').on('keyup', function (event) {
        const filterText = $(this).val();

        const colToSearch = $(this).hasClass('wallets-search') ? 0 : 1;

        $('#users-list tbody tr').each(function (index, element) {
            const userEmail = $(element).find('td:eq(' + colToSearch + ')').text();

            if (filterText.trim().length !== 0 && userEmail.indexOf(filterText) === -1) {
                $(this).hide()
            } else {
                $(this).show();
            }
        });
    });

    $('.search-cross').on('click', function (event) {
        event.preventDefault();

        $('input[name="search-by-email"]').val('').trigger('keyup');
    });

    $('input[name="referrer-filter"]').on('change', function (event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            const hasReferrer = $(element).find('td:eq(5)').text().trim().length === 0 ? 0 : 1;

            if (refFilter == hasReferrer) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide()
                }
            }
        });
    });

    $('input[name="status-filter"]').on('change', function (event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            const userStatus = $(element).find('td:eq(4)').text().trim();

            if (refFilter == userStatus) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide()
                }
            }
        });
    });

    $('input[name="role-filter"]').on('change', function (event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            const userRole = $(element).find('td:eq(3)').text().trim();

            if (refFilter == userRole) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide()
                }
            }
        });
    });

    $('input[name="dc-filter"]').on('change', function (event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            const userRole = $(element).find('td:eq(1)').text().trim();

            if (refFilter == userRole) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide()
                }
            }
        });
    });

    // Add ZNX ammount
    $('#add-znx').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const user = {
            'uid': $('#user-profile-id').val(),
            'amount': $('input[name="znx-amount"]').val(),
        }

        axios.post(
            '/admin/wallet/znx',
            qs.stringify(user)
        )
            .then(
                response => {
                    hideSpinner(button);

                    $('input[name="znx-amount"]').val('');
                    $('#total-znx-amount').html(response.data.totalAmount);

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#add-znx-modal'
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

                    $('input[name="znx-amount"]').parent().addClass('form-error');

                    const {message} = error.response.data;

                    showError(message)
                }
            )
    });

    // Update Wallet address
    $('.update-wallet').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const wallet = {
            'uid': $('#user-profile-id').val(),
            'currency': $(this).parents('.wallet-address-group').find('input[name="wallet-currency"]').val(),
            'address': $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').val(),
        }

        axios.post(
            '/admin/wallet',
            qs.stringify(wallet)
        )
            .then(
                () => {
                    hideSpinner(button);

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

                    $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').parent().addClass('form-error');

                    const {message} = error.response.data;

                    showError(message)
                }
            )
    });

});


