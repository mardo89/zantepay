$(document).ready(function () {

    //preloader
    $(window).on('load', function() {
        $('.app-preloader').addClass('is-hidden');
        $('#blank-screen').addClass('is-zoomed');
        setTimeout(function() {
            $('#blank-screen').removeClass('is-active');
            $('#start-screen').addClass('is-active')
        }, 1600);
    });

    //flash animation for links
    $(document).on('click', '.app-step img', function() {
        var links = $(this).siblings('.link-area');
        if ( !links.hasClass('is-active') ) {
            links.addClass('is-active');
            setTimeout(function() {
                links.removeClass('is-active');
            }, 1000);
        }
    });

    //tabs
    $(document).on('click', '.link-area', function(e) {
        // e.preventDefault();
        var tabId = $(this).attr('href');
        $('.app-step').removeClass('is-active');
        $(tabId).addClass('is-active');

        //disable hash jumping
        // var windowTop = $(window).scrollTop();
        // setTimeout(function() {
        //   window.scrollTo(0, windowTop);
        // }, 1);
        return false;
    });

});


