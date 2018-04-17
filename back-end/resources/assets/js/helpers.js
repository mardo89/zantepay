window.getSpinner = size => {

    return $('<div />').addClass('spinner spinner--' + size)
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
        );
}

window.showSpinner = element => {
    element.addClass('is-loading').prop('disabled', true);
    element.append(getSpinner(30));
}

window.hideSpinner = (element) => {
    element.removeClass('is-loading').prop('disabled', false);
    element.find('.spinner').remove();
}

// Errors
window.clearErrors = () => {
    $('.form-error').removeClass('form-error');
    $('.error-text').remove();
}

window.showError = errorMessage => {
    $.magnificPopup.open(
        {
            items: {
                src: '#error-modal'
            },
            type: 'inline',
            closeOnBgClick: true,
            callbacks: {
                elementParse: function (item) {
                    $(item.src).find('#error-message').text(errorMessage);
                }
            }
        }
    );
}

// Validate file
window.validateFile = file => {
    const isValidType = file.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i);
    const isValidSize = (file.size).toFixed(0) < 4194304;

    if (isValidType && isValidSize) {
        return true;
    }

    return false;
}

// Scroll to error
window.scrollToError = () => {
    $('html, body').animate(
        {
            scrollTop: $('.form-error:eq(0)').offset().top
        },
        500
    );
}

// Show Confirmation dialog
window.showConfirmation = (confirmationMessage, onAccept, onReject) => {
    $.magnificPopup.open(
        {
            items: {
                src: '#confirmation-modal'
            },
            type: 'inline',
            showCloseBtn: false,
            closeOnBgClick: false,
            callbacks: {
                elementParse: function (item) {
                    $(item.src).find('#confirmation-message').text(confirmationMessage);

                    $(item.src).find('#accept_action').on('click', function (e) {
                        e.preventDefault();

                        $.magnificPopup.close();

                        if (typeof onAccept === 'function') {
                            onAccept();
                        }
                    });

                    $(item.src).find('#reject_action').on('click', function (e) {
                        e.preventDefault();

                        $.magnificPopup.close();

                        if (typeof onReject === 'function') {
                            onReject;
                        }
                    });
                }
            }
        }
    );
}

// Show popover
window.showPopover = popoverContent => {

    $('.popover').remove();

    const popover = $('<div />').addClass('popover')
        .append(
            $('<i />').addClass('fa fa-check-circle')
        )
        .append(
            $('<div />').addClass('popover__content').html(popoverContent)
        )
        .append(
            $('<a />').addClass('popover__close').attr('href', '').html('Close')
                .on('click', function (e) {
                    e.preventDefault();

                    $('.popover').remove();
                })
        )

    $('body').prepend(
        popover
    )

    setTimeout(
        () => {
            popover.remove();
        },
        5000
    );

}
