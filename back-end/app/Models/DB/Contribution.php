<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'operation_id', 'proxy', 'amount', 'time_stamp'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Get user wallet
     */
    public function wallet() {
        return $this->belongsTo('App\Models\DB\Wallet', 'proxy', 'eth_wallet');
    }

}
