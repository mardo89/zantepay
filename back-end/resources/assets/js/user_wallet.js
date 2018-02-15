require('./helpers');

$(document).ready(function () {

    $('.wallet').on('click', '#copy-address', function (e) {
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

});


