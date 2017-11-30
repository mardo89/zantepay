<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebitCard extends Model
{
    /**
     * White Design
     */
    const DESIGN_WHITE = 0;

    /**
     * Red esign
     */
    const DESIGN_RED = 1;

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
    protected $hidden = [

    ];

}
