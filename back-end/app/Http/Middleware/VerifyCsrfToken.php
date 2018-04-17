<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'ico-registration',
        'seed-investor',
        'contact-us',
        'question',
        'activate-account',
        'account/register',
        'account/login',
        'account/logout',
        'account/reset-password',
    ];
}