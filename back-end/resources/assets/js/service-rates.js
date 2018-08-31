require('./helpers');

$(document).ready(function () {

    $('#update_rates_frm').on('submit', function (event) {
        event.preventDefault();


        const button = $(this).find('button[type="submit"]');
        showSpinner(button);
        clearErrors();
        $('.pagination').hide();

        axios.post(
            '/service/rates',
            qs.stringify(
                {
                    'eth_rate': $('input[name="eth_rate"]').val(),
                    'usd_rate': $('input[name="usd_rate"]').val(),
                    'euro_rate': $('input[name="euro_rate"]').val()
                }
            )
        )
            .then(
                () => {
                    hideSpinner(button);

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#confirm-rates-update-modal'
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

                    $.each(
                        errors,
                        (field, error) => {
                            $('input[name="' + field + '"]').parents('.form-group').addClass('form-error');
                            $('input[name="' + field + '"]').after(
                                $('<span />').addClass('error-text').text(error)
                            );
                        }
                    )

                    if (error.response.status == 500) {
                        showError(message)
                    }
                }
            )

    });


});


