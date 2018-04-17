<?php

namespace App\Models\Services;


use App\Models\DB\Profile;

class ProfilesService
{

    /**
     * Create Profile
     *
     * @param int $userID
     */
    public static function createProfile($userID)
    {
        Profile::create(
            [
                'user_id' => $userID
            ]
        );
    }

    /**
     * Remove user's Profile
     *
     * @param int $userID
     */
    public static function removeProfile($userID)
    {
        Profile::where('user_id', $userID)->delete();
    }

}
