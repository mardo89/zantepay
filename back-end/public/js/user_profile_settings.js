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
/******/ 	return __webpack_require__(__webpack_require__.s = 45);
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

/***/ 45:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(46);


/***/ }),

/***/ 46:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

$(document).ready(function () {

    $('.remove-document').on('click', function (event) {
        event.preventDefault();

        var file = {
            'did': $(this).parents('li').attr('id')
        };

        axios.post('/user/profile-settings/remove-document', qs.stringify(file)).then(function () {
            $.magnificPopup.open({
                items: {
                    src: '#remove-document-modal'
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
            var message = error.response.data.message;


            showError(message);
        });
    });

    $('#upload-identity-documents').on('submit', function (event) {
        event.preventDefault();

        var documents = new FormData();

        var isFilesValid = true;

        $.each($('#document-files')[0].files, function (index, file) {
            if (!validateFile(file)) {
                isFilesValid = false;

                return false;
            }

            documents.append('document_files[]', file);
        });

        if (!isFilesValid) {
            showError('Incorrect files format.');

            return false;
        }

        var button = $('#upload-identity-documents').find('button[type="submit"]');
        showSpinner(button);

        axios.post('/user/profile-settings/upload-identity-documents', documents).then(function () {
            hideSpinner(button);

            $.magnificPopup.open({
                items: {
                    src: '#upload-documents-modal'
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

            var message = error.response.data.message;


            showError(message);
        });
    });

    $('#upload-address-documents').on('submit', function (event) {
        event.preventDefault();
        clearErrors();

        var documents = new FormData();

        var isFilesValid = true;

        $.each($('#address-files')[0].files, function (index, file) {
            if (!validateFile(file)) {
                isFilesValid = false;

                return false;
            }

            documents.append('address_files[]', file);
        });

        if (!isFilesValid) {
            $('.drag-drop-area').after($('<div />').addClass('error-text').text('Incorrect files format.'));

            return false;
        }

        var button = $('#upload-identity-documents').find('button[type="submit"]');
        showSpinner(button);

        axios.post('/user/profile-settings/upload-address-documents', documents).then(function () {
            hideSpinner(button);

            $.magnificPopup.open({
                items: {
                    src: '#upload-documents-modal'
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

            var _error$response$data = error.response.data,
                message = _error$response$data.message,
                errors = _error$response$data.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('.drag-drop-area').after($('<div />').addClass('error-text').text(error));
                });
            } else {
                showError(message);
            }
        });
    });

    $('#change-password').on('submit', function (event) {
        event.preventDefault();

        var button = $(this).find('input[type="submit"]');
        showSpinner(button);
        clearErrors();

        var password = {
            'current-password': $(this).find('input[name="current-password"]').val(),
            'password': $(this).find('input[name="password"]').val(),
            'password_confirmation': $(this).find('input[name="confirm-password"]').val()
        };

        axios.post('/user/profile-settings/change-password', qs.stringify(password)).then(function () {
            hideSpinner(button);

            $('#change-password input[type="password"]').val('');

            $.magnificPopup.open({
                items: {
                    src: '#change-password-modal'
                },
                type: 'inline',
                closeOnBgClick: true
            });
        }).catch(function (error) {
            hideSpinner(button);

            var _error$response$data2 = error.response.data,
                errors = _error$response$data2.errors,
                message = _error$response$data2.message;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('#change-password input[name="' + field + '"]').parent().addClass('form-error');
                    $('#change-password input[name="' + field + '"]').after($('<span />').addClass('error-text').text(error));
                });
            } else {
                showError(message);
            }
        });
    });

    $('.update-wallet').on('click', function (event) {
        var _this = this;

        event.preventDefault();

        var confirmed = $(this).parents('.wallet-address-group').find('.owner-confirm').prop('checked');

        if (!confirmed) {
            showError('Please, confirm that you are the owner of this account');
            return false;
        }

        var wallet = {
            'currency': $(this).parents('.wallet-address-group').find('input[name="wallet-currency"]').val(),
            'address': $(this).parents('.wallet-address-group').find('input[name="wallet-address"]').val()
        };

        var button = $(this);
        showSpinner(button);
        clearErrors();

        axios.post('/user/profile-settings/update-wallet', qs.stringify(wallet)).then(function () {
            hideSpinner(button);

            $(_this).parents('.wallet-address-group').find('.owner-confirm').prop('checked', false);

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
});

/***/ })

/******/ });