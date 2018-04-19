require('./helpers');

const searchUser = (type, part, table, url, allowGrant) => {
    const partFilter = part !== 'ICO_TOTAL' ? part : '';

    // status filter
    const statusFilter = [];

    // @todo Collect status filters

    // page
    const paginator = table.next('nav').find('.pagination');
    const activePage = parseInt(paginator.find('.page-item.active .page-link').html());
    const page = isNaN(activePage) ? 1 : activePage;

    // sort
    let sortIndex = 0;
    let sortOrder = 'asc';

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
    table.find('tbody').append(
        $('<tr />').addClass('operation-in-progress')
            .append(
                $('<td />').attr('colspan', 4)
                    .append(
                        $('<a />').attr('href', '').addClass('update-icon is-active')
                    )
            )
    );

    paginator.find('.page-item').empty();


    axios.get(
        url,
        {
            params: {
                'part_filter': partFilter,
                'status_filter': statusFilter,
                'page': page,
                'sort_index': sortIndex,
                'sort_order': sortOrder
            }
        }
    )
        .then(
            response => {
                table.find('.operation-in-progress').remove();

                response.data.transactionsList.forEach(
                    transaction => {

                        let transactionStatus = '---';

                        if (allowGrant) {
                            transactionStatus = transaction.status === ''
                                ? '<button class="btn btn--medium btn--shadowed-light" type="button">Issue Token</button>'
                                : transaction.status;
                        }

                        table.find('tbody')
                            .append(
                                $('<tr />')
                                    .append(
                                        $('<td />').html(transaction.user)
                                    )
                                    .append(
                                        $('<td />').html(transaction.address)
                                    )
                                    .append(
                                        $('<td />').html(transaction.amount)
                                    )
                                    .append(
                                        $('<td />').html(transactionStatus)
                                    )
                            )

                    }
                );

                for (let i = 1; i <= response.data.paginator.totalPages; i++) {
                    const itemClass = response.data.paginator.currentPage == i ? 'page-item active' : 'page-item';

                    paginator.append(
                        $('<li />').addClass(itemClass)
                            .append(
                                $('<a />').addClass('page-link').attr('href', '#').html(i)
                            )
                    )
                }

                // add pagination
                if (response.data.paginator.totalPages > 1) {
                    paginator.prepend(
                        $('<li />').addClass('page-item')
                            .append(
                                $('<a />').addClass('page-link prev-page-link').attr('href', '#').html('Previous')
                            )
                    )
                        .append(
                            $('<li />').addClass('page-item')
                                .append(
                                    $('<a />').addClass('page-link next-page-link').attr('href', '#').html('Next')
                                )
                        )

                }

            }
        )
        .catch(
            error => {
                table.find('.operation-in-progress').remove();

                const {message} = error.response.data;

                showError(message)
            }
        )

}

$(document).ready(function () {

    // Grant Marketing Coins
    $('#grant_marketing_coins').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const grantInfo = {
            'address': $('#grant_marketing_address').val(),
            'amount': $('#grant_marketing_amount').val()
        }

        axios.post(
            '/admin/wallet/grant-marketing-coins',
            qs.stringify(grantInfo)
        )
            .then(
                () => {
                    hideSpinner(button);

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#grant-coins-modal'
                            },
                            type: 'inline',
                            closeOnBgClick: true
                        }
                    );
                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {message} = error.response.data;

                    showError(message)
                }
            )
    });

    // Grant Company Coins
    $('#grant_company_coins').on('click', function (event) {
        event.preventDefault();

        const button = $(this);
        showSpinner(button);
        clearErrors();

        const grantInfo = {
            'address': $('#grant_company_address').val(),
            'amount': $('#grant_company_amount').val()
        }

        axios.post(
            '/admin/wallet/grant-company-coins',
            qs.stringify(grantInfo)
        )
            .then(
                () => {
                    hideSpinner(button);

                    $.magnificPopup.open(
                        {
                            items: {
                                src: '#grant-coins-modal'
                            },
                            type: 'inline',
                            closeOnBgClick: true
                        }
                    );
                }
            )
            .catch(
                error => {
                    hideSpinner(button);

                    const {message} = error.response.data;

                    showError(message)
                }
            )
    });

    // Run requests to search ico transactions
    $('#ico_part_filter li').each(function () {
        const icoPart = $(this).attr('id');
        const tabID = $(this).find('a').attr('href');
        const table = $(tabID).find('table');

        searchUser('ico', icoPart, table, '/admin/wallet/search-ico-transactions', tabID === '#total');
    });

    $('.ico_transactions_block .pagination').on('click', '.page-link', function (e) {
        e.preventDefault();

        const parent = $(this).parents('.pagination');

        let activeItem = parent.find('.page-item.active');
        let nextItem = $(this).parents('.page-item');

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

        const table = $(this).parents('.ico_transactions_block').find('table');
        const tabID = $(this).parents('.ico_transactions_block').attr('id');
        const icoPart = $('#ico_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

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

        const table = $(this).parents('table');
        const tabID = $(this).parents('.ico_transactions_block').attr('id');
        const icoPart = $('#ico_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('ico', icoPart, table, '/admin/wallet/search-ico-transactions', tabID === 'total');
    });


    // Run requests to search marketing transactions
    $('#marketing_part_filter li').each(function () {
        const icoPart = $(this).attr('id');
        const tabID = $(this).find('a').attr('href');
        const table = $(tabID).find('table');

        searchUser('marketing', icoPart, table, '/admin/wallet/search-marketing-transactions', tabID === '#marketing-total');
    });

    $('.marketing_transactions_block .pagination').on('click', '.page-link', function (e) {
        e.preventDefault();

        const parent = $(this).parents('.pagination');

        let activeItem = parent.find('.page-item.active');
        let nextItem = $(this).parents('.page-item');

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

        const table = $(this).parents('.marketing_transactions_block').find('table');
        const tabID = $(this).parents('.marketing_transactions_block').attr('id');
        const icoPart = $('#marketing_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

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

        const table = $(this).parents('table');
        const tabID = $(this).parents('.marketing_transactions_block').attr('id');
        const icoPart = $('#marketing_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('marketing', icoPart, table, '/admin/wallet/search-marketing-transactions', tabID === 'company-total');
    });


    // Run requests to search company transactions
    $('#company_part_filter li').each(function () {
        const icoPart = $(this).attr('id');
        const tabID = $(this).find('a').attr('href');
        const table = $(tabID).find('table');

        searchUser('company', icoPart, table, '/admin/wallet/search-company-transactions', tabID === '#company-total');
    });

    $('.company_transactions_block .pagination').on('click', '.page-link', function (e) {
        e.preventDefault();

        const parent = $(this).parents('.pagination');

        let activeItem = parent.find('.page-item.active');
        let nextItem = $(this).parents('.page-item');

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

        const table = $(this).parents('.company_transactions_block').find('table');
        const tabID = $(this).parents('.company_transactions_block').attr('id');
        const icoPart = $('#company_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

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

        const table = $(this).parents('table');
        const tabID = $(this).parents('.company_transactions_block').attr('id');
        const icoPart = $('#company_part_filter').find('a[href="#' + tabID + '"]').parent().attr('id');

        searchUser('company', icoPart, table, '/admin/wallet/search-company-transactions', tabID === 'company-total');
    });

});


