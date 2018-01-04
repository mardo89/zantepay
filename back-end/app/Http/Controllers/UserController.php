<?php

namespace App\Http\Controllers;

use App\Mail\InviteFriend;
use App\Models\Country;
use App\Models\Currency;
use App\Models\DebitCard;
use App\Models\Document;
use App\Models\Invite;
use App\Models\Profile;
use App\Models\State;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get states list for country
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function getStates(Request $request)
    {
        $this->validate($request, [
            'country' => 'numeric'
        ]);

        $country = $request->input('country');

        $states = State::getStatesList($country);

        return response()->json($states);
    }

    /**
     * User profile
     *
     * @return View
     */
    public function profile()
    {
        // User
        $user = Auth::user();

        // Profile
        $profile = $user->profile;

        $profile->passportExpDate = is_null($profile->passport_expiration_date)
            ? date('m/d/Y')
            : date('m/d/Y', strtotime($profile->passport_expiration_date));

        $profile->birthDate = is_null($profile->birth_date)
            ? date('m/d/Y')
            : date('m/d/Y', strtotime($profile->birth_date));

        // Countries List
        $countries = Country::getCountriesList();

        // States List
        $states = State::getStatesList($profile->country_id);


        return view(
            'user.profile',
            [
                'user' => $user,
                'profile' => $profile,
                'countries' => $countries,
                'states' => $states
            ]
        );
    }

    /**
     * Save user profile
     *
     * @param Request $request
     *
     * @return View
     */
    public function saveProfile(Request $request)
    {
        $user = Auth::user();

        $this->validate(
            $request,
            [
                'first_name' => 'string|max:100|nullable',
                'last_name' => 'string|max:100|nullable',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id . ',id',
                'phone_number' => 'string|max:20|nullable',
                'country' => 'numeric',
                'state' => 'numeric',
                'city' => 'string|max:100|nullable',
                'address' => 'string|nullable',
                'postcode' => 'string|max:10|nullable',
                'passport' => 'string|max:50|nullable',
                'expiration_date' => 'date',
                'birth_date' => 'date',
                'birth_country' => 'string|max:50|nullable',
            ]
        );

        $profile = Profile::where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json(
                [
                    'message' => 'Error updating profile',
                    'errors' => ['Profile not found']
                ],
                422
            );
        }

        DB::beginTransaction();

        try {
            // Update user main info
            $user->email = $request->email;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone_number = $request->phone_number;
            $user->save();

            // Update profile
            $profile->country_id = $request->country;
            $profile->state_id = $request->state;
            $profile->city = $request->city;
            $profile->address = $request->address;
            $profile->postcode = $request->postcode;
            $profile->passport_id = $request->passport;
            $profile->passport_expiration_date = date('Y-m-d H:i:s', strtotime($request->expiration_date));
            $profile->birth_date = date('Y-m-d H:i:s', strtotime($request->birth_date));
            $profile->birth_country = $request->birth_country;
            $profile->save();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error updating profile',
                    'errors' => ['An error occurred']
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            []
        );
    }

    /**
     * User profile settings
     *
     * @return View
     */
    public function profileSettings()
    {
        // User
        $user = Auth::user();
        $user->accountVerified = $user->status == User::USER_STATUS_VERIFIED;

        // Verification
        $verification = $user->verification;
        $verification->idStatus = Verification::getStatus($verification->id_documents_status);
        $verification->addressStatus = Verification::getStatus($verification->address_documents_status);

        // Documents
        $userIDDocuments = [];
        $userAddressDocuments = [];

        $documents = Document::where('user_id', $user->id)->get();

        foreach ($documents as $document) {
            if ($document->document_type === Document::DOCUMENT_TYPE_IDENTITY) {
                $userIDDocuments[] = [
                    'did' => $document->did,
                    'name' => basename($document->file_path)
                ];
            } else {
                $userAddressDocuments[] = [
                    'did' => $document->did,
                    'name' => basename($document->file_path)
                ];
            }
        }

        // Wallet
        $wallet = $user->wallet;

        return view(
            'user.profile-settings',
            [
                'user' => $user,
                'verification' => $verification,
                'idDocuments' => $userIDDocuments,
                'addressDocuments' => $userAddressDocuments,
                'wallet' => $wallet,
            ]
        );
    }

    /**
     * Remove document
     *
     * @param Request $request
     *
     * @return json
     */
    public function removeDocument(Request $request)
    {
        $user = Auth::user();

        $document = Document::where('did', $request->did)->where('user_id', $user->id)->first();

        if (!$document) {
            return response()->json(
                [
                    'message' => 'File not found',
                    'errors' => ['Error deleting file']
                ],
                422
            );
        }

        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        Document::destroy($document->id);

        // Verification
        $verification = $user->verification;

        $idDocuments = Document::where('user_id', $user->id)->where('document_type', Document::DOCUMENT_TYPE_IDENTITY);

        if ($idDocuments->count() === 0) {
            $verification->id_documents_status = Verification::DOCUMENTS_NOT_UPLOADED;
            $verification->id_decline_reason = '';
        }

        $addressDocuments = Document::where('user_id', $user->id)->where('document_type', Document::DOCUMENT_TYPE_ADDRESS);

        if ($addressDocuments->count() === 0) {
            $verification->address_documents_status = Verification::DOCUMENTS_NOT_UPLOADED;
            $verification->address_decline_reason = '';
        }

        $verification->save();

        return response()->json(
            []
        );
    }

    /**
     * Update wallet link
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function updateWallet(Request $request)
    {
        $user = Auth::user();

        $this->validate(
            $request,
            [
                'currency' => 'required|numeric',
                'address' => 'string|nullable',
            ]
        );

        DB::beginTransaction();

        try {
            $wallet = $user->wallet;

            switch ($request->currency) {
                case Currency::CURRENCY_TYPE_BTC:
                    $wallet->btc_wallet = $request->address;
                    break;

                case Currency::CURRENCY_TYPE_ETH:
                    $wallet->eth_wallet = $request->address;
                    break;

            }

            $wallet->save();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error updating wallet',
                    'errors' => ['An error occurred']
                ],
                422
            );
        }

        DB::commit();

        return response()->json(
            []
        );
    }


    /**
     * Change password
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $this->validate(
            $request,
            [
                'password' => 'required|string|min:6|confirmed',
            ]
        );

        if (User::checkPassword($request->password_current, $user->password) === false) {
            return response()->json(
                [
                    'message' => 'Error changing password',
                    'errors' => [
                        'current-password' => 'Wrong password'
                    ]
                ],
                422
            );
        }

        $user->password = User::hashPassword($request->password);

        $user->save();

        return response()->json(
            []
        );
    }


    /**
     * Invite friends
     *
     * @return View
     */
    public function invite()
    {
        $user = Auth::user();

        $invites = Invite::where('user_id', $user->id)->get();

        $userReferrals = [];

        foreach ($invites as $invite) {
            $userReferrals[$invite->email] = [
                'name' => $invite->email,
                'avatar' => '/images/avatar.png',
                'status' => Invite::getStatus(Invite::INVITATION_STATUS_PENDING)

            ];
        }

        $referrals = User::where('referrer', $user->id)->get();


        foreach ($referrals as $referral) {
            $userName = ($referral->first_name != '' && $referral->last_name != '')
                ? $referral->first_name . ' ' . $referral->last_name
                : $referral->email;

            $userReferrals[$referral->email] = [
                'name' => $userName,
                'avatar' => !is_null($referral->avatar) ? $referral->avatar : '/images/avatar.png',
                'status' => Invite::getStatus(Invite::INVITATION_STATUS_VERIFYING)
            ];
        }

        return view(
            'user.invite-friend',
            [
                'referralLink' => action('IndexController@confirmInvitation', ['ref' => $user->uid]),
                'referrals' => $userReferrals
            ]
        );
    }

    /**
     * Save invitation
     *
     * @param Request $request
     *
     * @return View
     */
    public function saveInvitation(Request $request)
    {
        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255'
            ]
        );

        $user = Auth::user();
        $email = $request->email;

        $invite = Invite::where('user_id', $user->id)->where('email', $email)->first();

        try {

            if (!$invite) {
                $invite = Invite::create(
                    [
                        'user_id' => $user->id,
                        'email' => $email
                    ]
                );
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Invitation failed',
                    'errors' => ['Error sending invitation']
                ],
                422
            );

        }

        Mail::to($email)->send(new InviteFriend($user->email, $user->uid));

        return response()->json(
            [
                'email' => $invite['email'],
                'status' => Invite::getStatus($invite['status'])
            ]
        );
    }

    /**
     * Debit card design
     *
     * @return View
     */
    public function debitCard()
    {
        $user = Auth::user();

        $card = DebitCard::where('user_id', $user->id)->first();

        $userDebitCard = [
            'design' => is_null($card) ? DebitCard::DESIGN_WHITE : $card->design
        ];

        return view(
            'user.debit-card-design',
            [
                'debitCard' => $userDebitCard
            ]
        );
    }

    /**
     * Save debit card design
     *
     * @param Request $request
     *
     * @return json
     */
    public function saveDebitCard(Request $request)
    {
        $user = Auth::user();

        $this->validate(
            $request,
            [
                'design' => 'required|integer'
            ]
        );

        $userDebitCard = [
            'user_id' => $user->id,
            'design' => $request->design
        ];

        $card = DebitCard::where('user_id', $user->id)->first();

        try {

            if (!$card) {
                DebitCard::create($userDebitCard);
            } else {
                DebitCard::where('user_id', $user->id)->update($userDebitCard);
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error ordering Debit Card',
                    'errors' => ['An error occurred']
                ],
                422
            );

        }

        return response()->json(
            [
                'nextStep' => action('UserController@debitCardIdentityDocuments')
            ]
        );
    }

    /**
     * Debit card documents
     *
     * @return View
     */
    public function debitCardIdentityDocuments()
    {
        return view(
            'user.debit-card-documents'
        );
    }

    /**
     * Save debit card id documents
     *
     * @param Request $request
     *
     * @return json
     */
    public function uploadDCIdentityDocuments(Request $request)
    {
        $this->validate(
            $request,
            [
                'verify_later' => 'required|boolean',
                'document_files' => 'required_if:verify_later,0'
            ],
            [
                'document_files.required_if' => 'Please select files to download',
            ]
        );

        if ($request->verify_later == 1) {
            return response()->json(
                [
                    'nextStep' => action('UserController@debitCardAddress')
                ]
            );
        }

        DB::beginTransaction();

        try {

            $this->uploadIdentityFiles($request);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error uploading files',
                    'errors' => ['Error uploading file']
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            [
                'nextStep' => action('UserController@debitCardAddressDocuments')
            ]
        );
    }

    /**
     * Debit card address
     *
     * @return View
     */
    public function debitCardAddressDocuments()
    {
        return view(
            'user.debit-card-address'
        );
    }

    /**
     * Save debit card address documents
     *
     * @param Request $request
     *
     * @return json
     */
    public function uploadDCAddressDocuments(Request $request)
    {
        $this->validate(
            $request,
            [
                'verify_later' => 'required|boolean',
                'address_files' => 'required_if:verify_later,0'
            ],
            [
                'address_files.required_if' => 'Please select files to download',
            ]

        );

        if ($request->verify_later == 1) {
            return response()->json(
                [
                    'nextStep' => action('UserController@debitCardSuccess')
                ]
            );
        }


        DB::beginTransaction();

        try {

            $this->uploadAddressFiles($request);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error uploading files',
                    'errors' => ['Error uploading file']
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            [
                'nextStep' => action('UserController@debitCardSuccess')
            ]
        );
    }

    /**
     * Invite friends
     *
     * @return View
     */
    public function debitCardSuccess()
    {
        $user = Auth::user();

        return view(
            'user.debit-card-success',
            [
                'referralLink' => action('IndexController@confirmInvitation', ['ref' => $user->uid]),
            ]
        );
    }


    /**
     * Upload identity documents
     *
     * @param Request $request
     *
     * @return json
     */
    public function uploadIdentityDocuments(Request $request)
    {
        DB::beginTransaction();

        try {

            $this->uploadIdentityFiles($request);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error uploading files',
                    'errors' => ['Error uploading file']
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            []
        );
    }

    /**
     * Upload address documents
     *
     * @param Request $request
     *
     * @return json
     */
    public function uploadAddressDocuments(Request $request)
    {
        DB::beginTransaction();

        try {

            $this->uploadAddressFiles($request);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error uploading files',
                    'errors' => ['Error uploading file']
                ],
                422
            );

        }

        DB::commit();

        return response()->json(
            []
        );
    }


    /**
     * Upload identity files
     *
     * @param Request $request
     *
     * @throws \Exception
     */
    protected function uploadIdentityFiles($request)
    {
        $user = Auth::user();

        $files = new \stdClass();
        $files->data = [];
        $files->rules = [];

        foreach ($request->document_files as $index => $file) {
            $fileName = 'document_' . $user->uid . '_' . $index;

            $files->data[$fileName] = $file;
            $files->rules[$fileName] = 'file|max:4096|mimetypes:image/jpeg,image/png,application/pdf';
        }

        if (Validator::make($files->data, $files->rules)->fails()) {
            throw new \Exception('Error uploading files');
        }

        $oldDocuments = Document::where('user_id', $user->id)->where('document_type', Document::DOCUMENT_TYPE_IDENTITY)->get();

        // delete old files
        foreach ($oldDocuments as $document) {
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
        }

        // Empty DB records
        Document::where('user_id', $user->id)->where('document_type', Document::DOCUMENT_TYPE_IDENTITY)->delete();

        // insert new files
        foreach ($files->data as $fileName => $file) {
            $newFileName = $fileName . '.' . $file->getClientOriginalExtension();

            $pathToFile = $file->storeAs('documents/id', $newFileName);

            Document::create(
                [
                    'user_id' => $user->id,
                    'document_type' => Document::DOCUMENT_TYPE_IDENTITY,
                    'did' => uniqid(),
                    'file_path' => $pathToFile
                ]
            );
        }

        // Verification
        $verification = $user->verification;
        $verification->id_documents_status = Verification::DOCUMENTS_UPLOADED;
        $verification->id_decline_reason = '';
        $verification->save();

    }


    /**
     * Upload address files
     *
     * @param Request $request
     *
     * @throws \Exception
     */
    protected function uploadAddressFiles($request)
    {
        $user = Auth::user();

        $files = new \stdClass();
        $files->data = [];
        $files->rules = [];

        foreach ($request->address_files as $index => $file) {
            $fileName = 'document_' . $user->uid . '_' . $index;

            $files->data[$fileName] = $file;
            $files->rules[$fileName] = 'file|max:4096|mimetypes:image/jpeg,image/png,application/pdf';
        }

        if (Validator::make($files->data, $files->rules)->fails()) {
            throw new \Exception('Error uploading files');
        }

        $oldDocuments = Document::where('user_id', $user->id)->where('document_type', Document::DOCUMENT_TYPE_ADDRESS)->get();

        // delete old files
        foreach ($oldDocuments as $document) {
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
        }

        // Empty DB records
        Document::where('user_id', $user->id)->where('document_type', Document::DOCUMENT_TYPE_ADDRESS)->delete();

        // insert new files
        foreach ($files->data as $fileName => $file) {
            $newFileName = $fileName . '.' . $file->getClientOriginalExtension();

            $pathToFile = $file->storeAs('documents/address', $newFileName);

            Document::create(
                [
                    'user_id' => $user->id,
                    'document_type' => Document::DOCUMENT_TYPE_ADDRESS,
                    'did' => uniqid(),
                    'file_path' => $pathToFile
                ]
            );
        }

        // Verification
        $verification = $user->verification;
        $verification->address_documents_status = Verification::DOCUMENTS_UPLOADED;
        $verification->address_decline_reason = '';
        $verification->save();
    }

    /**
     * User wallet
     *
     * @return View
     */
    public function wallet()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        return view(
            'user.wallet',
            [
                'wallet' => $wallet,
                'referralLink' => action('IndexController@confirmInvitation', ['ref' => $user->uid]),
            ]
        );
    }

}
