require('./helpers');

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

        // page
        const activePage = parseInt($('.page-item.active .page-link').html());
        const page = isNaN(activePage) ? 1 : activePage;

        // sort
        let sortIndex = 0;
        let sortOrder = 'asc';

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
                    'page': page,
                    'sort_index': sortIndex,
                    'sort_order': sortOrder
                }
            }
        )
            .then(
                response => {
                    hideSpinner(button);

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

                    for(let i=1; i<=response.data.paginator.totalPages; i++) {
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

    $('.pagination').on('click', '.page-link', function(e) {
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

    $('#users-list .sort').on('click', function(e) {
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

    $('#search_user_frm button[type="submit"]').on('click', function(e) {
        e.preventDefault();

        $('.pagination .page-item').empty();

        $('#search_user_frm').trigger('submit');
    });

});


