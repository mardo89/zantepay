<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class EthAddressAction extends Model
{
    /**
     * Action statuses
     */
    const STATUS_IN_PROGRESS = 'OPERATION IN-PROGRESS';
    const STATUS_COMPLETE = 'OPERATION COMPLETE';
    const STATUS_FAILED = 'OPERATION FAILED';

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
    protected $hidden = [];

}
