<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * User roles
     */
    const USER_ROLE_ADMIN = 0;
    const USER_ROLE_MANAGER = 1;
    const USER_ROLE_USER = 2;

    /**
     * User statuses
     */
    const USER_STATUS_INACTIVE = 0;
    const USER_STATUS_ACTIVE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'uid', 'status', 'referrer',
        'first_name', 'last_name', 'phone_number','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'uid', 'password', 'remember_token', 'role'
    ];
}
