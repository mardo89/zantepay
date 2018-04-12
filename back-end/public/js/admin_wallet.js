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

/***/ 60:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(61);


/***/ }),

/***/ 61:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);

var searchUser = function searchUser(type, part, table, url, allowGrant) {
    var partFilter = part !== 'ICO_TOTAL' ? part : '';

    // status filter
    var statusFilter = [];

    // @todo Collect status filters

    // page
    var paginator = table.next('nav').find('.pagination');
    var activePage = parseInt(paginator.find('.page-item.active .page-link').html());
    var page = isNaN(activePage) ? 1 : activePage;

    // sort
    var sortIndex = 0;
    var sortOrder = 'asc';

    if (table.find('.sort.sort-asc').length) {
        sortIndex = $('.sort.sort-asc').index();
        sortOrder = 'asc';
    }

    if (table.find('.sort.sort-desc').length) {
        sortIndex = $('.sort.sort-desc').index();
        sortOrder = 'desc';
    }

    // Before request
    table.find('tbody').empty();
    table.find('tbody').append($('<tr />').addClass('operation-in-progress').append($('<td />').attr('colspan', 4).append($('<a />').attr('href', '').addClass('update-icon is-active'))));

    paginator.find('.page-item').empty();

    axios.get(url, {
        params: {
            'part_filter': partFilter,
            'status_filter': statusFilter,
            'page': page,
            'sort_index': sortIndex,
            'sort_order': sortOrder
        }
    }).then(function (response) {
        table.find('.operation-in-progress').remove();

        response.data.transactionsList.forEach(function (transaction) {

            var transactionStatus = '---';

            if (allowGrant) {
                transactionStatus = transaction.status === '' ? '<button class="btn btn--medium btn--shadowed-light" type="button">Issue Token</button>' : transaction.status;
            }

            table.find('tbody').append($('<tr />').append($('<td />').html(transaction.user)).append($('<td />').html(transaction.address)).append($('<td />').html(transaction.amount)).append($('<td />').html(transactionStatus)));
        });

        for (var i = 1; i <= response.data.paginator.totalPages; i++) {
            var itemClass = response.data.paginator.currentPage == i ? 'page-item active' : 'page-item';

            paginator.append($('<li />').addClass(itemClass).append($('<a />').addClass('page-link').attr('href', '#').html(i)));
        }

        // add pagination
        if (response.data.paginator.totalPages > 1) {
            paginator.prepend($('<li />').addClass('page-item').append($('<a />').addClass('page-link prev-page-link').attr('href', '#').html('Previous'))).append($('<li />').addClass('page-item').append($('<a />').addClass('page-link next-page-link').attr('href', '#').html('Next')));
        }
    }).catch(function (error) {
        table.find('.operation-in-progress').remove();

        var message = error.response.data.message;


        showError(message);
    });
};

$(document).ready(function () {

    // Grant Marketing Coins
    $('#grant_marketing_coins').on('click', function (event) {
        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var grantInfo = {
            'address': $('#grant_marketing_address').val(),
            'amount': $('#grant_marketing_amount').val()
        };

        axios.post('/admin/wallet/grant-marketing-coins', qs.stringify(grantInfo)).then(function () {
            hideSpinner(button);

            $.magnificPopup.open({
                items: {
                    src: '#grant-coins-modal'
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

    // Grant Company Coins
    $('#grant_company_coins').on('click', function (event) {
        event.preventDefault();

        var button = $(this);
        showSpinner(button);
        clearErrors();

        var grantInfo = {
            'address': $('#grant_company_address').val(),
            'amount': $('#grant_company_amount').val()
        };

        axios.post('/admin/wallet/grant-company-coins', qs.stringify(grantInfo)).then(function () {
            hideSpinner(button);

            $.magnificPopup.open({
                items: {
                    src: '#grant-coins-modal'
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

    // Run requests to search ico transactions
    $('#ico_part_filter li').each(function () {
        var icoPart = $(this).attr('id');
        var tabID = $(this).find('a').attr('href');
        var table = $(tabID).find('table');

        searchUser('ico', icoPart, table, '/admin/wallet/search-ico-transactions', tabID === '#total');
    });

    $('.ico_transactions_block .pagination').on('click', '.page-link', function (e) {
        e.preventDefault();

        var parent = $(this).parents('.pagination');

        var activeItem = parent.find('.page-item.active');
        var nextItem = $(this).parents('.page-item');

        if ($(this).hasClass('prev-page-link')) {
            activeItem = parent.find('.page-item.active');
            nextItem = activeItem.prev();

            if (nextItem.find('.prev-page-link').length) {
                return false;
            }
        }

        if ($(this).hasClass('next-page-link')) {

            activeItem = parent.find('.page-item.active');
            nextItem = activeItem.next();

            if (nextItem.find('.next-page-link').length) {
                return false;
            }
        }

        activeItem.removeClass('active');
        nextItem.addClass('active');

        var table = $(this).parents('.ico_transactions_block').find('table');
        var tabID = $(this).parents('.ico_transactions_block').attr('id');
        var icoPart = $('#ico_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('ico', icoPart, table, '/admin/wallet/search-ico-transactions', tabID === 'total');
    });

    $('.ico_transactions_block  .sort').on('click', function (e) {
        e.preventDefault();

        if ($(this).hasClass('sort-asc')) {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-desc');
        } else {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-asc');
        }

        var table = $(this).parents('table');
        var tabID = $(this).parents('.ico_transactions_block').attr('id');
        var icoPart = $('#ico_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('ico', icoPart, table, '/admin/wallet/search-ico-transactions', tabID === 'total');
    });

    // Run requests to search foundation transactions
    $('#foundation_part_filter li').each(function () {
        var icoPart = $(this).attr('id');
        var tabID = $(this).find('a').attr('href');
        var table = $(tabID).find('table');

        searchUser('foundation', icoPart, table, '/admin/wallet/search-foundation-transactions', tabID === '#foundation-total');
    });

    $('.foundation_transactions_block .pagination').on('click', '.page-link', function (e) {
        e.preventDefault();

        var parent = $(this).parents('.pagination');

        var activeItem = parent.find('.page-item.active');
        var nextItem = $(this).parents('.page-item');

        if ($(this).hasClass('prev-page-link')) {
            activeItem = parent.find('.page-item.active');
            nextItem = activeItem.prev();

            if (nextItem.find('.prev-page-link').length) {
                return false;
            }
        }

        if ($(this).hasClass('next-page-link')) {

            activeItem = parent.find('.page-item.active');
            nextItem = activeItem.next();

            if (nextItem.find('.next-page-link').length) {
                return false;
            }
        }

        activeItem.removeClass('active');
        nextItem.addClass('active');

        var table = $(this).parents('.foundation_transactions_block').find('table');
        var tabID = $(this).parents('.foundation_transactions_block').attr('id');
        var icoPart = $('#foundation_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('foundation', icoPart, table, '/admin/wallet/search-foundation-transactions', tabID === 'foundation-total');
    });

    $('.foundation_transactions_block  .sort').on('click', function (e) {
        e.preventDefault();

        if ($(this).hasClass('sort-asc')) {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-desc');
        } else {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-asc');
        }

        var table = $(this).parents('table');
        var tabID = $(this).parents('.foundation_transactions_block').attr('id');
        var icoPart = $('#foundation_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('foundation', icoPart, table, '/admin/wallet/search-foundation-transactions', tabID === 'foundation-total');
    });
});

/***/ })

/******/ });