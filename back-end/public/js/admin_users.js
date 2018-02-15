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
/******/ 	return __webpack_require__(__webpack_require__.s = 55);
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

/***/ 55:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(56);


/***/ }),

/***/ 56:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

$(document).ready(function () {

    $('input[name="search-by-email"]').on('keyup', function (event) {
        var filterText = $(this).val();

        var colToSearch = $(this).hasClass('wallets-search') ? 0 : 1;

        $('#users-list tbody tr').each(function (index, element) {
            var userEmail = $(element).find('td:eq(' + colToSearch + ')').text();

            if (filterText.trim().length !== 0 && userEmail.indexOf(filterText) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    $('.search-cross').on('click', function (event) {
        event.preventDefault();

        $('input[name="search-by-email"]').val('').trigger('keyup');
    });

    $('input[name="referrer-filter"]').on('change', function (event) {
        var refFilter = $(this).val();
        var isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            var hasReferrer = $(element).find('td:eq(5)').text().trim().length === 0 ? 0 : 1;

            if (refFilter == hasReferrer) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });

    $('input[name="status-filter"]').on('change', function (event) {
        var refFilter = $(this).val();
        var isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            var userStatus = $(element).find('td:eq(4)').text().trim();

            if (refFilter == userStatus) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });

    $('input[name="role-filter"]').on('change', function (event) {
        var refFilter = $(this).val();
        var isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            var userRole = $(element).find('td:eq(3)').text().trim();

            if (refFilter == userRole) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });

    $('input[name="dc-filter"]').on('change', function (event) {
        var refFilter = $(this).val();
        var isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            var userRole = $(element).find('td:eq(1)').text().trim();

            if (refFilter == userRole) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });
});

/***/ })

/******/ });