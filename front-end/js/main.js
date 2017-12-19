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
    $('.tabs-head li').removeClass('is-active');
    $('.tab-body').removeClass('is-active');
    $(this).parent().addClass('is-active');
    $(thisHref).addClass('is-active');
    if ( thisHref != '#profile') {
      $('.dashboard-top-panel-row .form-group').hide();
    } else {
      $('.dashboard-top-panel-row .form-group').show();
    }
  });


});
