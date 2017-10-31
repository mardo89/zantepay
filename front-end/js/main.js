$(document).ready(function() {

  //countdown
  if ( $('.js-countdown').length ) {
    var date = $('.js-countdown').data('date');
    $('.js-countdown').countdown(date)
      .on('update.countdown', function(event) {
        var dateFormat = '<span class="countdown-group"><span class="countdown-txt-lg">%D</span><span class="countdown-txt-sm">Days</span></span>';
        dateFormat += '<span class="countdown-dots">:</span><span class="countdown-group"><span class="countdown-txt-lg">%H</span><span class="countdown-txt-sm">Hours</span></span>';
        dateFormat += '<span class="countdown-dots">:</span><span class="countdown-group"><span class="countdown-txt-lg">%M</span><span class="countdown-txt-sm">Minutes</span></span>';
        dateFormat += '<span class="countdown-dots">:</span><span class="countdown-group"><span class="countdown-txt-lg">%S</span><span class="countdown-txt-sm">Seconds</span></span>';

        $(this).html(event.strftime(dateFormat));
      })
      .on('finish.countdown', function(event) {
        $(this).html('This offer has expired!').parent().addClass('disabled');
      });
  }

  //circle progress bar
  if ( $('.js-lp-progress').length ) {
    var percent = $('.js-lp-progress').data('percent') * 1;
    $('.js-lp-progress-blured').circleProgress({
      value: percent,
      emptyFill: "rgba(255,255,255,.6)",
      size: 292,
      thickness: 12,
      startAngle: -Math.PI / 2,
      fill: {
        color: "#f92112" 
      }
    });
    $('.js-lp-progress').circleProgress({
      value: percent,
      emptyFill: "rgba(255,255,255,.6)",
      size: 285,
      thickness: 6,
      startAngle: -Math.PI / 2,
      fill: {
        color: "#f92112" 
      }
    });
  }

  //hamburger
  $(document).on('click', '.hamburger', function() {
    $('.masthead__menu').slideToggle();
    $('.hamburger').toggleClass('is-active');
  });

  //popups
  if ( $('.js-popup-link').length ) {
    $('.js-popup-link').magnificPopup({
      type:'inline',
      midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
      mainClass: 'mfp-fade',
      fixedContentPos: false,
      callbacks: {
       open: function() {
        $('body').addClass('noscroll');
       },
       close: function() {
         $('body').removeClass('noscroll');
       }
      }
    });
  }

  //scroll navigaion
  $('.header-lp .navigation a, .scroll-button').on('click',function (e) {
    e.preventDefault();
    var target = this.hash;
    $target = $(target);
    $('html, body').stop().animate({
      'scrollTop':  $target.offset().top
    }, 900, 'swing', function () {
      window.location.hash = target;
    });
  });

  //datepicker
  if ( $('[data-toggle="datepicker"]').length ) {
    $('[data-toggle="datepicker"]').datepicker();
  }
});