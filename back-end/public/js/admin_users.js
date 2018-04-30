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
/******/ 	return __webpack_require__(__webpack_require__.s = 58);
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

/***/ 58:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(59);


/***/ }),

/***/ 59:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

$(document).ready(function () {

    $('#search_user_frm').on('submit', function (event) {
        event.preventDefault();

        // roles filter
        var roleFilter = [];

        $(this).find('input[name="role-filter"]:checked').each(function () {
            roleFilter.push($(this).val());
        });

        // status filter
        var statusFilter = [];

        $(this).find('input[name="status-filter"]:checked').each(function () {
            statusFilter.push($(this).val());
        });

        // referrer
        var referrerFilter = [];

        $(this).find('input[name="referrer-filter"]:checked').each(function () {
            referrerFilter.push($(this).val());
        });

        // email / name
        var nameFilter = $(this).find('input[name="search-by-email"]').val();

        // date filter
        var dateFromFiler = $(this).find('input[name="date_from_filter"]').val();
        var dateToFiler = $(this).find('input[name="date_to_filter"]').val();

        // page
        var activePage = parseInt($('.page-item.active .page-link').html());
        var page = isNaN(activePage) ? 1 : activePage;

        // sort
        var sortIndex = 2;
        var sortOrder = 'desc';

        if ($('.sort.sort-asc').length) {
            sortIndex = $('.sort.sort-asc').index();
            sortOrder = 'asc';
        }

        if ($('.sort.sort-desc').length) {
            sortIndex = $('.sort.sort-desc').index();
            sortOrder = 'desc';
        }

        var button = $(this).find('button[type="submit"]');
        showSpinner(button);
        clearErrors();
        $('.pagination').hide();

        axios.get('/admin/users/search', {
            params: {
                'role_filter': roleFilter,
                'status_filter': statusFilter,
                'referrer_filter': referrerFilter,
                'name_filter': nameFilter,
                'date_from_filter': dateFromFiler,
                'date_to_filter': dateToFiler,
                'page': page,
                'sort_index': sortIndex,
                'sort_order': sortOrder
            }
        }).then(function (response) {
            hideSpinner(button);

            $('#users-list tbody').empty();
            $('.pagination .page-item').empty().hide();

            response.data.usersList.forEach(function (user) {

                $('#users-list tbody').append($('<tr />').attr('id', user.id).append($('<td />').attr('width', 100).addClass('col-center').append($('<div />').addClass('thumb-60').append($('<img />').attr({ 'src': user.avatar, 'alt': user.name })))).append($('<td />').append($('<a />').addClass('primary-color').attr('href', user.profileLink).html(user.email))).append($('<td />').html(user.name)).append($('<td />').html(user.registered)).append($('<td />').html(user.role)).append($('<td />').html(user.status)).append($('<td />').html(user.hasReferrals)));
            });

            for (var i = 1; i <= response.data.paginator.totalPages; i++) {
                var itemClass = response.data.paginator.currentPage == i ? 'page-item active' : 'page-item';

                $('.pagination').append($('<li />').addClass(itemClass).append($('<a />').addClass('page-link').attr('href', '#').html(i)));
            }

            // add pagination
            if (response.data.paginator.totalPages > 1) {
                $('.pagination').prepend($('<li />').addClass('page-item').append($('<a />').addClass('page-link prev-page-link').attr('href', '#').html('Previous'))).append($('<li />').addClass('page-item').append($('<a />').addClass('page-link next-page-link').attr('href', '#').html('Next')));

                $('.pagination').show();
            }
        }).catch(function (error) {
            hideSpinner(button);

            var message = error.response.data.message;


            showError(message);
        });
    });

    $('.pagination').on('click', '.page-link', function (e) {
        e.preventDefault();

        var activeItem = $('.page-item.active');
        var nextItem = $(this).parents('.page-item');

        if ($(this).hasClass('prev-page-link')) {
            activeItem = $('.page-item.active');
            nextItem = activeItem.prev();

            if (nextItem.find('.prev-page-link').length) {
                return false;
            }
        }

        if ($(this).hasClass('next-page-link')) {

            activeItem = $('.page-item.active');
            nextItem = activeItem.next();

            if (nextItem.find('.next-page-link').length) {
                return false;
            }
        }

        activeItem.removeClass('active');
        nextItem.addClass('active');

        $('#search_user_frm').trigger('submit');
    });

    $('#users-list .sort').on('click', function (e) {
        e.preventDefault();

        if ($(this).hasClass('sort-asc')) {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-desc');
        } else {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-asc');
        }

        $('#search_user_frm').trigger('submit');
    });

    $('#search_user_frm button[type="submit"]').on('click', function (e) {
        e.preventDefault();

        $('.pagination .page-item').empty();

        $('#search_user_frm').trigger('submit');
    });
});

/***/ })

/******/ });