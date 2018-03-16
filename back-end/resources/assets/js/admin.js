require('./bootstrap');

$(document).ready(function () {
    //hamburger
    $(document).on('click', '.hamburger', function () {
        $('.masthead__menu').slideToggle();
        $('.hamburger').toggleClass('is-active');
    });

    // Datepicker
    if ($('[data-toggle="datepicker"]').length) {
        $('[data-toggle="datepicker"]').datepicker();
    }

    // Popups
    if ($('.js-popup-link').length) {
        $('.js-popup-link').magnificPopup({
            type: 'inline',
            midClick: true,
            mainClass: 'mfp-fade',
            fixedContentPos: false,
            callbacks: {
                open: function () {
                    $('body').addClass('noscroll');
                },
                close: function () {
                    $('body').removeClass('noscroll');
                }
            }
        });
    }

    // Tabs
    $(document).on('click', '.tabs-head a', function(e) {
        e.preventDefault();
        var thisHref = $(this).attr('href');
        $(this).closest('.tabs-head').find('li').removeClass('is-active');
        $(thisHref).closest('.tabs-wrap').find('.tab-body').removeClass('is-active');
        $(this).parent().addClass('is-active');
        $(thisHref).addClass('is-active');
        if ( thisHref != '#profile') {
            $('.dashboard-top-panel-row .form-group').hide();
        } else {
            $('.dashboard-top-panel-row .form-group').show();
        }
    });

    // Logout
    $('#logout-btn').on('click', function (event) {
        event.preventDefault();

        axios.post(
            '/account/logout',
            qs.stringify(
                {}
            )
        )
            .then(
                () => {
                    window.location = '/';
                }
            )
    });

    const tableSorter = (table, column, direction) => {
        let rows = table.find('tbody tr');

        let compare = (x, y) => x < y;

        if (direction) {
            compare = (x, y) => x >= y
        }

        for(let i=0; i < rows.length - 1; i++) {

            for(let j=i+1; j < rows.length; j++) {

                const val_i = $(rows[i]).find('td').eq(column).html();
                const val_j = $(rows[j]).find('td').eq(column).html();

                if (compare(val_i, val_j)) {
                    let tmp_row = rows[i];
                    rows[i] = rows[j];
                    rows[j] = tmp_row;
                }

            }
        }

    }

    // Sorter
    $('body').on('click', '.sort', function (event) {
        const table = $(this).parents('table');
        const column = $(this).index();
        let direction = 0;

        if ($(this).hasClass('sort-desc')) {
            $(this).removeClass('sort-desc');
            $(this).addClass('sort-asc');

            direction = 1;
        } else {
            $(this).removeClass('sort-asc');
            $(this).addClass('sort-desc');

            direction = 2;
        }

        tableSorter(table, column, direction)
    })


});


