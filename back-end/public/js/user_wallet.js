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
/******/ 	return __webpack_require__(__webpack_require__.s = 49);
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

/***/ }),

/***/ 49:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(50);


/***/ }),

/***/ 50:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

$(document).ready(function () {

    $('.wallet').on('click', '#copy-address', function (e) {
        e.preventDefault();

        var address = $(this).parents('.wallet').find('.address').text();

        var tmpEl = $('<input />').val(address);

        $('body').append(tmpEl);

        tmpEl.select();

        document.execCommand("copy");

        tmpEl.remove();
    });

    $('.create-address').on('click', function (event) {
        var _this = this;

        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        axios.post('/user/wallet/address', qs.stringify({})).then(function (response) {
            hideSpinner(button);

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

            var message = error.response.data.message;


            if (error.response.status == 422) {
                $(_this).parents('.wallet-address-group').find('input[name="wallet-address"]').parent().addClass('form-error');
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
});

/***/ })

/******/ });