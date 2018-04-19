require('./helpers');

$(document).ready(function () {

    $('#search_mail_events_frm').on('submit', function (event) {
        event.preventDefault();

        // roles filter
        const typeFilter = [];

        $(this).find('input[name="type-filter"]:checked').each(function () {
            typeFilter.push($(this).val());
        });

        // status filter
        const statusFilter = [];

        $(this).find('input[name="status-filter"]:checked').each(function () {
            statusFilter.push($(this).val());
        });

        // page
        const activePage = parseInt($('.page-item.active .page-link').html());
        const page = isNaN(activePage) ? 1 : activePage;

        // sort
        let sortIndex = 0;
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
            '/service/mail-events/search',
            {
                params: {
                    'type_filter': typeFilter,
                    'status_filter': statusFilter,
                    'page': page,
                    'sort_index': sortIndex,
                    'sort_order': sortOrder
                }
            }
        )
            .then(
                response => {
                    hideSpinner(button);

                    $('#events-list tbody').empty();
                    $('.pagination .page-item').empty().hide();

                    response.data.eventsList.forEach(
                        event => {

                            $('#events-list tbody')
                                .append(
                                    $('<tr />').attr('id', event.id)
                                        .append(
                                            $('<td />').html(event.date)
                                        )
                                        .append(
                                            $('<td />').addClass('col-center').html(event.event)
                                        )
                                        .append(
                                            $('<td />').html(event.to)
                                        )
                                        .append(
                                            $('<td />').html(event.status)
                                        )
                                        .append(
                                            $('<td />')
                                                .append(
                                                    event.isSuccess ? '' : $('<a />').addClass('send-link resend-email').attr('href', '').html('Resend')
                                                )
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

        $('#search_mail_events_frm').trigger('submit');
    });

    $('#events-list .sort').on('click', function(e) {
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

    $('#search_mail_events_frm button[type="submit"]').on('click', function(e) {
        e.preventDefault();

        $('.pagination .page-item').empty();

        $('#search_mail_events_frm').trigger('submit');
    });


    $('#events-list').on('click', '.resend-email',function(e) {
        e.preventDefault();

        const id = $(this).parents('tr').attr('id');

        const button = $(this);

        button.hide();

        axios.post(
            '/service/mail-events/process',
            qs.stringify(
                {
                    id
                }
            )
        )
            .then(
                () => {
                    button.remove();
                }
            )
            .catch(
                error => {
                    button.show();

                    const {message} = error.response.data;

                    showError(message)
                }
            )

    });
});


