<?php

/**
 * Main Page
 */
Route::get('/', 'IndexController@main');
Route::get('states', 'IndexController@getStates');
Route::get('confirmation', 'IndexController@confirmation');
Route::get('invitation', 'IndexController@invitation');
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
    Route::post('logout', 'AuthController@logout');

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

    Route::get('profile-settings', 'UserController@profileSettings');
    Route::post('profile-settings/update-wallet', 'UserController@updateWallet');
    Route::post('profile-settings/change-password', 'UserController@changePassword');
    Route::post('profile-settings/upload-identity-documents', 'UserController@uploadIdentityDocuments');
    Route::post('profile-settings/upload-address-documents', 'UserController@uploadAddressDocuments');

    Route::get('invite-friend', 'UserController@invite');
    Route::post('invite-friend', 'UserController@saveInvitation');

    Route::get('debit-card', 'UserController@debitCard');
    Route::post('debit-card', 'UserController@saveDebitCard');

    Route::get('debit-card-documents', 'UserController@debitCardIdentityDocuments');
    Route::post('debit-card-documents', 'UserController@uploadDCIdentityDocuments');

    Route::get('debit-card-address', 'UserController@debitCardAddressDocuments');
    Route::post('debit-card-address', 'UserController@uploadDCAddressDocuments');

    Route::get('debit-card-success', 'UserController@debitCardSuccess');

    Route::post('remove-document', 'UserController@removeDocument');
    Route::post('change-password', 'UserController@changePassword');
});

/**
 * ADMIN
 */
Route::group(['prefix' => 'admin'], function () {
    Route::get('users', 'AdminController@users');

    Route::get('profile', 'AdminController@profile');
    Route::post('profile', 'AdminController@saveProfile');
    Route::post('profile/remove', 'AdminController@removeProfile');

    Route::get('document', 'AdminController@document');
    Route::post('document/approve', 'AdminController@approveDocument');
    Route::post('document/decline', 'AdminController@declineDocument');

    Route::post('wallet/znx', 'AdminController@addZNX');
    Route::post('wallet', 'AdminController@updateWallet');

});

//Route::get('/test-email', function () {
//    return new App\Mail\IcoRegistration('http://zantepay');
//});
