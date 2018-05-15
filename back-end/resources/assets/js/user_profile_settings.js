require('./helpers');

const resetForms = () => {

    $('#upload-identity-documents').trigger('reset');
    $('#upload-address-documents').trigger('reset');

}

const initVerify = () => {

    const veriff = Veriff({
        apiKey: '09928dac-7bea-4413-9ade-79fdd5145d01',
        parentId: 'verify-user',
        onSession: function(err, response) {

            if (response.status === 'success' && response.verification !== undefined) {

                const session = {
                    'session_id': response.verification.id,
                    'session_token': response.verification.sessionToken,
                }

                const verificationUrl = response.verification.url;

                axios.post(
                    '/user/verify',
                    qs.stringify(session)
                )
                    .then(
                        () => {
                            location.replace(verificationUrl);
                        }
                    )
                    .catch(
                        error => {
                            const {message} = error.response.data;

                            showError(message);
                        }
                    )

            } else {
                showError('Verification failed')
            }

        }
    });

    veriff.mount();
}

$(document).ready(function () {

    $('.remove-document').on('click', function (event) {
        event.preventDefault();

        showConfirmation(
            'Are you sure do you want to delete this file?',
            () => {
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
            }
        );
    });

    $('#upload-identity-documents').on('submit', function (event) {
        event.preventDefault();
        clearErrors();

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

        const button = $(this).find('button[type="submit"]');
        showSpinner(button);

        axios.post(
            '/user/profile-settings/upload-identity-documents',
            documents
        )
            .then(
                () => {
                    hideSpinner(button);
                    resetForms();

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
                                $('#upload-identity-documents .drag-drop-area').after(
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

        const button = $(this).find('button[type="submit"]');
        showSpinner(button);

        axios.post(
            '/user/profile-settings/upload-address-documents',
            documents
        )
            .then(
                () => {
                    hideSpinner(button);
                    resetForms();

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
                                $('#upload-address-documents .drag-drop-area').after(
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

        const password = processProtectionRequest(
            'Change Password',
            {
                'current-password': $(this).find('input[name="current-password"]').val(),
                'password': $(this).find('input[name="password"]').val(),
                'password_confirmation': $(this).find('input[name="password_confirmation"]').val(),
            }
        );

        axios.post(
            '/user/profile-settings/change-password',
            qs.stringify(password)
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

                        scrollToError();

                    } else {
                        showError(message)
                    }
                }
            )
    });

    $('.update-wallet').on('click', function (event) {
        event.preventDefault();

        const confirmed = $(this).parents('.wallet-address-group').find('.owner-confirm').prop('checked');

        if (!confirmed) {
            showError('Please, confirm that you are the owner of this account');
            return false;
        }

        const wallet = processProtectionRequest(
            'Change Wallet Address',
            {
                'currency': $(this).parents('.wallet-address-group').find('input[name="wallet-currency"]').val(),
                'address': $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').val(),
            }
        );

        const button = $(this);
        showSpinner(button);
        clearErrors();

        axios.post(
            '/user/profile-settings/update-wallet',
            qs.stringify(wallet)
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
                            $(this).parents('.wallet-address-group').find('.owner-confirm').prop('checked', false);

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

    $('#address-files').on('change', function () {

        $('.selected-address-files').remove();

        let fileList = $('<ul />').addClass('files-list selected-address-files');

        $.each(
            $(this)[0].files,
            (index, file) => {

                fileList.append(
                    $('<li />')
                        .append(
                            $('<i />').addClass('file-ico')
                        )
                        .append(
                            $('<div />').addClass('filename').html(file.name)
                        )
                );
            }
        )

        $('#upload-address-documents .drag-drop-area').after(
            fileList
        );

    })

    $('#document-files').on('change', function () {

        $('.selected-document-files').remove();

        let fileList = $('<ul />').addClass('files-list selected-document-files');

        $.each(
            $(this)[0].files,
            (index, file) => {

                fileList.append(
                    $('<li />')
                        .append(
                            $('<i />').addClass('file-ico')
                        )
                        .append(
                            $('<div />').addClass('filename').html(file.name)
                        )
                );
            }
        )

        $('#upload-identity-documents .drag-drop-area').after(
            fileList
        );

    })

    $('#address-files, #document-files').trigger('change');

    initVerify();
});


