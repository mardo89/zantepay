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

/***/ 62:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(63);


/***/ }),

/***/ 63:
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

    // Run requests to search marketing transactions
    $('#marketing_part_filter li').each(function () {
        var icoPart = $(this).attr('id');
        var tabID = $(this).find('a').attr('href');
        var table = $(tabID).find('table');

        searchUser('marketing', icoPart, table, '/admin/wallet/search-marketing-transactions', tabID === '#marketing-total');
    });

    $('.marketing_transactions_block .pagination').on('click', '.page-link', function (e) {
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

        var table = $(this).parents('.marketing_transactions_block').find('table');
        var tabID = $(this).parents('.marketing_transactions_block').attr('id');
        var icoPart = $('#marketing_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('marketing', icoPart, table, '/admin/wallet/search-marketing-transactions', tabID === 'company-total');
    });

    $('.marketing_transactions_block  .sort').on('click', function (e) {
        e.preventDefault();

        if ($(this).hasClass('sort-asc')) {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-desc');
        } else {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-asc');
        }

        var table = $(this).parents('table');
        var tabID = $(this).parents('.marketing_transactions_block').attr('id');
        var icoPart = $('#marketing_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('marketing', icoPart, table, '/admin/wallet/search-marketing-transactions', tabID === 'company-total');
    });

    // Run requests to search company transactions
    $('#company_part_filter li').each(function () {
        var icoPart = $(this).attr('id');
        var tabID = $(this).find('a').attr('href');
        var table = $(tabID).find('table');

        searchUser('company', icoPart, table, '/admin/wallet/search-company-transactions', tabID === '#company-total');
    });

    $('.company_transactions_block .pagination').on('click', '.page-link', function (e) {
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

        var table = $(this).parents('.company_transactions_block').find('table');
        var tabID = $(this).parents('.company_transactions_block').attr('id');
        var icoPart = $('#company_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('foundation', icoPart, table, '/admin/wallet/search-company-transactions', tabID === 'company-total');
    });

    $('.company_transactions_block  .sort').on('click', function (e) {
        e.preventDefault();

        if ($(this).hasClass('sort-asc')) {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-desc');
        } else {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-asc');
        }

        var table = $(this).parents('table');
        var tabID = $(this).parents('.company_transactions_block').attr('id');
        var icoPart = $('#company_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('company', icoPart, table, '/admin/wallet/search-company-transactions', tabID === 'company-total');
    });
});

/***/ })

/******/ });