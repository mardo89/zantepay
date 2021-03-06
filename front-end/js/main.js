$(document).ready(function() {

  //set cookie
  function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
      var d = new Date();
      d.setTime(d.getTime() + expires * 1000);
      expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
      options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
      updatedCookie += "; " + propName;
      var propValue = options[propName];
      if (propValue !== true) {
        updatedCookie += "=" + propValue;
      }
    }
    // console.log(updatedCookie);
    document.cookie = updatedCookie;
  }

  //delete cookie
  function deleteCookie(name) {
    setCookie(name, "", {
      expires: -1
    })
  }

  //get cookie
  function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
      "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
  }

  $(document).on('click', '.js-close-banner', function() {
    $(this).closest('.h-banner').removeClass('is-active');
    setCookie('hideNoticeBanner', 'true', {path: '/', expires: 86400}); //1day cookie
  });

  if ( !getCookie('hideNoticeBanner') && $('.h-banner').length ) {
    $('.h-banner').addClass('is-active');
  }

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
      midClick: true,
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

  $('.js-popup-video').magnificPopup({
      type: 'iframe',
      midClick: true,
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

  
  if ( $('.js-open-noclose-popup').length ) {
    $('.js-open-noclose-popup').magnificPopup({
      type:'inline',
      midClick: true,
      showCloseBtn: false,
      closeOnBgClick: false,
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
  $(document).on( 'click', '.js-close-popup', function() {
    $.magnificPopup.close();
  });

  //accordion
  $(document).on('click', '.js-accordion .accordion__head a', function() {
    var thisID = $(this).attr('href');
    if ( $(this).closest('.accordion__head').hasClass('is-active') ) {
      $(this).parents('.js-accordion').find('.accordion__head').removeClass('is-active');
      $(this).parents('.js-accordion').find('.accordion__body').slideUp();
    } else {
      $(this).parents('.js-accordion').find('.accordion__head').removeClass('is-active');
      $(this).parent().addClass('is-active');
      $(this).parents('.js-accordion').find('.accordion__body').not(thisID).slideUp();
      $(thisID).slideDown();
    }
    return false;
  });

  //scroll navigaion
  $('.header-lp .navigation a[href^="#"], .scroll-button').on('click',function (e) {
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

  //Log in
  $("#frm_signin").submit(function(event){
    showSpin(true);
    $.ajax({
      data: {
        submitStr:'loginSubmit',
        email:$('#login_email').val(),
        password:$('#login_password').val()
      },
      method: "POST",
      url: $(this).attr('action'),
      success: function(response){
        var result = JSON.parse(response);
        if(result.type == "Error")            //Signup Error
          showLoginErr(result, true);
        else{                                   //Signup Success
          showLoginErr(result, false);
          $.magnificPopup.close();
          window.location.href = 'profile.php';
        }
        showSpin(false);
      }  
    });

    event.preventDefault();
  });

  //Sing up
  $("#frm_signup").submit(function(event){
    showSpin(true);
    $.ajax({
      data: {
        submitStr:'signupSubmit',
        email:$('#signup_email').val(),
        password:$('#signup_pwd').val(),
        cnf_password:$('#signup_cnf_pwd').val()
      },
      method: "POST",
      url: "src/login/userAccount.php",
      success: function(response){
        var result = JSON.parse(response);
        if(result.type == "Error"){             //Signup Error
          showSpin(false);
          showSignupErr(result, true);
        }
        else{                                   //Signup Success
          showSignupErr(result, false);
          $.magnificPopup.close();
          $.magnificPopup.open({items: {src: '#confirm-modal'},type: 'inline',closeOnBgClick:false});
          sendEmail();
        }
      }  
    });
    event.preventDefault();
  });

  //Signup Err
  function showSignupErr(result, show){
    $('#span_err').text(result.type);
    $('#span_msg').text(result.msg);
    if(show)
      $('.signup_err').show();
    else
      $('.signup_err').hide();
  }
  //Login Err
  function showLoginErr(result, show){
    $('#login_err').text(result.type);
    $('#login_err_msg').text(result.msg);
    if(show)
      $('.login_err').show();
    else
      $('.login_err').hide();
  }

  //Email Send Function
  function sendEmail(){
    showSpin(true);
    $.ajax({
      data:{
        sendEmail:true
      },
      method: "POST",
      url: $('#email_send_link').attr('href'),
      success: function(response){
        showSpin(false);
      }
    });
  }
  
  //Spin function
  function showSpin(visible){
    if(visible)
      $('#spin').show();
    else
      $('#spin').hide();
  }

  $('#email_send_link').on("click", function(event){
    sendEmail();
    event.preventDefault();
  });

  $("#btn_logout").click( function(event){
    
    showSpin(true);
    $.ajax({
      data: {
        submitStr:'logoutSubmit'
      },
      method: "POST",
      url: "src/login/userAccount.php",
      success: function(response){
        var result = JSON.parse(response);
        console.log(result);
        window.location.href = 'index.php';
        
        showSpin(false);
      }  
    });

    //event.preventDefault();
  });

  //tabs
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

  //open tabs by url
  if ( location.hash && $('.tabs-head') ) {
    var tabHref = location.hash;
    $('.tabs-head li').removeClass('is-active');
    $('.tab-body').removeClass('is-active');
    $('a[href="' + tabHref + '"]').parent().addClass('is-active');
    $(tabHref).addClass('is-active');
  }

  //open resset password by url
  if ( location.hash == '#forgot-password' ) {
    $.magnificPopup.open({items: {src: '#forgot-password'},type: 'inline'});
  }

  //hp shapes
  if ( $('#particles-js').length ) {
    window.addEventListener("load", function () {
      var lineColor = $('.particles-js-black').length ? '#000' : '#fff';
      particlesJS('particles-js',
      
        {
          "particles": {
            "number": {
              "value": 80,
              "density": {
                "enable": true,
                "value_area": 800
              }
            },
            "color": {
              "value": "#f92112"
            },
            "shape": {
              "type": "circle",
              "stroke": {
                "width": 0,
                "color": "#000000"
              },
              "polygon": {
                "nb_sides": 5
              },
              "image": {
                "src": "img/github.svg",
                "width": 100,
                "height": 100
              }
            },
            "opacity": {
              "value": 0.7,
              "random": false,
              "anim": {
                "enable": false,
                "speed": 3,
                "opacity_min": 0.2,
                "sync": false
              }
            },
            "size": {
              "value": 5,
              "random": true,
              "anim": {
                "enable": false,
                "speed": 6,
                "size_min": 0.1,
                "sync": false
              }
            },
            "line_linked": {
              "enable": true,
              "distance": 150,
              "color": lineColor,
              "opacity": 0.5,
              "width": 1
            },
            "move": {
              "enable": true,
              "speed": 4,
              "direction": "none",
              "random": false,
              "straight": false,
              "out_mode": "out",
              "attract": {
                "enable": false,
                "rotateX": 600,
                "rotateY": 1200
              }
            }
          },
          "interactivity": {
            "detect_on": "canvas",
            "events": {
              "onhover": {
                "enable": false,
              },
              "onclick": {
                "enable": false,
              },
              "resize": true
            },
            "modes": {
              "grab": {
                "distance": 400,
                "line_linked": {
                  "opacity": 1
                }
              },
              "bubble": {
                "distance": 400,
                "size": 40,
                "duration": 2,
                "opacity": 8,
                "speed": 5
              },
              "repulse": {
                "distance": 200
              },
              "push": {
                "particles_nb": 4
              },
              "remove": {
                "particles_nb": 2
              }
            }
          },
          "retina_detect": true,
          "config_demo": {
            "hide_card": false,
            "background_color": "#b61924",
            "background_image": "",
            "background_position": "50% 50%",
            "background_repeat": "no-repeat",
            "background_size": "cover"
          }
        }

      );
    });
  }

  $(document).on('click', '.m-dropdown > a', function() {
    $(this).toggleClass('is-active');
    $(this).siblings('ul').slideToggle();
  });


  // ALPHA APP
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
  
  var newsletterTimeoutHandle;
  function openNewsletterPopup() {
      newsletterTimeoutHandle = window.setTimeout(function() {
          $.magnificPopup.open({
              items: {
                  src: '#newsletter-modal'
              },
              type:'inline',
              midClick: true,
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
          setCookie('hideNewsletterPopup', 'true', {path: '/', expires: 86400}); //1day cookie
      }, 6000);
  }

  if( $('#newsletter-modal').length && !getCookie('hideNewsletterPopup')) {
      openNewsletterPopup();
  }
});
