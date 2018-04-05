<?php

namespace App\Models\Services;


use App\Models\DB\Verification;

class DocumentsService
{

    /**
     * Check if documents verification is complete
     *
     * @param User $user
     *
     * @return boolean
     */
    public static function verificationComplete($user)
    {
        $verification = $user->verification;

        if (!$verification) {
            return false;
        }

        $idVerified = $verification->id_documents_status == Verification::DOCUMENTS_APPROVED;
        $addressVerified = $verification->address_documents_status == Verification::DOCUMENTS_APPROVED;

        return $idVerified && $addressVerified;
    }

}
