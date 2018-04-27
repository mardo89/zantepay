require('./helpers');

const processUserDelete = deleteButton => {

    const button = deleteButton;
    showSpinner(button);
    clearErrors();

    const userInfo = processProtectionRequest(
        'Delete User',
        {
            'uid': $('#user-profile-id').val(),
        }
    )


    axios.post(
        '/admin/profile/remove',
        qs.stringify(userInfo)
    )
        .then(
            response => {
                hideSpinner(button);

                processProtectionResponse(
                    response.status,
                    () => {
                        processUserDelete(deleteButton);
                    },
                    () => {
                        $.magnificPopup.open(
                            {
                                items: {
                                    src: '#remove-profile-modal'
                                },
                                type: 'inline',
                                closeOnBgClick: true,
                                callbacks: {
                                    close: function () {
                                        window.location = '/admin/users'
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

                showError(message)
            }
        )

}


$(document).ready(function () {

    // Change user role
    $('select[name="user-role"]').on('change', function (event) {
        event.preventDefault();

        clearErrors();

        const userInfo = processProtectionRequest(
            'Change User Role',
            {
                'uid': $('#user-profile-id').val(),
                'role': $(this).val(),
            }
        );


        axios.post(
            '/admin/profile',
            qs.stringify(userInfo)
        )
            .then(
                response => {

                    processProtectionResponse(
                        response.status,
                        () => {
                            $(this).trigger('change');
                        },
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
                    );

                }
            )
            .catch(
                error => {

                    const {message, errors} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('#user-profile select[name="user-role"]').parents('.form-group').addClass('form-error');
                                $('#user-profile select[name="user-role"]').after(
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

    // Delete user
    $('#remove-user').on('click', function (event) {
        event.preventDefault();

        showConfirmation(
            'Are you sure you want to delete this user?',
            () => {
                processUserDelete($(this));
            }
        );

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
                response => {
                    hideSpinner(button);

                    let parentRow = $(this).parents('.row');

                    parentRow.find('.document-actions').before(
                        $('<div />').addClass('col-md-3 col-sm-4 col-5 mb-20 document-status').html(response.data.status)
                    );

                    parentRow.find('.document-actions').remove();
                    parentRow.find('.document-reason').remove();

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
                response => {
                    hideSpinner(button);

                    let parentRow = $(this).parents('.row');

                    parentRow.find('.document-actions').before(
                        $('<div />').addClass('col-md-3 col-sm-4 col-5 mb-20 document-status').html(response.data.status)
                    );

                    parentRow.find('.document-actions').remove();
                    parentRow.find('.document-reason').remove();

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

                    const {message, errors} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $(this).parents('.row').find('input[name="decline-reason"]').parent().addClass('form-error');
                                $(this).parents('.row').find('input[name="decline-reason"]').after(
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

    // Add ZNX ammount
    $('#add-ico-znx').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const user = processProtectionRequest(
            'Add ZNX from ICO pool',
            {
                'uid': $('#user-profile-id').val(),
                'amount': $('.ico-pool input[name="znx-amount"]').val(),
            }
        )

        axios.post(
            '/admin/wallet/add-ico-znx',
            qs.stringify(user)
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
                            $('.ico-pool input[name="znx-amount"]').val('');
                            $('#total-znx-amount').html(response.data.totalAmount);

                            $.magnificPopup.open(
                                {
                                    items: {
                                        src: '#add-ico-znx-modal'
                                    },
                                    type: 'inline',
                                    closeOnBgClick: true,
                                    callbacks: {
                                        elementParse: function (item) {
                                            $(item.src).find('.znx_added').text(user.amount);
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

                    const {message, errors} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('.ico-pool input[name="znx-amount"]').parent().addClass('form-error');
                                $('.ico-pool input[name="znx-amount"]').after(
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

    $('#add-foundation-znx').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const user = processProtectionRequest(
            'Add ZNX from Foundation pool',
            {
                'uid': $('#user-profile-id').val(),
                'amount': $('.foundation-pool input[name="znx-amount"]').val(),
            }
        );

        axios.post(
            '/admin/wallet/add-foundation-znx',
            qs.stringify(user)
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
                            $('.foundation-pool input[name="znx-amount"]').val('');
                            $('#total-znx-amount').html(response.data.totalAmount);

                            $.magnificPopup.open(
                                {
                                    items: {
                                        src: '#add-foundation-znx-modal'
                                    },
                                    type: 'inline',
                                    closeOnBgClick: true,
                                    callbacks: {
                                        elementParse: function (item) {
                                            $(item.src).find('.znx_added').text(user.amount);
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

                    const {message, errors} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('.foundation-pool input[name="znx-amount"]').parent().addClass('form-error');
                                $('.foundation-pool input[name="znx-amount"]').after(
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

                    const {message, errors} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').parent().addClass('form-error');
                                $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').after(
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

});


