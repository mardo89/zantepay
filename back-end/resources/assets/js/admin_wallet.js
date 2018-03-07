require('./helpers');

$(document).ready(function () {

    // Grant Marketing Coins
    $('#grant_marketing_coins').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const grantInfo = {
            'address': $('#grant_marketing_address').val(),
            'amount': $('#grant_marketing_amount').val()
        }

        axios.post(
            '/admin/wallet/grant-marketing-coins',
            qs.stringify(grantInfo)
        )
            .then(
                () => {
                    hideSpinner(button);

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#grant-coins-modal'
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

});


