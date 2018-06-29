require('./helpers');

const saveState = () => {

    const roleFilter = [];

    $('input[name="role-filter"]:checked').each(function () {
        roleFilter.push($(this).val());
    });

    const referrerFilter = [];

    $('input[name="referrer-filter"]:checked').each(function () {
        referrerFilter.push($(this).val());
    });

    const statusFilter = [];

    $('input[name="status-filter"]:checked').each(function () {
        statusFilter.push($(this).val());
    });

    const dateFromFilter = $('input[name="date_from_filter"]').val();
    const dateToFilter = $('input[name="date_to_filter"]').val();
    const searchFilter = $('input[name="search-by-email"]').val();

    const userFormState = {
        'roleFilter': roleFilter,
        'referrerFilter': referrerFilter,
        'statusFilter': statusFilter,
        'dateFromFilter': dateFromFilter,
        'dateToFilter': dateToFilter,
        'searchFilter': searchFilter
    }


    sessionStorage.setItem('userFormState', JSON.stringify(userFormState));
};


const restoreState = () => {

    let userFormState = sessionStorage.getItem('userFormState');

    if (!userFormState) {
        return;
    }

    userFormState = JSON.parse(userFormState);

    $('input[name="role-filter"]').each(function () {
        if (userFormState.roleFilter.indexOf($(this).val()) === -1) {
            $(this).prop('checked', false);
        }
    });

    $('input[name="referrer-filter"]').each(function () {
        if (userFormState.referrerFilter.indexOf($(this).val()) === -1) {
            $(this).prop('checked', false);
        }
    });

    $('input[name="status-filter"]').each(function () {
        if (userFormState.statusFilter.indexOf($(this).val()) === -1) {
            $(this).prop('checked', false);
        }
    });

    $('input[name="date_from_filter"]').val(userFormState.dateFromFilter);
    $('input[name="date_to_filter"]').val(userFormState.dateToFilter);
    $('input[name="search-by-email"]').val(userFormState.searchFilter);

    $('#search_user_frm').trigger('submit');

}

$(document).ready(function () {

    $('#search_user_frm').on('submit', function (event) {
        event.preventDefault();

        // roles filter
        const roleFilter = [];

        $(this).find('input[name="role-filter"]:checked').each(function () {
            roleFilter.push($(this).val());
        });

        // status filter
        const statusFilter = [];

        $(this).find('input[name="status-filter"]:checked').each(function () {
            statusFilter.push($(this).val());
        });

        // referrer
        const referrerFilter = [];

        $(this).find('input[name="referrer-filter"]:checked').each(function () {
            referrerFilter.push($(this).val());
        });

        // email / name
        const nameFilter = $(this).find('input[name="search-by-email"]').val();

        // date filter
        const dateFromFiler = $(this).find('input[name="date_from_filter"]').val();
        const dateToFiler = $(this).find('input[name="date_to_filter"]').val();

        // page
        const activePage = parseInt($('.page-item.active .page-link').html());
        const page = isNaN(activePage) ? 1 : activePage;

        // sort
        let sortIndex = 2;
        let sortOrder = 'desc';

        if ($('.sort.sort-asc').length) {
            sortIndex = $('.sort.sort-asc').index();
            sortOrder = 'asc';
        }

        if ($('.sort.sort-desc').length) {
            sortIndex = $('.sort.sort-desc').index();
            sortOrder = 'desc';
        }

        const button = $(this).find('button[type="submit"]');
        showSpinner(button);
        clearErrors();
        $('.pagination').hide();

        axios.get(
            '/admin/users/search',
            {
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
            }
        )
            .then(
                response => {
                    hideSpinner(button);

                    saveState();

                    if ($('#total_found').length === 0) {

                        $('#users-list')
                            .before(
                                $('<p />')
                                    .addClass('primary-color pull-right')
                                    .attr('id', 'total_found')
                            )

                    }

                    $('#total_found').html('Total found: <strong>' + response.data.totalFound + '</strong>');

                    $('#users-list tbody').empty();
                    $('.pagination .page-item').empty().hide();

                    response.data.usersList.forEach(
                        user => {

                            $('#users-list tbody')
                                .append(
                                    $('<tr />').attr('id', user.id)
                                        .append(
                                            $('<td />').attr('width', 100).addClass('col-center')
                                                .append(
                                                    $('<div />').addClass('thumb-60')
                                                        .append(
                                                            $('<img />').attr({'src': user.avatar, 'alt': user.name})
                                                        )
                                                )
                                        )
                                        .append(
                                            $('<td />')
                                                .append(
                                                    $('<a />').addClass('primary-color').attr('href', user.profileLink).html(user.email)
                                                )
                                        )
                                        .append(
                                            $('<td />').html(user.name)
                                        )
                                        .append(
                                            $('<td />').html(user.registered)
                                        )
                                        .append(
                                            $('<td />').html(user.role)
                                        )
                                        .append(
                                            $('<td />').html(user.status)
                                        )
                                        .append(
                                            $('<td />').html(user.hasReferrals)
                                        )
                                )

                        }
                    );

                    for (let i = 1; i <= response.data.paginator.totalPages; i++) {
                        const itemClass = response.data.paginator.currentPage == i ? 'page-item active' : 'page-item';

                        $('.pagination')
                            .append(
                                $('<li />').addClass(itemClass)
                                    .append(
                                        $('<a />').addClass('page-link').attr('href', '#').html(i)
                                    )
                            )
                    }

                    // add pagination
                    if (response.data.paginator.totalPages > 1) {
                        $('.pagination')
                            .prepend(
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

                        $('.pagination').show();
                    }

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

    $('.pagination').on('click', '.page-link', function (e) {
        e.preventDefault();

        let activeItem = $('.page-item.active');
        let nextItem = $(this).parents('.page-item');

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

    $('.search-cross').on('click', function (event) {

        event.preventDefault();

        $('input[name="search-by-email"]').val('');

    });

    $('#import_users').on('click', function (event) {
        event.preventDefault();

        // roles filter
        const roleFilter = [];

        $('#search_user_frm').find('input[name="role-filter"]:checked').each(function () {
            roleFilter.push($(this).val());
        });

        // status filter
        const statusFilter = [];

        $('#search_user_frm').find('input[name="status-filter"]:checked').each(function () {
            statusFilter.push($(this).val());
        });

        // referrer
        const referrerFilter = [];

        $('#search_user_frm').find('input[name="referrer-filter"]:checked').each(function () {
            referrerFilter.push($(this).val());
        });

        // email / name
        const nameFilter = $('#search_user_frm').find('input[name="search-by-email"]').val();

        // date filter
        const dateFromFiler = $('#search_user_frm').find('input[name="date_from_filter"]').val();
        const dateToFiler = $('#search_user_frm').find('input[name="date_to_filter"]').val();

        // page
        const activePage = parseInt($('.page-item.active .page-link').html());
        const page = isNaN(activePage) ? 1 : activePage;

        // sort
        let sortIndex = 2;
        let sortOrder = 'desc';

        if ($('.sort.sort-asc').length) {
            sortIndex = $('.sort.sort-asc').index();
            sortOrder = 'asc';
        }

        if ($('.sort.sort-desc').length) {
            sortIndex = $('.sort.sort-desc').index();
            sortOrder = 'desc';
        }

        const params = {
            'role_filter': roleFilter,
            'status_filter': statusFilter,
            'referrer_filter': referrerFilter,
            'name_filter': nameFilter,
            'date_from_filter': dateFromFiler,
            'date_to_filter': dateToFiler,
            'page': page,
            'sort_index': sortIndex,
            'sort_order': sortOrder
        };

        const fileUrl = '/admin/users/import?' + jQuery.param( params );

        window.open(fileUrl, '_blank');

    });

    restoreState();

});


