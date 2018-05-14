<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class AreaCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'country_code', 'area_code', 'area_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * @var bool Disable timestamps
     */
    public $timestamps = false;

}
