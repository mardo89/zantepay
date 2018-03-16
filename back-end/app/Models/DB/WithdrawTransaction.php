<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class WithdrawTransaction extends Model
{
    /**
     * Action statuses
     */
    const STATUS_PENDING = 'PENDING';
    const STATUS_COMPLETE = 'COMPLETE';
    const STATUS_FAILED = 'FAILED';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount', 'wallet_address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
