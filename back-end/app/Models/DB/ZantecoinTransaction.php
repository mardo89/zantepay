<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class ZantecoinTransaction extends Model
{
    /**
     * Transaction types
     */
    const TRANSACTION_ETH_TO_ZNX = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount', 'ico_part', 'contribution_id', 'transaction_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
