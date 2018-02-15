<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class ContributionAction extends Model
{
    /**
     * Action Types
     */
    const ACTION_TYPE_UPDATE = 0;
    const ACTION_TYPE_CORRECT = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'action_type', 'continuation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
