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

/***/ }),

/***/ 50:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(51);


/***/ }),

/***/ 51:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

$(document).ready(function () {

    $('.wallet').on('click', '#copy-address', function (e) {
        e.preventDefault();

        var address = $(this).parents('.wallet').find('.address').text();

        var tmpEl = $('<input />').val(address);

        $('body').append(tmpEl);

        tmpEl.focus();

        tmpEl.get(0).setSelectionRange(0, address.length);

        document.execCommand("copy");

        tmpEl.remove();
    });

    $('.create-address').on('click', function (event) {
        var _this = this;

        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        button.parent().after($('<div />').addClass('col col-md-12 mt-20 primary-color text-sm address-warning').append($('<span />').text('This operation can take up to 5 minutes. Please do not close or refresh this page.')));

        axios.post('/user/wallet/address', qs.stringify({})).then(function (response) {
            hideSpinner(button);

            $('.address-warning').remove();

            var wrapper = button.parent();

            wrapper.before($('<div />').addClass('col col-sm-auto text-lg wordwrap address').text(response.data.address)).before($('<div />').addClass('col col-md-3').append($('<a />').addClass('btn btn--shadowed-light btn--medium btn--130 mt-sm-15').attr({
                id: 'copy-address',
                href: ''
            }).text('Copy')));

            wrapper.remove();

            $.magnificPopup.open({
                items: {
                    src: '#wallet-address-modal'
                },
                type: 'inline',
                closeOnBgClick: true
            });
        }).catch(function (error) {
            hideSpinner(button);

            $('.address-warning').remove();

            var message = error.response.data.message;


            if (error.response.status == 422) {

                $(_this).parents('.wallet-address-group').find('input[name="wallet-address"]').parent().addClass('form-error');

                scrollToError();
            } else {
                showError(message);
            }
        });
    });

    $('.rate-calculator input[type="text"]').on('focus', function (event) {
        clearErrors();

        var fromCurrency = $(this).attr('name');
        var toCurrency = $('.rate-calculator input[name!="' + fromCurrency + '"]').attr('name');

        $('input[name="' + fromCurrency + '"]').val('');
        $('input[name="' + toCurrency + '"]').val('');
    });

    $('.rate-calculator input[type="text"]').on('keyup', function (event) {

        var fromCurrency = $(this).attr('name');
        var toCurrency = $('.rate-calculator input[name!="' + fromCurrency + '"]').attr('name');

        if ($(this).val().length === 0) {
            $('input[name="' + toCurrency + '"]').val('');

            return;
        }

        var calculatorParams = {};
        calculatorParams[fromCurrency] = $(this).val();

        axios.post('/user/wallet/rate-calculator', qs.stringify(calculatorParams)).then(function (response) {
            clearErrors();

            $('input[name="' + toCurrency + '"]').val(response.data.balance);
        }).catch(function (error) {
            var _error$response$data = error.response.data,
                errors = _error$response$data.errors,
                message = _error$response$data.message;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('.rate-calculator input[name="' + field + '"]').parent().addClass('form-error');
                });
            } else {
                showError(message);
            }
        });
    });

    $('#transfer_btn').on('click', function (event) {
        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var transfer = {
            eth_amount: $('input[name="transfer_eth_amount"]').val()
        };

        axios.post('/user/wallet/transfer-eth', qs.stringify(transfer)).then(function (response) {
            hideSpinner(button);

            $('input[name="transfer_eth_amount"]').val('');

            $('#available_znx_amount').text(response.data.total);

            $.magnificPopup.open({
                items: {
                    src: '#transfer-modal'
                },
                type: 'inline',
                closeOnBgClick: true,
                callbacks: {
                    close: function close() {
                        window.location.reload();
                    },

                    elementParse: function elementParse(item) {
                        $(item.src).find('#znx_balance').text(response.data.balance);
                    }
                }
            });
        }).catch(function (error) {
            hideSpinner(button);

            var _error$response$data2 = error.response.data,
                message = _error$response$data2.message,
                errors = _error$response$data2.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('input[name="transfer_' + field + '"]').parent().addClass('form-error');
                    $('input[name="transfer_' + field + '"]').after($('<span />').addClass('error-text').text(error));
                });

                scrollToError();
            } else {
                showError(message);
            }
        });
    });

    $('#withdraw_btn').on('click', function (event) {
        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var withdraw = {
            address: $('input[name="withdraw_address"]').val()
        };

        axios.post('/user/wallet/withdraw-eth', qs.stringify(withdraw)).then(function () {
            hideSpinner(button);

            $('input[name="withdraw_address"]').val('');

            $.magnificPopup.open({
                items: {
                    src: '#withdraw-modal'
                },
                type: 'inline',
                closeOnBgClick: true,
                callbacks: {
                    close: function close() {
                        window.location.reload();
                    }
                }
            });
        }).catch(function (error) {
            hideSpinner(button);

            var _error$response$data3 = error.response.data,
                message = _error$response$data3.message,
                errors = _error$response$data3.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('input[name="withdraw_' + field + '"]').parent().addClass('form-error');
                    $('input[name="withdraw_' + field + '"]').after($('<span />').addClass('error-text').text(error));
                });

                scrollToError();
            } else {
                showError(message);
            }
        });
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

        var spinner = $('<div />').addClass('spinner-container').css('height', '50px').append($('<div />').addClass('spinner spinner--50').append($('<div />')).append($('<div />')).append($('<div />')).append($('<div />')));

        var button = $(this).find('input[type="submit"]');

        button.prop('disabled', true).hide();
        button.after(spinner);

        axios.post('/user/accept-terms', qs.stringify()).then(function () {
            window.location.reload();
        }).catch(function (error) {
            button.prop('disabled', false).show();
            spinner.remove();

            var message = error.response.data.message;


            $('.logon-group').last().after($('<span />').addClass('error-text').text(message));
        });
    });
});

/***/ })

/******/ });