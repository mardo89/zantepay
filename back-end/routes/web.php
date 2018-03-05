<?php

/**
 * Main Page
 */
Route::get('/', 'IndexController@main');
Route::get('confirmation', 'IndexController@confirmActivation');
Route::get('invitation', 'IndexController@confirmInvitation');
Route::get('reset-password', 'IndexController@resetPassword');
Route::get('password', 'IndexController@confirmPasswordReset');
Route::post('ico-registration', 'IndexController@saveRegistration');
Route::post('seed-investor', 'IndexController@saveInvestor');


/**
 * Mailing
 */
Route::group(['prefix' => 'mail'], function () {

    Route::post('activate-account', 'MailController@activateAccount');
    Route::post('contact-us', 'MailController@contactUs');

});


/**
 * Account
 */
Route::group(['prefix' => 'account'], function () {

    Route::post('register', 'AccountController@register');
    Route::post('login', 'AccountController@login');
    Route::post('logout', 'AccountController@logout');
    Route::post('reset-password', 'AccountController@resetPassword');
    Route::post('save-password', 'AccountController@savePassword');

    Route::get('fb', 'AccountController@toFacebookProvider');
    Route::get('fb/callback', 'AccountController@FacebookProviderCallback');

    Route::get('google', 'AccountController@toGoogleProvider');
    Route::get('google/callback', 'AccountController@GoogleProviderCallback');

});


/**
 * USER
 */
Route::group(['prefix' => 'user'], function () {

    Route::get('states', 'UserController@getStates');
    Route::get('profile', 'UserController@profile');
    Route::post('profile', 'UserController@saveProfile');

    Route::get('profile-settings', 'UserController@profileSettings');
    Route::post('profile-settings/remove-document', 'UserController@removeDocument');
    Route::post('profile-settings/upload-identity-documents', 'UserController@uploadIdentityDocuments');
    Route::post('profile-settings/upload-address-documents', 'UserController@uploadAddressDocuments');
    Route::post('profile-settings/change-password', 'UserController@changePassword');
    Route::post('profile-settings/update-wallet', 'UserController@updateWallet');

    Route::get('invite-friend', 'UserController@invite');
    Route::post('invite-friend', 'UserController@saveInvitation');

    Route::get('wallet', 'UserController@wallet');
    Route::post('wallet/address', 'UserController@createWalletAddress');
    Route::post('wallet/rate-calculator', 'UserController@rateCalculator');

    Route::get('debit-card', 'UserController@debitCard');
    Route::post('debit-card', 'UserController@saveDebitCard');
    Route::get('debit-card-documents', 'UserController@debitCardIdentityDocuments');
    Route::post('debit-card-documents', 'UserController@uploadDCIdentityDocuments');
    Route::get('debit-card-address', 'UserController@debitCardAddressDocuments');
    Route::post('debit-card-address', 'UserController@uploadDCAddressDocuments');
    Route::get('debit-card-success', 'UserController@debitCardSuccess');

});

/**
 * ADMIN
 */
Route::group(['prefix' => 'admin'], function () {

    Route::get('users', 'AdminController@users')->middleware('auth.manager');

    Route::get('profile', 'AdminController@profile')->middleware('auth.manager');
    Route::post('profile', 'AdminController@saveProfile')->middleware('auth.admin');
    Route::post('profile/remove', 'AdminController@removeProfile')->middleware('auth.admin');

    Route::get('document', 'AdminController@document')->middleware('auth.manager');
    Route::post('document/approve', 'AdminController@approveDocument')->middleware('auth.manager');
    Route::post('document/decline', 'AdminController@declineDocument')->middleware('auth.manager');

    Route::get('wallet', 'AdminController@wallet')->middleware('auth.admin');
    Route::post('wallet/znx', 'AdminController@addZNX')->middleware('auth.admin');
    Route::post('wallet', 'AdminController@updateWallet')->middleware('auth.admin');

});

//Route::get('/test-email', function () {
//    return new App\Mail\ResetPassword('1234567890');
//});
