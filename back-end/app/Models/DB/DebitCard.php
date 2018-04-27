<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class DebitCard extends Model
{
    /**
     * Not selected
     */
    const DESIGN_NOT_SELECTED = 0;

    /**
     * White Design
     */
    const DESIGN_WHITE = 1;

    /**
     * Red Design
     */
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
