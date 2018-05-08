<?php

namespace App\Models\Services;


use App\Models\DB\AreaCode;
use App\Models\DB\Country;
use App\Models\DB\State;

class CountriesService
{
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
