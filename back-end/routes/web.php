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
Route::get('/faq', 'IndexController@faq');


/**
 * Mailing
 */
Route::group(['prefix' => 'mail'], function () {

    Route::post('activate-account', 'MailController@activateAccount');
    Route::post('contact-us', 'MailController@contactUs');
    Route::post('question', 'MailController@question');
    Route::post('invite-friend', 'MailController@inviteFriend');

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
    Route::post('accept-terms', 'UserController@acceptTerms');

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
    Route::post('wallet/transfer-eth', 'UserController@transferEth');
    Route::post('wallet/withdraw-eth', 'UserController@withdrawEth');

    Route::get('debit-card', 'UserController@debitCard');
    Route::post('debit-card', 'UserController@saveDebitCard');
    Route::get('debit-card-documents', 'UserController@debitCardIdentityDocuments');
    Route::post('debit-card-documents', 'UserController@uploadDCIdentityDocuments');
    Route::get('debit-card-address', 'UserController@debitCardAddressDocuments');
    Route::post('debit-card-address', 'UserController@uploadDCAddressDocuments');
    Route::get('debit-card-success', 'UserController@debitCardSuccess');

});

/**
 * ADMIN / MANAGER / SALES
 */
Route::group(['prefix' => 'admin'], function () {

    Route::get('users', 'ManagerController@users');
    Route::get('users/search', 'ManagerController@searchUsers');

    Route::get('profile', 'ManagerController@profile');
    Route::post('profile', 'AdminController@saveProfile');
    Route::post('profile/remove', 'AdminController@removeProfile');

    Route::get('document', 'ManagerController@document');
    Route::post('document/approve', 'ManagerController@approveDocument');
    Route::post('document/decline', 'ManagerController@declineDocument');

    Route::get('wallet', 'AdminController@wallet');
    Route::post('wallet', 'ManagerController@updateWallet');
    Route::post('wallet/add-ico-znx', 'ManagerController@addIcoZnx');
    Route::post('wallet/add-foundation-znx', 'ManagerController@addFoundationZnx');
    Route::post('wallet/grant-marketing-coins', 'AdminController@grantMarketingCoins');
    Route::post('wallet/grant-company-coins', 'AdminController@grantCompanyCoins');
});

//Route::get('/test-email', function () {
//    return new \App\Mail\ChangePassword();
//});
