/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 50);
/******/ })
/************************************************************************/
/******/ ({

/***/ 1:
/***/ (function(module, exports) {

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

window.getSpinner = function (size) {

    return $('<div />').addClass('spinner spinner--' + size).append($('<div />')).append($('<div />')).append($('<div />')).append($('<div />'));
};

window.showSpinner = function (element) {
    element.addClass('is-loading').prop('disabled', true);
    element.append(getSpinner(30));
};

window.hideSpinner = function (element) {
    element.removeClass('is-loading').prop('disabled', false);
    element.find('.spinner').remove();
};

// Errors
window.clearErrors = function () {
    $('.form-error').removeClass('form-error');
    $('.error-text').remove();
};

window.showError = function (errorMessage) {
    $.magnificPopup.open({
        items: {
            src: '#error-modal'
        },
        type: 'inline',
        closeOnBgClick: true,
        callbacks: {
            elementParse: function elementParse(item) {
                $(item.src).find('#error-message').text(errorMessage);
            }
        }
    });
};

// Validate file
window.validateFile = function (file) {
    var isValidType = file.type.match(/(.png)|(.jpeg)|(.jpg)|(.pdf)$/i);
    var isValidSize = file.size.toFixed(0) < 4194304;

    if (isValidType && isValidSize) {
        return true;
    }

    return false;
};

// Scroll to error
window.scrollToError = function () {
    $('html, body').animate({
        scrollTop: $('.form-error:eq(0)').offset().top
    }, 500);
};

// Show Confirmation dialog
window.showConfirmation = function (confirmationMessage, onAccept, onReject) {
    $.magnificPopup.open({
        items: {
            src: '#confirmation-modal'
        },
        type: 'inline',
        showCloseBtn: false,
        closeOnBgClick: false,
        callbacks: {
            elementParse: function elementParse(item) {
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
    });
};

// Show popover
window.showPopover = function (popoverContent) {

    $('.popover').remove();

    var popover = $('<div />').addClass('popover').append($('<i />').addClass('fa fa-check-circle')).append($('<div />').addClass('popover__content').html(popoverContent)).append($('<a />').addClass('popover__close').attr('href', '').html('Close').on('click', function (e) {
        e.preventDefault();

        $('.popover').remove();
    }));

    $('body').prepend(popover);

    setTimeout(function () {
        popover.remove();
    }, 5000);
};

// Show Protection dialog
window.showProtectionDialog = function (onSubscribe) {

    $.magnificPopup.open({
        items: {
            src: '#protection-modal'
        },
        type: 'inline',
        showCloseBtn: true,
        closeOnBgClick: true,
        callbacks: {
            elementParse: function elementParse(item) {
                $(item.src).find('#frm_protection').find('input[name="signature"]').val('');

                $(item.src).find('#frm_protection').off('submit').on('submit', function (e) {
                    e.preventDefault();

                    sessionStorage.setItem('signature', $(this).find('input[name="signature"]').val());

                    $.magnificPopup.close();

                    onSubscribe();
                });
            }
        }
    });
};

// Check protection status
window.processProtectionRequest = function (action, requestParams) {
    var signature = sessionStorage.getItem('signature');
    sessionStorage.removeItem('signature');

    if (!signature) {
        var action_timestamp = new Date().valueOf();
        sessionStorage.setItem('action_timestamp', action_timestamp);

        return _extends({}, requestParams, {
            action: action,
            action_timestamp: action_timestamp
        });
    }

    return _extends({}, requestParams, {
        action_timestamp: sessionStorage.getItem('action_timestamp'),
        signature: signature
    });
};

// Check protection status
window.processProtectionResponse = function (responseStatus, processWithProtection, processWithoutProtection) {

    if (responseStatus == 205) {

        if (typeof processWithProtection === 'function') {
            showProtectionDialog(processWithProtection);
        }
    } else {

        if (typeof processWithoutProtection === 'function') {
            processWithoutProtection();
        }
    }
};

/***/ }),

/***/ 50:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(51);


/***/ }),

/***/ 51:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

var openShareWindow = function openShareWindow(url, windowWidth, windowHeight) {

    var positionLeft = (screen.availWidth - windowWidth) / 2;
    var positionTop = (screen.availHeight - windowHeight) / 2;
    var params = "width=" + windowWidth + ", height=" + windowHeight + ", resizable=no, scrollbars=yes, left=" + positionLeft + ", top=" + positionTop;

    window.open(url, '_blank', params);
};

$(document).ready(function () {

    $('#invite-friend').on('click', function (event) {
        event.preventDefault();

        var button = $('#invite-friend');
        showSpinner(button);
        clearErrors();

        var email = $('#friend-email').val();

        axios.post('/user/invite-friend', qs.stringify({
            email: email
        })).then(function (response) {
            hideSpinner(button);

            $('input[name="email"]').val('');

            if ($("#invites-list tr td:contains('" + response.data.email + "')").length === 0) {

                $('#invites-list tbody').prepend($('<tr />').attr('id', response.data.email).append($('<td />').css('width', '100').addClass('col-center').append($('<div />').addClass('thumb-60').append($('<img />').attr('src', '/images/avatar.png').attr('alt', response.data.email)))).append($('<td />').text(response.data.email)).append($('<td />').append($('<span />').addClass('primary-color').text(response.data.status))).append($('<td />').text('')).append($('<td />').text('')).append($('<td />').css('width', '160').addClass('col-center').append($('<a />').attr('href', '').addClass('send-link resend-invitation').text('Resend'))));
            }
        }).catch(function (error) {
            hideSpinner(button);

            console.error(error);

            var _error$response$data = error.response.data,
                message = _error$response$data.message,
                errors = _error$response$data.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('#friend-email').parent().addClass('form-error');
                    $('#friend-email').after($('<span />').addClass('error-text').text(error));
                });

                scrollToError();
            } else {
                showError(message);
            }
        });
    });

    $('#invites-list').on('click', '.resend-invitation', function (event) {
        event.preventDefault();

        var email = $(this).parents('tr').attr('id');

        axios.post('/user/invite-friend', qs.stringify({
            email: email
        })).catch(function (error) {
            var message = error.response.data.message;


            showError(message);
        });
    });

    $('#fb-share').on('click', function (event) {
        event.preventDefault();

        openShareWindow($(this).attr('href'), 640, 400);
    });

    $('#tw-share').on('click', function (event) {
        event.preventDefault();

        openShareWindow($(this).attr('href'), 640, 255);
    });

    $('#google-share').on('click', function (event) {
        event.preventDefault();

        openShareWindow($(this).attr('href'), 400, 480);
    });
});

/***/ })

/******/ });