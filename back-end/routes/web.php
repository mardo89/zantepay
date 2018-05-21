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
Route::post('newsletter/join', 'IndexController@saveNewsLetter');
Route::get('faq', 'IndexController@faq');
Route::get('bounty', 'IndexController@bounty');
Route::get('twitter-campaign', 'IndexController@twitterBountyCampaign');
Route::get('facebook-campaign', 'IndexController@facebookBountyCampaign');
Route::get('youtube-campaign', 'IndexController@youtubeBountyCampaign');
Route::get('blogs-article-campaign', 'IndexController@blogsBountyCampaign');
Route::get('support-campaign', 'IndexController@supportBountyCampaign');
Route::get('telegram-campaign', 'IndexController@telegramBountyCampaign');
Route::get('mobile-app', 'IndexController@mobileApp');
Route::post('contact-us', 'IndexController@contactUs');
Route::post('question', 'IndexController@question');
Route::post('activate-account', 'IndexController@activateAccount');


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

    Route::post('verify/callback', 'AccountController@trackVerifyResponse');
});


/**
 * USER
 */
Route::group(['prefix' => 'user'], function () {

    Route::get('states', 'UserController@getStates');
    Route::post('accept-terms', 'UserController@acceptTerms');


    Route::get('profile', 'UserController@profile');
    Route::post('profile', 'UserController@saveProfile');//->middleware('protect.action');

    Route::post('close-account', 'UserController@closeAccount');//->middleware('protect.action');

    Route::post('verify', 'UserController@trackVerifyRequest');

    Route::get('profile-settings', 'UserController@profileSettings');
//    Route::post('profile-settings/remove-document', 'UserController@removeDocument');
//    Route::post('profile-settings/upload-identity-documents', 'UserController@uploadIdentityDocuments');
//    Route::post('profile-settings/upload-address-documents', 'UserController@uploadAddressDocuments');
    Route::post('profile-settings/change-password', 'UserController@changePassword');//->middleware('protect.action');
    Route::post('profile-settings/update-wallet', 'UserController@updateWallet');//->middleware('protect.action');

    Route::get('invite-friend', 'UserController@invite');
    Route::post('invite-friend', 'UserController@saveInvitation');

    Route::get('wallet', 'UserController@wallet');
    Route::post('wallet/address', 'UserController@createWalletAddress');
    Route::post('wallet/rate-calculator', 'UserController@rateCalculator');
    Route::post('wallet/transfer-eth', 'UserController@transferEth');//->middleware('protect.action');
    Route::post('wallet/withdraw-eth', 'UserController@withdrawEth');//->middleware('protect.action');

    Route::get('debit-card', 'UserController@debitCardCountry');
    Route::post('debit-card', 'UserController@saveDebitCardCountry');
    Route::get('debit-card-design', 'UserController@debitCardDesign');
    Route::post('debit-card-design', 'UserController@saveDebitCardDesign');
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
    Route::post('profile', 'AdminController@saveProfile')->middleware('protect.action');
    Route::post('profile/remove', 'AdminController@removeProfile')->middleware('protect.action');

    Route::get('document', 'ManagerController@document');
    Route::post('document/approve', 'ManagerController@approveDocument');
    Route::post('document/decline', 'ManagerController@declineDocument');

    Route::get('wallet', 'AdminController@wallet');
    Route::post('wallet', 'ManagerController@updateWallet')->middleware('protect.action');
    Route::post('wallet/add-ico-znx', 'ManagerController@addIcoZnx')->middleware('protect.action');
    Route::post('wallet/add-foundation-znx', 'ManagerController@addFoundationZnx')->middleware('protect.action');
//    Route::post('wallet/grant-marketing-coins', 'AdminController@grantMarketingCoins');
//    Route::post('wallet/grant-company-coins', 'AdminController@grantCompanyCoins');

    Route::get('wallet/search-ico-transactions', 'AdminController@searchIcoTransactions');
    Route::get('wallet/search-marketing-transactions', 'AdminController@searchMarketingTransactions');
    Route::get('wallet/search-company-transactions', 'AdminController@searchCompanyTransactions');

    Route::get('newsletter', 'AdminController@newsletter');
    Route::get('newsletter/export', 'AdminController@exportNewsletter');
});

/**
 * SERVICE
 */
Route::group(['prefix' => 'service'], function () {

    Route::get('mail-events', 'ServiceController@mailEvents');
    Route::get('mail-events/search', 'ServiceController@searchMailEvents');
    Route::post('mail-events/process', 'ServiceController@processMailEvent');

});

//Route::get('/test-email', function () {
//    return new \App\Mail\AccountApproved();
//});
