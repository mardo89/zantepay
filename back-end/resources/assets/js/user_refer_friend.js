require('./helpers');

const openShareWindow = (url, windowWidth, windowHeight) => {

    const positionLeft = (screen.availWidth - windowWidth) / 2;
    const positionTop = (screen.availHeight - windowHeight) / 2;
    const params = "width=" + windowWidth + ", height=" + windowHeight + ", resizable=no, scrollbars=yes, left=" + positionLeft + ", top=" + positionTop;

    window.open(url, '_blank', params)

}

$(document).ready(function () {

    $('#invite-friend').on('click', function (event) {
        event.preventDefault();

        const button = $('#invite-friend');
        showSpinner(button);
        clearErrors();

        const invite = {
            'email': $('#friend-email').val()
        }

        axios.post(
            '/user/invite-friend',
            qs.stringify(invite)
        )
            .then(
                response => {
                    hideSpinner(button);

                    $('input[name="email"]').val('');

                    if ($('#' + response.data.email).length === 0) {

                        $('#invites-list tbody')
                            .prepend(
                                $('<tr />').attr('id', response.data.email)
                                    .append(
                                        $('<td />').css('width', '100').addClass('col-center')
                                            .append(
                                                $('<div />').addClass('thumb-60')
                                                    .append(
                                                        $('<img />')
                                                            .attr('src', '/images/avatar.png')
                                                            .attr('alt', response.data.email)
                                                    )
                                            )
                                    )
                                    .append(
                                        $('<td />').text(response.data.email)
                                    )
                                    .append(
                                        $('<td />')
                                            .append(
                                                $('<span />')
                                                    .addClass('primary-color')
                                                    .text(response.data.status)
                                            )
                                    )
                                    .append(
                                        $('<td />').text('')
                                    )
                                    .append(
                                        $('<td />').text('')
                                    )
                                    .append(
                                        $('<td />').css('width', '160').addClass('col-center')
                                            .append(
                                                $('<a />')
                                                    .attr('href', '')
                                                    .addClass('send-link resend-invitation')
                                                    .text('Resend')
                                            )
                                    )
                            )
                    }

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
                                $('#friend-email').parent().addClass('form-error');
                                $('#friend-email').after(
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

    $('#invites-list').on('click', '.resend-invitation', function (event) {
        event.preventDefault();

        const email = $(this).parents('tr').attr('id');

        axios.post(
            '/mail/invite-friend',
            qs.stringify(
                {
                    email
                }
            )
        )
            .catch(
                error => {

                    const {message} = error.response.data;

                    showError(message);
                }
            )
    });

    $('#fb-share').on('click', function (event) {
        event.preventDefault();

        openShareWindow(
            $(this).attr('href'),
            640,
            400
        );
    });

    $('#tw-share').on('click', function (event) {
        event.preventDefault();

        openShareWindow(
            $(this).attr('href'),
            640,
            255
        );
    });

    $('#google-share').on('click', function (event) {
        event.preventDefault();

        openShareWindow(
            $(this).attr('href'),
            400,
            480
        );
    });

});


