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
/******/ 	return __webpack_require__(__webpack_require__.s = 60);
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

/***/ 60:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(61);


/***/ }),

/***/ 61:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

var processUserDelete = function processUserDelete(deleteButton) {

    var button = deleteButton;
    showSpinner(button);
    clearErrors();

    var userInfo = processProtectionRequest('Delete User', {
        'uid': $('#user-profile-id').val()
    });

    axios.post('/admin/profile/remove', qs.stringify(userInfo)).then(function (response) {
        hideSpinner(button);

        processProtectionResponse(response.status, function () {
            processUserDelete(deleteButton);
        }, function () {
            $.magnificPopup.open({
                items: {
                    src: '#remove-profile-modal'
                },
                type: 'inline',
                closeOnBgClick: true,
                callbacks: {
                    close: function close() {
                        window.location = '/admin/users';
                    }
                }
            });
        });
    }).catch(function (error) {
        hideSpinner(button);

        var message = error.response.data.message;


        showError(message);
    });
};

$(document).ready(function () {

    // Change user role
    $('select[name="user-role"]').on('change', function (event) {
        var _this = this;

        event.preventDefault();

        clearErrors();

        var userInfo = processProtectionRequest('Change User Role', {
            'uid': $('#user-profile-id').val(),
            'role': $(this).val()
        });

        axios.post('/admin/profile', qs.stringify(userInfo)).then(function (response) {

            processProtectionResponse(response.status, function () {
                $(_this).trigger('change');
            }, function () {
                $.magnificPopup.open({
                    items: {
                        src: '#save-profile-modal'
                    },
                    type: 'inline',
                    closeOnBgClick: true
                });
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

                scrollToError();
            } else {
                showError(message);
            }
        });
    });

    // Delete user
    $('#remove-user').on('click', function (event) {
        var _this2 = this;

        event.preventDefault();

        showConfirmation('Are you sure you want to delete this user?', function () {
            processUserDelete($(_this2));
        });
    });

    // Approve documents
    $('.approve-documents').on('click', function (event) {
        var _this3 = this;

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

            var parentRow = $(_this3).parents('.row');

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
        var _this4 = this;

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

            var parentRow = $(_this4).parents('.row');

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
                    $(_this4).parents('.row').find('input[name="decline-reason"]').parent().addClass('form-error');
                    $(_this4).parents('.row').find('input[name="decline-reason"]').after($('<span />').addClass('error-text').text(error));
                });

                scrollToError();
            } else {
                showError(message);
            }
        });
    });

    // Add ZNX ammount
    $('#add-ico-znx').on('click', function (event) {
        var _this5 = this;

        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var user = processProtectionRequest('Add ZNX from ICO pool', {
            'uid': $('#user-profile-id').val(),
            'amount': $('.ico-pool input[name="znx-amount"]').val()
        });

        axios.post('/admin/wallet/add-ico-znx', qs.stringify(user)).then(function (response) {

            hideSpinner(button);

            processProtectionResponse(response.status, function () {
                $(_this5).trigger('click');
            }, function () {
                $('.ico-pool input[name="znx-amount"]').val('');
                $('#total-znx-amount').html(response.data.totalAmount);

                $.magnificPopup.open({
                    items: {
                        src: '#add-ico-znx-modal'
                    },
                    type: 'inline',
                    closeOnBgClick: true,
                    callbacks: {
                        elementParse: function elementParse(item) {
                            $(item.src).find('.znx_added').text(user.amount);
                        }
                    }
                });
            });
        }).catch(function (error) {
            hideSpinner(button);

            var _error$response$data3 = error.response.data,
                message = _error$response$data3.message,
                errors = _error$response$data3.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('.ico-pool input[name="znx-amount"]').parent().addClass('form-error');
                    $('.ico-pool input[name="znx-amount"]').after($('<span />').addClass('error-text').text(error));
                });

                scrollToError();
            } else {
                showError(message);
            }
        });
    });

    $('#add-foundation-znx').on('click', function (event) {
        var _this6 = this;

        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var user = processProtectionRequest('Add ZNX from Foundation pool', {
            'uid': $('#user-profile-id').val(),
            'amount': $('.foundation-pool input[name="znx-amount"]').val()
        });

        axios.post('/admin/wallet/add-foundation-znx', qs.stringify(user)).then(function (response) {

            hideSpinner(button);

            processProtectionResponse(response.status, function () {
                $(_this6).trigger('click');
            }, function () {
                $('.foundation-pool input[name="znx-amount"]').val('');
                $('#total-znx-amount').html(response.data.totalAmount);

                $.magnificPopup.open({
                    items: {
                        src: '#add-foundation-znx-modal'
                    },
                    type: 'inline',
                    closeOnBgClick: true,
                    callbacks: {
                        elementParse: function elementParse(item) {
                            $(item.src).find('.znx_added').text(user.amount);
                        }
                    }
                });
            });
        }).catch(function (error) {
            hideSpinner(button);

            var _error$response$data4 = error.response.data,
                message = _error$response$data4.message,
                errors = _error$response$data4.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('.foundation-pool input[name="znx-amount"]').parent().addClass('form-error');
                    $('.foundation-pool input[name="znx-amount"]').after($('<span />').addClass('error-text').text(error));
                });

                scrollToError();
            } else {
                showError(message);
            }
        });
    });

    // Update Wallet address
    $('.update-wallet').on('click', function (event) {
        var _this7 = this;

        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var wallet = processProtectionRequest('Change user\'s wallet address', {
            'uid': $('#user-profile-id').val(),
            'currency': $(this).parents('.wallet-address-group').find('input[name="wallet-currency"]').val(),
            'address': $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').val()
        });

        axios.post('/admin/wallet', qs.stringify(wallet)).then(function (response) {

            hideSpinner(button);

            processProtectionResponse(response.status, function () {
                $(_this7).trigger('click');
            }, function () {
                $.magnificPopup.open({
                    items: {
                        src: '#wallet-address-modal'
                    },
                    type: 'inline',
                    closeOnBgClick: true
                });
            });
        }).catch(function (error) {
            hideSpinner(button);

            var _error$response$data5 = error.response.data,
                message = _error$response$data5.message,
                errors = _error$response$data5.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $(_this7).parents('.wallet-address-group').find('input[name="wallet-address"]').parent().addClass('form-error');
                    $(_this7).parents('.wallet-address-group').find('input[name="wallet-address"]').after($('<span />').addClass('error-text').text(error));
                });

                scrollToError();
            } else {
                showError(message);
            }
        });
    });
});

/***/ })

/******/ });