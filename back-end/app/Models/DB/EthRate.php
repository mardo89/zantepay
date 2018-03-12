<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class EthRate extends Model
{
    /**
     * Currency
     */
    const CURRENCY_TYPE_EURO = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_type', 'rate'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
