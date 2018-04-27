<?php

namespace App\Models\Services;


use App\Models\DB\Document;
use App\Models\DB\Verification;
use Illuminate\Support\Facades\Storage;

class DocumentsService
{

    /**
     * @var array Verification Statuses
     */
    public static $verificationStatuses = [
        Verification::DOCUMENTS_NOT_UPLOADED => 'Documents not uploaded',
        Verification::DOCUMENTS_UPLOADED => 'Pending approval',
        Verification::DOCUMENTS_APPROVED => 'Documents approved',
        Verification::DOCUMENTS_DECLINED => 'Documents declined',
    ];

    /**
     * @var array Document Types
     */
    public static $documentTypes = [
        Document::DOCUMENT_TYPE_IDENTITY => 'Identity Documents',
        Document::DOCUMENT_TYPE_ADDRESS => 'Address Documents',
    ];

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
        $verification = self::getVerification($user);

        $idVerified = $verification->id_documents_status == Verification::DOCUMENTS_APPROVED;
        $addressVerified = $verification->address_documents_status == Verification::DOCUMENTS_APPROVED;

        return $idVerified && $addressVerified;
    }

    /**
     * Get verification status
     *
     * @param User $user
     *
     * @return array
     */
    public static function getVerificationInfo($user)
    {
        $verification = self::getVerification($user);

        return [
            'id' => [
                'isDocumentsUploaded' => $verification->id_documents_status == Verification::DOCUMENTS_UPLOADED,
                'statusName' => self::getVerificationStatus($verification->id_documents_status, $verification->id_decline_reason),
                'declineReason' => $verification->id_decline_reason
            ],
            'address' => [
                'isDocumentsUploaded' => $verification->address_documents_status == Verification::DOCUMENTS_UPLOADED,
                'statusName' => self::getVerificationStatus($verification->address_documents_status, $verification->address_decline_reason),
                'declineReason' => $verification->address_decline_reason
            ]
        ];
    }

    /**
     * Get user's Documents
     *
     * @param int $userID
     *
     * @return array
     */
    public static function getUserDocuments($userID)
    {
        $userIDDocuments = [];
        $userAddressDocuments = [];

        foreach (self::getDocuments($userID) as $document) {
            if ($document->document_type === Document::DOCUMENT_TYPE_IDENTITY) {
                $userIDDocuments[] = [
                    'src' => action('ManagerController@document', ['did' => $document->did]),
                    'type' => Storage::mimeType($document->file_path)
                ];
            } else {
                $userAddressDocuments[] = [
                    'src' => action('ManagerController@document', ['did' => $document->did]),
                    'type' => Storage::mimeType($document->file_path)
                ];
            }
        }

        return [
            'idDocuments' => $userIDDocuments,
            'addressDocuments' => $userAddressDocuments,
        ];
    }

    /**
     * Remove user's Documents
     *
     * @param int $userID
     */
    public static function removeUserDocuments($userID)
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
     * @return Document
     */
    protected static function getDocuments($userID)
    {
        return Document::where('user_id', $userID)->get();
    }

    /**
     * Remove user's Documents
     *
     * @param object $document
     */
    protected static function deleteDocument($document)
    {
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }
    }

    /**
     * Get list of document type id's
     *
     *
     * @return array
     */
    public static function getDocumentTypeID() {

        return [
            'idDocuments' => Document::DOCUMENT_TYPE_IDENTITY,
            'addressDocuments' => Document::DOCUMENT_TYPE_ADDRESS,
        ];

    }

    /**
     * Get user's verification object
     *
     * @param User $user
     *
     * @return Verification
     */
    protected static function getVerification($user) {
        return $user->verification;
    }

    /**
     * Get user's verification status
     *
     * @param int $verificationStatus
     * @param string $declineReason
     *
     * @return string
     */
    protected static function getVerificationStatus($verificationStatus, $declineReason) {
        $verificationStatusName = self::$verificationStatuses[$verificationStatus] ?? '';

        return $verificationStatus != Verification::DOCUMENTS_DECLINED
            ? $verificationStatusName
            : $verificationStatusName . ' - ' . $declineReason;
    }

}
