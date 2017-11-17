<?php

/**
 * Main Page
 */
Route::get('/', function () {
    return view('index');
});


/**
 * Mailing
 */
Route::group(['prefix' => 'mail'], function () {
    Route::post('activate-account', 'MailController@activateAccount');
    Route::post('contact-us', 'MailController@contactUs');
});
/**
 * AUTHORIZATION
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('fb-login', 'AuthController@fbLogin');
    Route::post('logout', 'AuthController@logout');
    Route::post('mail', 'AuthController@sendActivationEmail');
    Route::get('activate', 'AuthController@activate');
});