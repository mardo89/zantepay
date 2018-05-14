<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    /**
     * Invitation status
     */
    const INVITATION_STATUS_PENDING = 0;
    const INVITATION_STATUS_VERIFYING = 1;
    const INVITATION_STATUS_COMPLETE = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
