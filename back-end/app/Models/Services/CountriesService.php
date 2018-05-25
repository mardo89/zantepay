<?php

namespace App\Models\Services;


use App\Models\DB\AreaCode;
use App\Models\DB\Country;
use App\Models\DB\State;

class CountriesService
{
    protected static $europeCountries = [
        14, 21, 33, 54, 56, 57, 58, 68, 74, 75, 82, 85, 99, 105, 107, 120, 126, 127, 135, 155, 175, 176, 180, 197, 198, 205, 211, 230
    ];

    /**
     * Get Countries list
     *
     * @return array
     */
    public static function getCountries()
    {
        $countriesList = [];

        foreach (Country::all() as $country) {
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

    /**
     * Find Country
     *
     * @param int $countryID
     *
     * @return string
     */
    public static function findCountry($countryID)
    {
        $country = Country::find($countryID);

        return !is_null($country) ? $country->name : '';
    }

    /**
     * Find Country Code
     *
     * @param string $countryName
     *
     * @return string
     */
    public static function getCountry($countryName)
    {
        $country = Country::where('name', $countryName)->first();

        return optional($country)->id;
    }

    /**
     * Check if country is in Europe
     *
     * @param int $countryID
     *
     * @return boolean
     */
    public static function isEurope($countryID)
    {
        return in_array($countryID, self::$europeCountries);
    }

    /**
     * Get States list
     *
     * @return array
     */
    public static function getStates()
    {
        return State::all();
    }

    /**
     * Get States list for Country
     *
     * @param int $countryID
     *
     * @return array
     */
    public static function getCountryStates($countryID)
    {
        $statesList = [];

        $states = State::where('country_id', $countryID)->orderBy('name', 'asc')->get();

        if (!is_null($states)) {
            foreach ($states as $state) {
                $statesList[] = [
                    'id' => (int)$state->id,
                    'name' => $state->name
                ];
            }
        }

        $statesList[] = [
            'id' => 0,
            'name' => 'Other state'
        ];

        return $statesList;
    }

    /**
     * Find State
     *
     * @param int $stateID
     *
     * @return string
     */
    public static function findState($stateID)
    {
        $state = State::find($stateID);

        return !is_null($state) ? $state->name : '';
    }

    /**
     * Get Codes list for Country
     *
     * @param int $countryID
     *
     * @return array
     */
    public static function getCountryCodes($countryID)
    {
        $codesList = [];

        $areaCodes = AreaCode::where('country_id', $countryID)->orderBy('area_name', 'ASC')->get();

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
