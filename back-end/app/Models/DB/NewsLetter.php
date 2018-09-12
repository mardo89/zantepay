<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class NewsLetter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

	/**
	 * Get user info
	 */
	public function user() {
		return $this->hasOne('App\Models\DB\User', 'email', 'email')->withDefault(
			[
				'first_name' => '',
				'last_name' => ''
			]
		);
	}

}
