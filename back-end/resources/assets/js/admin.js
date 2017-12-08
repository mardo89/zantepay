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

// Errors
const clearErrors = () => {
    $('.form-error').removeClass('form-error');
    $('.error-text').remove();
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

        showSpinner($('#user-profile').find('button[type="submit"]'), 50);
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
                () => {
                    hideSpinner($('#user-profile').find('button[type="submit"]'));

                    $('#user-profile select[name="role"]').parents('.form-group').addClass('form-error');
                }
            )

    });

    // Approve document
    $('.user-documents').on('submit', function (event) {
        event.preventDefault();

        showSpinner($(this).find('button[type="submit"]'), 50);
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
                    hideSpinner($(this).find('button[type="submit"]'));

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
                () => {
                    hideSpinner($(this).find('button[type="submit"]'));
                }
            )

    });

    // Filters
    $('input[name="search-by-email"]').on('keyup', function(event) {
        const filterText = $(this).val();

        $('#users-list tbody tr').each(function(index, element) {
            const userEmail = $(element).find('td:eq(1)').text();

            if (filterText.trim().length !== 0 && userEmail.indexOf(filterText) === -1) {
                $(this).hide()
            } else {
                $(this).show();
            }
        });
    });

    $('input[name="referrer-filter"]').on('change', function(event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function(index, element) {
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

    $('input[name="status-filter"]').on('change', function(event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function(index, element) {
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

    $('input[name="role-filter"]').on('change', function(event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function(index, element) {
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

});


