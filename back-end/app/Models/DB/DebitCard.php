<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class DebitCard extends Model
{
    /**
     * Debit Card Designs
     */
    const DESIGN_NOT_SELECTED = 0;
    const DESIGN_WHITE = 1;
    const DESIGN_RED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'design'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

}
