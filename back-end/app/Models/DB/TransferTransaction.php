<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class TransferTransaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'eth_amount', 'znx_amount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
