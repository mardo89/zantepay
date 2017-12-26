<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    /**
     * Get list of states for country
     *
     * @param int $countryID
     *
     * @return array
     */
    public static function getStatesList($countryID) {
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

}
