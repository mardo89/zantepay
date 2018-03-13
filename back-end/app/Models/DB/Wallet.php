<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /**
     * Bonuses
     */
    const DEBIT_CARD_BONUS = 500;
    const REFERRAL_BONUS = 500;
    const COMMISSION_BONUS = 0.2;


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

    /**
     * Get user
     */
    public function user() {
        return $this->belongsTo('App\Models\DB\User', 'user_id', 'id');
    }

    /**
     * Get contributions
     */
    public function contributions() {
        return $this->hasMany('App\Models\DB\Contribution', 'proxy', 'eth_wallet');
    }

}
