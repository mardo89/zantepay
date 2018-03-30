<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class ZantecoinTransaction extends Model
{
    /**
     * Transaction types
     */
    const TRANSACTION_ETH_TO_ZNX = 0;
    const TRANSACTION_COMMISSION_TO_ZNX = 1;
    const TRANSACTION_ADD_ICO_ZNX = 2;
    const TRANSACTION_ADD_FOUNDATION_ZNX = 3;

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

    /**
     * Return ICO transaction types
     *
     * @return array
     */
    public static function getIcoTransactionTypes() {
        return [
            ZantecoinTransaction::TRANSACTION_ETH_TO_ZNX,
            ZantecoinTransaction::TRANSACTION_ADD_ICO_ZNX,
            ZantecoinTransaction::TRANSACTION_COMMISSION_TO_ZNX
        ];
    }

    /**
     * Return Foundation transaction types
     *
     * @return array
     */
    public static function getFoundationTransactionTypes() {
        return [
            ZantecoinTransaction::TRANSACTION_ADD_FOUNDATION_ZNX
        ];
    }

}
