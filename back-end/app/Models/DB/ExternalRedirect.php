<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class ExternalRedirect extends Model
{
    /**
     * Action types
     */
    const ACTION_TYPE_REGISTRATION = "Sign Up";
    const ACTION_TYPE_REGISTRATION_ICO = "Registration for Pre ICO";
    const ACTION_TYPE_REGISTRATION_INVESTOR = "Become An Investor";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'external_link', 'email', 'action'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public static function addLink($externalLink, $email, $action) {

        if ($externalLink) {

            self::create(
                [
                    'external_link' => $externalLink,
                    'email' => $email,
                    'action' => $action
                ]
            );

        }

    }
}
