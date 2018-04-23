require('./helpers');

$(document).ready(function () {

    $('.wallet').on('click', '#copy-address', function (e) {
        e.preventDefault();

        const address = $(this).parents('.wallet').find('.address').text();

        let tmpEl = $('<input />').val(address);

        $(this).after(tmpEl);

        tmpEl.focus();

        tmpEl.get(0).setSelectionRange(0, address.length);

        document.execCommand("copy");

        tmpEl.remove();
    })

    $('.create-address').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        button.parent().after(
            $('<div />')
                .addClass('col col-md-12 mt-20 primary-color text-sm address-warning')
                .append(
                    $('<span />').text('This operation can take up to 5 minutes. Please do not close or refresh this page.')
                )
        );

        axios.post(
            '/user/wallet/address',
            qs.stringify({})
        )
            .then(
                response => {
                    hideSpinner(button);

                    $('.address-warning').remove();

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

                    $('.address-warning').remove();

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

    $('.rate-calculator input[type="text"]').on('focus', function (event) {
        clearErrors();

        const fromCurrency = $(this).attr('name');
        const toCurrency = $('.rate-calculator input[name!="' + fromCurrency + '"]').attr('name');

        $('input[name="' + fromCurrency + '"]').val('');
        $('input[name="' + toCurrency + '"]').val('');
    });

    $('.rate-calculator input[type="text"]').on('keyup', function (event) {

        const fromCurrency = $(this).attr('name');
        const toCurrency = $('.rate-calculator input[name!="' + fromCurrency + '"]').attr('name');

        if ($(this).val().length === 0) {
            $('input[name="' + toCurrency + '"]').val('');

            return;
        }

        const calculatorParams = {};
        calculatorParams[fromCurrency] = $(this).val();

        axios.post(
            '/user/wallet/rate-calculator',
            qs.stringify(calculatorParams)
        )
            .then(
                response => {
                    clearErrors();

                    $('input[name="' + toCurrency + '"]').val(response.data.balance);
                }
            )
            .catch(
                error => {
                    const {errors, message} = error.response.data;

                    if (error.response.status == 422) {

                        $.each(
                            errors,
                            (field, error) => {
                                $('.rate-calculator input[name="' + field + '"]').parent().addClass('form-error');
                            }
                        )

                    } else {
                        showError(message)
                    }
                }
            )
    });

    $('#transfer_btn').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const transfer = processProtectionRequest(
            {
                eth_amount: $('input[name="transfer_eth_amount"]').val()
            }
        );

        axios.post(
            '/user/wallet/transfer-eth',
            qs.stringify(transfer)
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
                            $('input[name="transfer_eth_amount"]').val('');

                            $('#available_znx_amount').text(response.data.total);

                            $.magnificPopup.open(
                                {
                                    items: {
                                        src: '#transfer-modal'
                                    },
                                    type: 'inline',
                                    closeOnBgClick: true,
                                    callbacks: {
                                        close: function () {
                                            window.location.reload();
                                        },

                                        elementParse: function (item) {
                                            $(item.src).find('#znx_balance').text(response.data.balance);
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
                                $('input[name="transfer_' + field + '"]').parent().addClass('form-error');
                                $('input[name="transfer_' + field + '"]').after(
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

    $('#withdraw_btn').on('click', function (event) {
        event.preventDefault();


        const button = $(this);
        showSpinner(button);
        clearErrors();

        const withdraw = processProtectionRequest(
            {
                address: $('input[name="withdraw_address"]').val()
            }
        );

        axios.post(
            '/user/wallet/withdraw-eth',
            qs.stringify(withdraw)
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
                            $('input[name="withdraw_address"]').val('');

                            $.magnificPopup.open(
                                {
                                    items: {
                                        src: '#withdraw-modal'
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
                                $('input[name="withdraw_' + field + '"]').parent().addClass('form-error');
                                $('input[name="withdraw_' + field + '"]').after(
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

    $('#frm_welcome').on('submit', function (event) {
        event.preventDefault();

        clearErrors();

        if ($('#welcome input[name="tc_item"]').length !== $('#welcome input[name="tc_item"]:checked').length) {

            $('#welcome input[name="tc_item"]').each(function () {
                if (!$(this).prop('checked')) {
                    $(this).parents('.logon-group').addClass('form-error');
                }
            });

            return false;
        }

        const spinner = $('<div />').addClass('spinner-container').css('height', '50px')
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
            )

        const button = $(this).find('input[type="submit"]');

        button.prop('disabled', true).hide();
        button.after(spinner);

        axios.post(
            '/user/accept-terms',
            qs.stringify()
        )
            .then(
                () => {
                    window.location.reload();
                }
            )
            .catch(
                error => {
                    button.prop('disabled', false).show();
                    spinner.remove();

                    const {message} = error.response.data;

                    $('.logon-group').last().after(
                        $('<span />').addClass('error-text').text(message)
                    )

                }
            )
    });

});


