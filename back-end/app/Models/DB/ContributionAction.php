<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class ContributionAction extends Model
{
    /**
     * Action types
     */
    const ACTION_TYPE_UPDATE = 0;
    const ACTION_TYPE_RESTORE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contributions_found', 'action_type', 'continuation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
