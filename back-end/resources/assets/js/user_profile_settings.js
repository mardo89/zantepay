require('./helpers');

$(document).ready(function () {

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

        const wallet = {
            'currency': $(this).parents('.wallet-address-group').find('input[name="wallet-currency"]').val(),
            'address': $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').val(),
        }

        const button = $(this);
        showSpinner(button);
        clearErrors();

        axios.post(
            '/user/profile-settings/update-wallet',
            qs.stringify(wallet)
        )
            .then(
                () => {
                    hideSpinner(button);

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


