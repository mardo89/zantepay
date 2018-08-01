require('./helpers');

$(document).ready(function () {

    $('#dc_country').on('submit', function (event) {
        event.preventDefault();

        const button = $('#dc_country').find('button[type="submit"]');
        showSpinner(button);

        const card = {
            'country': $('select[name="card-country"]').val()
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

                    const {message, errors} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('select[name="card-country"]').parent().addClass('form-error');
                                $('select[name="card-country"]').after(
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

    $('#dc_design').on('submit', function (event) {
        event.preventDefault();

        const confirmed = $('input[name="terms"]').prop('checked');

        if (!confirmed) {
            showError('Please, confirm that you have read debit card pre-order Terms & Conditions');
            return false;
        }

        const button = $('#dc_design').find('button[type="submit"]');
        showSpinner(button);

        const card = {
            'design': $('input[name="card-type"]:checked').val()
        }

        axios.post(
            '/user/debit-card-design',
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


