<?php

namespace App\Models\Services;


use App\Exceptions\DocumentException;
use App\Models\DB\Document;
use App\Models\DB\User;
use App\Models\DB\Verification;
use http\Env\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
                'isDocumentsUploaded' => $verification->id_documents_status !== Verification::DOCUMENTS_NOT_UPLOADED,
                'statusName' => self::getVerificationStatus($verification->id_documents_status, $verification->id_decline_reason),
                'declineReason' => $verification->id_decline_reason
            ],
            'address' => [
                'isDocumentsUploaded' => $verification->address_documents_status !== Verification::DOCUMENTS_NOT_UPLOADED,
                'statusName' => self::getVerificationStatus($verification->address_documents_status, $verification->address_decline_reason),
                'declineReason' => $verification->address_decline_reason
            ]
        ];
    }

    /**
     * Get info about document
     *
     * @param string $documentDID
     *
     * @return array
     * @throws
     */
    public static function getDocumentInfo($documentDID)
    {
        $document = Document::where('did', $documentDID)->first();

        if (!$document) {
            throw new DocumentException('Document not found');
        }

        return [
            'documentFilePath' => storage_path('app/' . $document->file_path),
            'documentMimeType' => Storage::mimeType($document->file_path)

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
                    'id' => $document->did,
                    'name' => basename($document->file_path),
                    'src' => action('ManagerController@document', ['did' => $document->did]),
                    'type' => Storage::mimeType($document->file_path)
                ];
            } else {
                $userAddressDocuments[] = [
                    'id' => $document->did,
                    'name' => basename($document->file_path),
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
     * Remove user's Document
     *
     * @param User $user
     * @param string $documentDID
     *
     * @throws
     */
    public static function removeUserDocument($user, $documentDID)
    {
        // Remove document
        $document = Document::where('did', $documentDID)->where('user_id', $user->id)->first();

        if (!$document) {
            throw new DocumentException('Document not found');
        }

        self::deleteDocument($document);

        Document::destroy($document->id);

        // Update verification status
        $verification = self::getVerification($user);
        $userDocuments = self::getUserDocuments($user->id);

        if (count($userDocuments['idDocuments']) === 0) {
            $verification->id_documents_status = Verification::DOCUMENTS_NOT_UPLOADED;
            $verification->id_decline_reason = '';
        }

        if (count($userDocuments['addressDocuments']) === 0) {
            $verification->address_documents_status = Verification::DOCUMENTS_NOT_UPLOADED;
            $verification->address_decline_reason = '';
        }

        $verification->save();

        // Update user status
        if ($verification->id_documents_status == Verification::DOCUMENTS_APPROVED) {
            UsersService::changeUserStatus($user, User::USER_STATUS_IDENTITY_VERIFIED);
        } elseif ($verification->address_documents_status == Verification::DOCUMENTS_APPROVED) {
            UsersService::changeUserStatus($user, User::USER_STATUS_ADDRESS_VERIFIED);
        } else {
            UsersService::changeUserStatus($user, User::USER_STATUS_NOT_VERIFIED);
        }

    }

    /**
     * Approve user's Documents
     *
     * @param string $userUID
     * @param int $documentsType
     *
     * @return string
     * @throws
     */
    public static function approveUserDocuments($userUID, $documentsType)
    {
        $user = AccountsService::getUserByID($userUID);

        $verification = self::getVerification($user);

        if ($documentsType == Document::DOCUMENT_TYPE_IDENTITY) {

            $verification->id_documents_status = Verification::DOCUMENTS_APPROVED;
            $verification->id_decline_reason = '';

            $verificationStatus = self::getVerificationStatus($verification->id_documents_status, $verification->id_decline_reason);

            UsersService::changeUserStatus($user, User::USER_STATUS_IDENTITY_VERIFIED);

        } else {

            $verification->address_documents_status = Verification::DOCUMENTS_APPROVED;
            $verification->address_decline_reason = '';

            $verificationStatus = self::getVerificationStatus($verification->address_documents_status, $verification->address_decline_reason);

            UsersService::changeUserStatus($user, User::USER_STATUS_ADDRESS_VERIFIED);

        }

        $verification->save();

        if (self::verificationComplete($user)) {

            UsersService::changeUserStatus($user, User::USER_STATUS_VERIFIED);

            BonusesService::updateBonus($user);

            MailService::sendApproveDocumentsEmail($user->email);

        }

        return $verificationStatus;
    }

    /**
     * Decline user's Documents
     *
     * @param string $userUID
     * @param int $documentsType
     * @param string $declineReason
     *
     * @return string
     * @throws
     */
    public static function declineUserDocuments($userUID, $documentsType, $declineReason)
    {
        $user = AccountsService::getUserByID($userUID);

        $verification = self::getVerification($user);

        if ($documentsType == Document::DOCUMENT_TYPE_IDENTITY) {

            $verification->id_documents_status = Verification::DOCUMENTS_DECLINED;
            $verification->id_decline_reason = $declineReason;

            $verificationStatus = self::getVerificationStatus($verification->id_documents_status, $verification->id_decline_reason);
        } else {

            $verification->address_documents_status = Verification::DOCUMENTS_DECLINED;
            $verification->address_decline_reason = $declineReason;

            $verificationStatus = self::getVerificationStatus($verification->address_documents_status, $verification->address_decline_reason);
        }

        $verification->save();

        // Change user status
        if ($verification->id_documents_status == Verification::DOCUMENTS_APPROVED) {

            UsersService::changeUserStatus($user, User::USER_STATUS_IDENTITY_VERIFIED);

        } elseif ($verification->address_documents_status == Verification::DOCUMENTS_APPROVED) {

            UsersService::changeUserStatus($user, User::USER_STATUS_ADDRESS_VERIFIED);

        } else {

            UsersService::changeUserStatus($user, User::USER_STATUS_NOT_VERIFIED);

        }

        return $verificationStatus;
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
    public static function getDocumentTypeID()
    {

        return [
            'idDocuments' => Document::DOCUMENT_TYPE_IDENTITY,
            'addressDocuments' => Document::DOCUMENT_TYPE_ADDRESS,
        ];

    }

    /**
     * Upload identity files
     *
     * @param User $user
     * @param Request $request
     *
     * @throws
     */
    public static function uploadIdentityFiles($user, $request)
    {
        self::uploadUserFiles($user, Document::DOCUMENT_TYPE_IDENTITY, $request);
    }

    /**
     * Upload address files
     *
     * @param User $user
     * @param Request $request
     *
     * @throws
     */
    public static function uploadAddressFiles($user, $request)
    {
        self::uploadUserFiles($user, Document::DOCUMENT_TYPE_ADDRESS, $request);
    }

    /**
     * Upload user's files
     *
     * @param User $user
     * @param int $documentType
     * @param Request $request
     *
     * @throws
     */
    protected static function uploadUserFiles($user, $documentType, $request)
    {
        $files = new \stdClass();
        $files->data = [];
        $files->rules = [];

        $filesList = $documentType === Document::DOCUMENT_TYPE_IDENTITY ? $request->document_files : $request->address_files;

        foreach ($filesList as $index => $file) {
            $fileName = 'document_' . $user->uid . '_' . $index;

            $files->data[$fileName] = $file;
            $files->rules[$fileName] = 'file|max:4096|mimetypes:image/jpeg,image/png,application/pdf';
        }

        if (Validator::make($files->data, $files->rules)->fails()) {
            throw new \Exception('Incorrect files format', 422);
        }

        // delete old documents
        $oldDocuments = Document::where('user_id', $user->id)->where('document_type', $documentType)->get();

        // delete old files
        foreach ($oldDocuments as $document) {
            self::deleteDocument($document);
        }

        // Empty DB records
        Document::where('user_id', $user->id)->where('document_type', $documentType)->delete();


        // upload new files
        $storeFolder = $documentType === Document::DOCUMENT_TYPE_IDENTITY ? 'documents/id' : 'documents/address';

        foreach ($files->data as $fileName => $file) {
            $newFileName = $fileName . '.' . $file->getClientOriginalExtension();

            $pathToFile = $file->storeAs($storeFolder, $newFileName);

            Document::create(
                [
                    'user_id' => $user->id,
                    'document_type' => $documentType,
                    'did' => uniqid(),
                    'file_path' => $pathToFile
                ]
            );
        }

        // Verification
        $verification = self::getVerification($user);

        if ($documentType === Document::DOCUMENT_TYPE_IDENTITY) {
            $verification->id_documents_status = Verification::DOCUMENTS_UPLOADED;
            $verification->id_decline_reason = '';
        } else {
            $verification->address_documents_status = Verification::DOCUMENTS_UPLOADED;
            $verification->address_decline_reason = '';
        }

        $verification->save();

        // Change Status
        UsersService::changeUserStatus($user, User::USER_STATUS_VERIFICATION_PENDING);
    }

    /**
     * Get user's verification object
     *
     * @param User $user
     *
     * @return Verification
     */
    protected static function getVerification($user)
    {
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
    protected static function getVerificationStatus($verificationStatus, $declineReason)
    {
        $verificationStatusName = self::$verificationStatuses[$verificationStatus] ?? '';

        return $verificationStatus != Verification::DOCUMENTS_DECLINED
            ? $verificationStatusName
            : $verificationStatusName . ' - ' . $declineReason;
    }

}
