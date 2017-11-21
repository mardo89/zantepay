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
    Route::get('activate', 'AuthController@activate');
});

/**
 * USER
 */
Route::group(['prefix' => 'user'], function () {
    Route::get('profile', 'UserController@profile');
    Route::post('profile', 'UserController@saveProfile');
    Route::get('states', 'UserController@getStates');
});

//Route::get('/test-email', function () {
//    return new App\Mail\ActivateAccount('http://test-link?user=92837927492847');
//});
