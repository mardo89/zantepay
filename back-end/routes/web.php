<?php

/**
 * Main Page
 */
Route::get('/', 'IndexController@main');
Route::get('states', 'IndexController@getStates');
Route::post('ico-registration', 'IndexController@saveRegistration');


/**
 * Mailing
 */
Route::group(['prefix' => 'mail'], function () {
    Route::post('activate-account', 'MailController@activateAccount');
    Route::post('contact-us', 'MailController@contactUs');
    Route::post('invite-friend', 'MailController@inviteFriend');
    Route::post('ico-registration', 'MailController@icoRegistration');
});

/**
 * AUTHORIZATION
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('fb-login', 'AuthController@fbLogin');
    Route::post('logout', 'AuthController@logout');
    Route::get('activate', 'AuthController@activate');

    Route::get('fb', 'AuthController@toFacebookProvider');
    Route::get('fb/callback', 'AuthController@FacebookProviderCallback');

    Route::get('google', 'AuthController@toGoogleProvider');
    Route::get('google/callback', 'AuthController@GoogleProviderCallback');

});

/**
 * USER
 */
Route::group(['prefix' => 'user'], function () {
    Route::get('profile', 'UserController@profile');
    Route::post('profile', 'UserController@saveProfile');

    Route::get('invite-friend', 'UserController@invite');
    Route::post('invite-friend', 'UserController@saveInvitation');
});

//Route::get('/test-email', function () {
//    return new App\Mail\ActivateAccount('http://test-link?user=92837927492847');
//});
