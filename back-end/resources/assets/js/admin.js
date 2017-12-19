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
                    window.location.pathname = '/';
                }
            )
    });

    // Change role
    $('#user-profile').on('submit', function (event) {
        event.preventDefault();

        const button = $('#user-profile').find('button[type="submit"]');
        showSpinner(button);
        clearErrors();

        const user = {
            'uid': $('#user-profile-id').val(),
            'role': $('#user-profile select[name="role"]').val(),
        }

        axios.post(
            '/admin/profile',
            qs.stringify(user)
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

                    $('#user-profile select[name="role"]').parents('.form-group').addClass('form-error');

                    const {message} = error.response.data;

                    showError(message)
                }
            )

    });

    // Approve document
    $('.user-documents').on('submit', function (event) {
        event.preventDefault();

        const button = $(this).find('button[type="submit"]');
        showSpinner(button);
        clearErrors();

        const document = {
            'uid': $('#user-profile-id').val(),
            'type': $(this).find('input[name="document-type"]').val(),
        }

        axios.post(
            '/admin/document',
            qs.stringify(document)
        )
            .then(
                () => {
                    hideSpinner(button);

                    $(this).find('button[type="submit"]').remove();

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#document-modal'
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

    // Update ZNX wallet
    $('#user-wallet').on('submit', function (event) {
        event.preventDefault();

        const button = $('#user-profile').find('button[type="submit"]');
        showSpinner(button);
        clearErrors();

        const user = {
            'uid': $('#user-profile-id').val(),
            'amount': $('input[name="znx-amount"]').val(),
        }

        axios.post(
            '/admin/wallet',
            qs.stringify(user)
        )
            .then(
                response => {
                    hideSpinner(button);

                    $('input[name="znx-amount"]').val('');

                    $('#wallet-transactions tbody')
                        .prepend(
                            $('<tr />')
                                .append(
                                    $('<td>').html(response.data.date)
                                )
                                .append(
                                    $('<td>').html(response.data.currency)
                                )
                                .append(
                                    $('<td>').html(response.data.amount)
                                )
                                .append(
                                    $('<td>').html(response.data.manager)
                                )
                        )

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#wallet-modal'
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

});


