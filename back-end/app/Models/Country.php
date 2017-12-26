<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    /**
     * Get list of countries
     *
     * @return array
     */
    public static function getCountriesList() {
        $countriesList = [];

        foreach (self::all() as $country) {
            $countriesList[] = [
                'id' => (int)$country->id,
                'name' => $country->name
            ];
        }

        $countriesList[] = [
            'id' => 0,
            'name' => 'Other country'
        ];

        return $countriesList;
    }

}
