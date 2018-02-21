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

    /**
     * Get list of states for country
     *
     * @param int $countryID
     *
     * @return array
     */
    public static function getCodesList($countryID) {
        $codesList = [];

        $areaCodes = self::where('country_id', $countryID)->orderBy('area_name', 'ASC')->get();

        if (!is_null($areaCodes)) {
            foreach ($areaCodes as $areaCode) {
                $codesList[] = [
                    'id' => (int)$areaCode->id,
                    'code' => sprintf('(%s) %s %s', $areaCode->country_code, $areaCode->area_code, $areaCode->area_name)
                ];
            }
        }

        $codesList[] = [
            'id' => 0,
            'code' => 'Other code'
        ];

        return $codesList;
    }

}
