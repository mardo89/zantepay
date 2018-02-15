require('./helpers');

$(document).ready(function () {

    $('input[name="search-by-email"]').on('keyup', function (event) {
        const filterText = $(this).val();

        const colToSearch = $(this).hasClass('wallets-search') ? 0 : 1;

        $('#users-list tbody tr').each(function (index, element) {
            const userEmail = $(element).find('td:eq(' + colToSearch + ')').text();

            if (filterText.trim().length !== 0 && userEmail.indexOf(filterText) === -1) {
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

    $('input[name="referrer-filter"]').on('change', function (event) {
        const refFilter = $(this).val();
        const isVisible = $(this).prop('checked');

        $('#users-list tbody tr').each(function (index, element) {
            const hasReferrer = $(element).find('td:eq(5)').text().trim().length === 0 ? 0 : 1;

            if (refFilter == hasReferrer) {
                if (isVisible) {
                    $(this).show();
                } else {
                    $(this).hide()
                }
            }
        });
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


