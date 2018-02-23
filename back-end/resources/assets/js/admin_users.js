require('./helpers');

$(document).ready(function () {

    $('input[name="search-by-email"]').on('keyup', function (event) {
        const filterText = $(this).val().toLowerCase();

        $('#users-list tbody tr').each(function (index, element) {
            const userEmail = $(element).find('td:eq(1)').text().toLowerCase();
            const userName = $(element).find('td:eq(2)').text().toLowerCase();
            const userReferrer = $(element).find('td:eq(5)').text().toLowerCase();

            const isNotMatch = (
                userEmail.indexOf(filterText) === -1
                && userName.indexOf(filterText) === -1
                && userReferrer.indexOf(filterText) === -1
            );

            if (filterText.trim().length !== 0 && isNotMatch) {
                $(this).hide()
            } else {
                $(this).show();
            }
        });
    });

    $('.search-cross').on('click', function (event) {
        event.preventDefault();

        $('input[name="search-by-email"]').val('').trigger('keyup');
    });

    $('input[name="status-filter"]').on('change', function (event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            const userStatus = $(element).find('td:eq(4)').text().trim();

            if (refFilter == userStatus) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide()
                }
            }
        });
    });

    $('input[name="role-filter"]').on('change', function (event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            const userRole = $(element).find('td:eq(3)').text().trim();

            if (refFilter == userRole) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide()
                }
            }
        });
    });

    $('input[name="dc-filter"]').on('change', function (event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            const userRole = $(element).find('td:eq(1)').text().trim();

            if (refFilter == userRole) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide()
                }
            }
        });
    });

});


