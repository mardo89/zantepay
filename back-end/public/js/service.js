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
/******/ 	return __webpack_require__(__webpack_require__.s = 62);
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

/***/ 62:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(63);


/***/ }),

/***/ 63:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

$(document).ready(function () {

    $('#search_mail_events_frm').on('submit', function (event) {
        event.preventDefault();

        // roles filter
        var typeFilter = [];

        $(this).find('input[name="type-filter"]:checked').each(function () {
            typeFilter.push($(this).val());
        });

        // status filter
        var statusFilter = [];

        $(this).find('input[name="status-filter"]:checked').each(function () {
            statusFilter.push($(this).val());
        });

        // page
        var activePage = parseInt($('.page-item.active .page-link').html());
        var page = isNaN(activePage) ? 1 : activePage;

        // sort
        var sortIndex = 0;
        var sortOrder = 'asc';

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

        axios.get('/service/mail-events/search', {
            params: {
                'type_filter': typeFilter,
                'status_filter': statusFilter,
                'page': page,
                'sort_index': sortIndex,
                'sort_order': sortOrder
            }
        }).then(function (response) {
            hideSpinner(button);

            $('#events-list tbody').empty();
            $('.pagination .page-item').empty().hide();

            response.data.eventsList.forEach(function (event) {

                $('#events-list tbody').append($('<tr />').attr('id', event.id).append($('<td />').html(event.date)).append($('<td />').addClass('col-center').html(event.event)).append($('<td />').html(event.to)).append($('<td />').html(event.status)).append($('<td />').append(event.isSuccess ? '' : $('<a />').addClass('send-link resend-email').attr('href', '').html('Resend'))));
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

        $('#search_mail_events_frm').trigger('submit');
    });

    $('#events-list .sort').on('click', function (e) {
        e.preventDefault();

        if ($(this).hasClass('sort-asc')) {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-desc');
        } else {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-asc');
        }

        $('#search_mail_events_frm').trigger('submit');
    });

    $('#search_mail_events_frm button[type="submit"]').on('click', function (e) {
        e.preventDefault();

        $('.pagination .page-item').empty();

        $('#search_mail_events_frm').trigger('submit');
    });

    $('#events-list').on('click', '.resend-email', function (e) {
        e.preventDefault();

        var id = $(this).parents('tr').attr('id');

        var button = $(this);

        button.hide();

        axios.post('/service/mail-events/process', qs.stringify({
            id: id
        })).then(function () {
            button.remove();
        }).catch(function (error) {
            button.show();

            var message = error.response.data.message;


            showError(message);
        });
    });
});

/***/ })

/******/ });