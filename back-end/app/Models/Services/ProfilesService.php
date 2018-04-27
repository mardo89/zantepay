<?php

namespace App\Models\Services;


use App\Models\DB\Country;
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

    /**
     * Get info about user's profile
     *
     * @param string $userUID
     *
     * @return array
     * @throws
     */
    public static function getInfo($userUID)
    {
        $user = AccountsService::getUserByID($userUID);

        $profile = $user->profile;
        $profile->passportExpDate = self::convertDate($profile->passport_expiration_date);
        $profile->birthDate = self::convertDate($profile->birth_date);
        $profile->countryName = CountriesService::findCountry($profile->country_id);
        $profile->stateName = CountriesService::findState($profile->state_id);
        $profile->birthCountryName = CountriesService::findCountry($profile->birth_country_id);

        $verification = DocumentsService::getVerificationInfo($user);
        $documents = DocumentsService::getUserDocuments($user->id);
        $documentsTypes = DocumentsService::getDocumentTypeID();

        $referrer = AccountsService::getReferrer($user->referrer);
        $referrerEmail = is_null($referrer) ? (is_null($user->referrer) ? '' : 'User deleted') : $referrer->email;

        $debitCardDesign = DebitCardsService::getDebitCardDesign($user->id);
        $debitCard = [
            'isWhite' => DebitCardsService::isDebitCardWhile($debitCardDesign),
            'isRed' => DebitCardsService::isDebitCardRed($debitCardDesign),
        ];

        $wallet = $user->wallet;

        $rolesList = UsersService::getUserRoles();

        $allowEdit = !AccountsService::checkIdentity($user->id);

        return [
            'user' => $user,
            'profile' => $profile,
            'verification' => $verification,
            'documents' => $documents,
            'documentTypes' => $documentsTypes,
            'referrer' => $referrerEmail,
            'debitCard' => $debitCard,
            'wallet' => $wallet,
            'userRoles' => $rolesList,
            'allowEdit' => $allowEdit

        ];
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
}
