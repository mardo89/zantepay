<?php

namespace App\Models\DB;

use Illuminate\Database\Eloquent\Model;

class ZpayRate extends Model
{
	/**
	 * Currency
	 */
	const CURRENCY_TYPE_ETH = 0;
	const CURRENCY_TYPE_USD = 1;
	const CURRENCY_TYPE_EURO = 2;

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
