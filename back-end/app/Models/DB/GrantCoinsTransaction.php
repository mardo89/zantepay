<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class GrantCoinsTransaction extends Model
{
    /**
     * Transaction types
     */
    const GRANT_ICO_TOKENS = 'ico';
    const GRANT_MARKETING_TOKENS = 'marketing';
    const GRANT_COMPANY_TOKENS = 'company';

    /**
     * Transaction statuses
     */
    const STATUS_IN_PROGRESS = 0;
    const STATUS_COMPLETE = 1;
    const STATUS_FAILED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address', 'amount', 'type', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
