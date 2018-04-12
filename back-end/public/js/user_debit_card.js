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
/******/ 	return __webpack_require__(__webpack_require__.s = 52);
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

/***/ 52:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(53);


/***/ }),

/***/ 53:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

$(document).ready(function () {

    $('#dc_design').on('submit', function (event) {
        event.preventDefault();

        var confirmed = $('input[name="terms"]').prop('checked');

        if (!confirmed) {
            showError('Please, confirm that you have read debit card pre-order Terms & Conditions');
            return false;
        }

        var button = $('#dc_design').find('button[type="submit"]');
        showSpinner(button);

        var card = {
            'design': $('input[name="card-type"]:checked').val()
        };

        axios.post('/user/debit-card', qs.stringify(card)).then(function (response) {
            hideSpinner(button);

            window.location = response.data.nextStep;
        }).catch(function (error) {
            hideSpinner(button);

            var message = error.response.data.message;


            showError(message);
        });
    });

    $('#dc_documents').on('submit', function (event) {
        event.preventDefault();
        clearErrors();

        var card = new FormData();

        if ($('input[name="confirm"]').prop('checked')) {
            card.append('verify_later', 1);
        } else {
            card.append('verify_later', 0);

            var isFilesValid = true;

            $.each($('#document-files')[0].files, function (index, file) {
                if (!validateFile(file)) {
                    isFilesValid = false;

                    return false;
                }

                card.append('document_files[]', file);
            });

            if (!isFilesValid) {
                $('.drag-drop-area').after($('<div />').addClass('error-text').text('Incorrect files format.'));

                return false;
            }
        }

        var button = $('#dc_documents').find('button[type="submit"]');
        showSpinner(button);

        axios.post('/user/debit-card-documents', card).then(function (response) {
            hideSpinner(button);

            window.location = response.data.nextStep;
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

    $('#dc_address').on('submit', function (event) {
        event.preventDefault();
        clearErrors();

        var card = new FormData();

        if ($('input[name="confirm"]').prop('checked')) {
            card.append('verify_later', 1);
        } else {
            card.append('verify_later', 0);

            var isFilesValid = true;

            $.each($('#address-files')[0].files, function (index, file) {
                if (!validateFile(file)) {
                    isFilesValid = false;

                    return false;
                }

                card.append('address_files[]', file);
            });

            if (!isFilesValid) {
                $('.drag-drop-area').after($('<div />').addClass('error-text').text('Incorrect files format.'));

                return false;
            }
        }

        var button = $('#dc_address').find('button[type="submit"]');
        showSpinner(button);

        axios.post('/user/debit-card-address', card).then(function (response) {
            hideSpinner(button);

            window.location = response.data.nextStep;
        }).catch(function (error) {
            hideSpinner(button);

            var _error$response$data2 = error.response.data,
                message = _error$response$data2.message,
                errors = _error$response$data2.errors;


            if (error.response.status == 422) {

                $.each(errors, function (field, error) {
                    $('.drag-drop-area').after($('<div />').addClass('error-text').text(error));
                });
            } else {
                showError(message);
            }
        });
    });
});

/***/ })

/******/ });