<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * Identification status
     */
    const IDENTIFICATION_NOT_APPROVED = 0;

    const IDENTIFICATION_APPROVED = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'country_id', 'state_id', 'city', 'address', 'post_code',
        'passport_id', 'passport_expiration_date',
        'birth_date', 'birth_country_id', 'eth_wallet'
    ];
}
