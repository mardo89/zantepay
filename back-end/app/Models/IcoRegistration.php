<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IcoRegistration extends Model
{
    /**
     * Currency types
     */
    const CURRENCY_TYPE_BTC = 0;
    const CURRENCY_TYPE_ETH = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'currency', 'amount'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * Get currency
     *
     * @param int $currency
     *
     * @return string
     */
    public static function getCurrency($currency)
    {
        switch ($currency) {
            case self::CURRENCY_TYPE_BTC:
                return 'BTC';

            case self::CURRENCY_TYPE_ETH:
                return 'ETH';

            default:
                return '';
        }
    }

}
