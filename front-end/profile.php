<!DOCTYPE html>
<?php

if(!isset($_SESSION)){
  session_start();
  $sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';

  if(isset($sessData['userLoggedIn']) && $sessData['userLoggedIn']){   //Already Logged in
    
  }
  else
  {
    header("Location:index.php");
  }
}

?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zantepay</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Exo+2:300,300i,400,400i,500,700" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
  </head>
  <body>
  <!--[if lt IE 10]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
  <![endif]-->

    <header class="header">
      <div class="masthead">
        <div class="container">
          <div class="masthead__row">
            <div class="masthead__left">
              <a href="/" class="logo" title="Zantepay">
                <img src="images/logo-large.png" alt="Zantepay Logo">
              </a>
            </div>
            
            <div class="hamburger hamburger--slider">
              <div class="hamburger-box">
                <div class="hamburger-inner"></div>
              </div>
            </div>

            <div class="masthead__menu">
              <nav class="navigation">
                <ul>
                  <li class="current-menu-item"><a href="profile.html">Profile</a></li>
                  <li><a href="refer-a-friend.html">Refer a Friend</a></li>
                  <li><a href="wallet.html">Wallet</a></li>
                  <li><a href="debit-card.html">Zantepay Debit Card</a></li>
                </ul>
              </nav>

              <div class="masthead__right">
                <a id="btn_logout"  href="#" class="btn btn--small btn--shadowed-dark">Logout</a>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </header>

    <main class="main main-dashboard">
      <div class="container">
        <form action="">
          <div class="dashboard-group-sm">
            <h2 class="h4 headline-mb">Main information:</h2>
            <div class="row">
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field1">First name:</label>
                  <input class="input-field" type="text" name="f-name" id="field1">
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field2">Last name:</label>
                  <input class="input-field" type="text" name="l-name" id="field2">
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field3">Email:</label>
                  <input class="input-field" type="email" name="email" id="field3">
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field4">Phone number:</label>
                  <input class="input-field" type="text" name="tel" id="field4">
                </div>
              </div>
            </div>
          </div>

          <div class="dashboard-group-sm">
            <h2 class="h4 headline-mb">Address:</h2>
            <div class="row">
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label">Country:</label>
                  <select name="contry" class="input-field">
                    <option disabled selected></option>
                    <option value="">Country 1</option>
                    <option value="">Country 2</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label">State / County:</label>
                  <select name="contry" class="input-field">
                    <option disabled selected></option>
                    <option value="">state 1</option>
                    <option value="">state 2</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field5">City:</label>
                  <input class="input-field" type="text" name="city" id="field5">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field6">Address:</label>
                  <input class="input-field" type="text" name="address" id="field6">
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field7">Postcode:</label>
                  <input class="input-field" type="text" name="postcode" id="field7">
                </div>
              </div>
            </div>
          </div>

          <div class="dashboard-group-sm">
            <h2 class="h4 headline-mb">Identification:</h2>
            <div class="row">
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field8">Passport / Government ID:</label>
                  <input class="input-field" type="text" name="government" id="field8">
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field9">Passport / ID expiry date:</label>
                  <div class="date-picker-wrap">
                    <input class="input-field date-picker-inp" type="text" name="expiry" id="field9" data-toggle="datepicker">
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field10">Date of birth:</label>
                  <div class="date-picker-wrap">
                    <input class="input-field date-picker-inp" type="text" name="birth" id="field10" data-toggle="datepicker">
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6">
                <div class="form-group">
                  <label class="field-label" for="field11">Country of birth:</label>
                  <input class="input-field" type="text" name="country-birth" id="field11">
                </div>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn--shadowed-light btn--medium btn--160">Save</button>
        </form>
      </div>
    </main>

    
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <script src="js/datepicker.js"></script>
    <script src="js/main.js" type="text/javascript"></script>
  </body>
</html>