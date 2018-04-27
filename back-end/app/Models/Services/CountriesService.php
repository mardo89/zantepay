<?php

namespace App\Models\Services;


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
        return Country::all();
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
     * @return array
     */
    public static function getCountryStates($countryID)
    {
        return State::where('country_id', $countryID)->get();
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

}
