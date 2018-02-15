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
/******/ 	return __webpack_require__(__webpack_require__.s = 57);
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

/***/ 57:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(58);


/***/ }),

/***/ 58:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

$(document).ready(function () {

    // Change user role
    $('select[name="user-role"]').on('change', function (event) {
        event.preventDefault();

        clearErrors();

        var userInfo = {
            'uid': $('#user-profile-id').val(),
            'role': $(this).val()
        };

        axios.post('/admin/profile', qs.stringify(userInfo)).then(function () {
            $.magnificPopup.open({
                items: {
                    src: '#save-profile-modal'
                },
                type: 'inline',
                closeOnBgClick: true
            });
        }).catch(function (error) {
            var _error$response$data = error.response.data,
                message = _error$response$data.message,
                errors = _error$response$data.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('#user-profile select[name="user-role"]').parents('.form-group').addClass('form-error');
                    $('#user-profile select[name="user-role"]').after($('<span />').addClass('error-text').text(error));
                });
            } else {
                showError(message);
            }
        });
    });

    // Delete user
    $('#remove-user').on('click', function (event) {
        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var userInfo = {
            'uid': $('#user-profile-id').val()
        };

        axios.post('/admin/profile/remove', qs.stringify(userInfo)).then(function () {
            hideSpinner(button);

            $.magnificPopup.open({
                items: {
                    src: '#remove-profile-modal'
                },
                type: 'inline',
                closeOnBgClick: true,
                callbacks: {
                    close: function close() {
                        window.location.pathname = '/admin/users';
                    }
                }
            });
        }).catch(function (error) {
            hideSpinner(button);

            var message = error.response.data.message;


            showError(message);
        });
    });

    // Approve documents
    $('.approve-documents').on('click', function (event) {
        var _this = this;

        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var document = {
            'uid': $('#user-profile-id').val(),
            'type': $(this).parent().find('input[name="document-type"]').val()
        };

        axios.post('document/approve', qs.stringify(document)).then(function (response) {
            hideSpinner(button);

            var parentRow = $(_this).parents('.row');

            parentRow.find('.document-actions').before($('<div />').addClass('col-md-3 col-sm-4 col-5 mb-20 document-status').html(response.data.status));

            parentRow.find('.document-actions').remove();
            parentRow.find('.document-reason').remove();

            $.magnificPopup.open({
                items: {
                    src: '#approve-documents-modal'
                },
                type: 'inline',
                closeOnBgClick: true
            });
        }).catch(function (error) {
            hideSpinner(button);

            var message = error.response.data.message;


            showError(message);
        });
    });

    // Decline documents
    $('.decline-documents').on('click', function (event) {
        var _this2 = this;

        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var document = {
            'uid': $('#user-profile-id').val(),
            'type': $(this).parent().find('input[name="document-type"]').val(),
            'reason': $(this).parents('.row').find('input[name="decline-reason"]').val()
        };

        axios.post('document/decline', qs.stringify(document)).then(function (response) {
            hideSpinner(button);

            var parentRow = $(_this2).parents('.row');

            parentRow.find('.document-actions').before($('<div />').addClass('col-md-3 col-sm-4 col-5 mb-20 document-status').html(response.data.status));

            parentRow.find('.document-actions').remove();
            parentRow.find('.document-reason').remove();

            $.magnificPopup.open({
                items: {
                    src: '#decline-documents-modal'
                },
                type: 'inline',
                closeOnBgClick: true
            });
        }).catch(function (error) {
            hideSpinner(button);

            var _error$response$data2 = error.response.data,
                message = _error$response$data2.message,
                errors = _error$response$data2.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $(_this2).parents('.row').find('input[name="decline-reason"]').parent().addClass('form-error');
                    $(_this2).parents('.row').find('input[name="decline-reason"]').after($('<span />').addClass('error-text').text(error));
                });
            } else {
                showError(message);
            }
        });
    });

    // Add ZNX ammount
    $('#add-znx').on('click', function (event) {
        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var user = {
            'uid': $('#user-profile-id').val(),
            'amount': $('input[name="znx-amount"]').val()
        };

        axios.post('/admin/wallet/znx', qs.stringify(user)).then(function (response) {
            hideSpinner(button);

            $('input[name="znx-amount"]').val('');
            $('#total-znx-amount').html(response.data.totalAmount);

            $.magnificPopup.open({
                items: {
                    src: '#add-znx-modal'
                },
                type: 'inline',
                closeOnBgClick: true
            });
        }).catch(function (error) {
            hideSpinner(button);

            var _error$response$data3 = error.response.data,
                message = _error$response$data3.message,
                errors = _error$response$data3.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('input[name="znx-amount"]').parent().addClass('form-error');
                    $('input[name="znx-amount"]').after($('<span />').addClass('error-text').text(error));
                });
            } else {
                showError(message);
            }
        });
    });

    // Update Wallet address
    $('.update-wallet').on('click', function (event) {
        var _this3 = this;

        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var wallet = {
            'uid': $('#user-profile-id').val(),
            'currency': $(this).parents('.wallet-address-group').find('input[name="wallet-currency"]').val(),
            'address': $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').val()
        };

        axios.post('/admin/wallet', qs.stringify(wallet)).then(function () {
            hideSpinner(button);

            $.magnificPopup.open({
                items: {
                    src: '#wallet-address-modal'
                },
                type: 'inline',
                closeOnBgClick: true
            });
        }).catch(function (error) {
            hideSpinner(button);

            var _error$response$data4 = error.response.data,
                message = _error$response$data4.message,
                errors = _error$response$data4.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $(_this3).parents('.wallet-address-group').find('input[name="wallet-address"]').parent().addClass('form-error');
                    $(_this3).parents('.wallet-address-group').find('input[name="wallet-address"]').after($('<span />').addClass('error-text').text(error));
                });
            } else {
                showError(message);
            }
        });
    });
});

/***/ })

/******/ });