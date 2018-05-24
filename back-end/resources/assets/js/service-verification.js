require('./helpers');

$(document).ready(function () {

    $('#search_verification_info_frm').on('submit', function (event) {
        event.preventDefault();

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
            '/service/verification/search',
            {
                params: {
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

                    $('#verification-info tbody').empty();
                    $('.pagination .page-item').empty().hide();

                    response.data.verificationInfo.forEach(
                        verification => {

                            $('#verification-info tbody')
                                .append(
                                    $('<tr />').attr('id', verification.id)
                                        .append(
                                            $('<td />').html(verification.user)
                                        )
                                        .append(
                                            $('<td />').html(verification.status)
                                        )
                                        .append(
                                            $('<td />')
                                                .append(
                                                    verification.canReset ? $('<button />').addClass('field-btn btn btn--shadowed-light btn--medium reset-verification').html('Reset') : ''
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

        $('#search_verification_info_frm').trigger('submit');
    });

    $('#verification-info .sort').on('click', function(e) {
        e.preventDefault();

        if ($(this).hasClass('sort-asc')) {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-desc');
        } else {
            $('.sort').removeClass('sort-asc').removeClass('sort-desc');

            $(this).addClass('sort-asc');
        }

        $('#search_verification_info_frm').trigger('submit');
    });

    $('#search_verification_info_frm button[type="submit"]').on('click', function(e) {
        e.preventDefault();

        $('.pagination .page-item').empty();

        $('#search_verification_info_frm').trigger('submit');
    });

    $('#verification-info').on('click', '.reset-verification',function(e) {
        e.preventDefault();

        const button = $(this);
        showSpinner(button);

        const user = {
            'uid': $(this).parents('tr').attr('id'),
        }

        axios.post(
            'verification/reset',
            qs.stringify(user)
        )
            .then(
                response => {

                    hideSpinner(button);
                    button.remove();

                    $('#' + user.uid).find('td:eq(1)').html(response.data.verificationStatus);
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

});


