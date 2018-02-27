<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class ContributionAction extends Model
{
    /**
     * Action statuses
     */
    const ACTION_STATUS_PENDING = 0;
    const ACTION_STATUS_CONFIRMED = 1;
    const ACTION_STATUS_DECLINED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'action_date', 'action_status', 'continuation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
