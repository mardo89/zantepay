<?php

namespace App\Models\Services;


use App\Models\DB\Document;
use App\Models\DB\Verification;
use Illuminate\Support\Facades\Storage;

class DocumentsService
{

    /**
     * Create Verification
     *
     * @param int $userID
     */
    public static function createVerification($userID)
    {
        Verification::create(
            [
                'user_id' => $userID
            ]
        );
    }

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

    /**
     * Remove user's Documents
     *
     * @param int $userID
     */
    public static function removeDocuments($userID)
    {
        Verification::where('user_id', $userID)->delete();

        $documents = self::getDocuments($userID);

        foreach ($documents as $document) {
            self::deleteDocument($document);
        }

        Document::where('user_id', $userID)->delete();
    }

    /**
     * Get user's Documents
     *
     * @param int $userID
     *
     * @return mixed
     */
    public static function getDocuments($userID)
    {
        return Document::where('user_id', $userID)->get();
    }

    /**
     * Remove user's Documents
     *
     * @param object $document
     */
    public static function deleteDocument($document)
    {
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }
    }

}
