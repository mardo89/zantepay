<?php

namespace App\Models\Services;


use App\Models\DB\Country;
use App\Models\DB\Profile;
use App\Models\DB\User;
use App\Models\Wallet\Currency;

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

    /**
     * Get info about user's profile
     *
     * @param User $user
     *
     * @return Profile
     * @throws
     */
    public static function getProfileInfo($user)
    {
        $profile = self::getProfile($user);

        $profile->passportExpDate = self::convertDate($profile->passport_expiration_date);
        $profile->birthDate = self::convertDate($profile->birth_date);
        $profile->countryName = CountriesService::findCountry($profile->country_id);
        $profile->stateName = CountriesService::findState($profile->state_id);
        $profile->birthCountryName = CountriesService::findCountry($profile->birth_country_id);

        return $profile;
    }

    /**
     * Get info about user's profile settings
     *
     * @param User $user
     *
     * @return array
     * @throws
     */
    public static function getProfileSettingsInfo($user)
    {
        $verification = DocumentsService::getVerificationInfo($user);

        $documents = DocumentsService::getUserDocuments($user->id);
        $documentsTypes = DocumentsService::getDocumentTypeID();

        return [
            'verification' => $verification,
            'documents' => $documents,
            'documentTypes' => $documentsTypes,
        ];
    }

    /**
     * Update user profile
     *
     * @param User $user
     * @param array $newProfile
     *
     * @throws
     */
    public static function updateProfile($user, $newProfile)
    {
        $profile = self::getProfile($user);

        if (empty($profile->country_id)) {
            $profile->country_id = $newProfile['country'];
        }

        $profile->state_id = $newProfile['state'];
        $profile->city = $newProfile['city'];
        $profile->address = $newProfile['address'];
        $profile->post_code = $newProfile['postcode'];
        $profile->passport_id = $newProfile['passport'];
        $profile->passport_expiration_date = date('Y-m-d H:i:s', strtotime($newProfile['expiration_date']));
        $profile->birth_date = date('Y-m-d H:i:s', strtotime($newProfile['birth_date']));
        $profile->birth_country_id = $newProfile['birth_country'];

        $profile->save();
    }

    /**
     * Update user country
     *
     * @param User $user
     * @param integer $countryID
     *
     * @throws
     */
    public static function updateProfileCountry($user, $countryID)
    {
        $profile = self::getProfile($user);

        $profile->country_id = $countryID;

        $profile->save();
    }


    /**
     * Update user country
     *
     * @param User $user
     *
     * @return boolean
     * @throws
     */
    public static function isEuropeCitizenship($user)
    {
        $profile = self::getProfile($user);

        return CountriesService::isEurope($profile->country_id);
    }


    /**
     * Add ZNX from ICO pull
     *
     * @param User $user
     * @param string $address
     * @param int $addressType
     *
     * @throws
     */
    public static function updateWalletAddress($user, $address, $addressType)
    {
        $profile = self::getProfile($user);

        switch ($addressType) {
            case Currency::CURRENCY_TYPE_BTC:
                $profile->btc_wallet = $address;
                break;

            case Currency::CURRENCY_TYPE_ETH:
                $profile->eth_wallet = $address;
                break;
        }

        $profile->save();
    }

    /**
     * Convert date to the correct format for View
     *
     * @param string $date
     *
     * @return string
     */
    protected static function convertDate($date)
    {
        return is_null($date) ? '' : date('m/d/Y', strtotime($date));
    }

    /**
     * Get user's profile
     *
     * @param User $user
     *
     * @return Profile
     * @throws \App\Exceptions\UserNotFoundException
     */
    protected static function getProfile($user)
    {
        return $user->profile;
    }
}
