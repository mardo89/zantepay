<?php

namespace App\Http\Controllers;

use App\Mail\InviteFriend;
use App\Models\DB\AreaCode;
use App\Models\DB\EthAddressAction;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Wallet\Currency;
use App\Models\DB\Country;
use App\Models\DB\DebitCard;
use App\Models\DB\Document;
use App\Models\DB\Invite;
use App\Models\DB\Profile;
use App\Models\DB\State;
use App\Models\DB\User;
use App\Models\DB\Verification;
use App\Models\Validation\ValidationMessages;
use App\Models\Wallet\CurrencyFormatter;
use App\Models\Wallet\EtheriumApi;
use App\Models\Wallet\Ico;
use App\Models\Wallet\RateCalculator;
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
        $codes = AreaCode::getCodesList($country);

        return response()->json(
            [
                'states' => $states,
                'codes' => $codes
            ]
        );
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

        // Area Codes List
        $codes = AreaCode::getCodesList($profile->country_id);

        return view(
            'user.profile',
            [
                'user' => $user,
                'profile' => $profile,
                'countries' => $countries,
                'states' => $states,
                'codes' => $codes
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
                'first_name' => 'alpha|max:100|nullable',
                'last_name' => 'alpha|max:100|nullable',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id . ',id',
                'phone_number' => 'digits_between:5,20|nullable',
                'area_code' => 'numeric',
                'country' => 'numeric',
                'state' => 'numeric',
                'city' => 'alpha|max:100|nullable',
                'address' => 'string|nullable',
                'postcode' => 'string|max:10|nullable',
                'passport' => 'string|max:50|nullable',
                'expiration_date' => 'date|after_or_equal:tomorrow',
                'birth_date' => 'date|before_or_equal:-18 year',
                'birth_country' => 'numeric',
            ],
            ValidationMessages::getList(
                [
                    'first_name' => 'First Name',
                    'last_name' => 'Last Name',
                    'email' => 'Email',
                    'phone_number' => 'Phone Number',
                    'area_code' => 'Area Code',
                    'country' => 'Country',
                    'state' => 'State',
                    'city' => 'City',
                    'address' => 'Address',
                    'postcode' => 'Postcode',
                    'passport' => 'Passport / Government ID',
                    'expiration_date' => 'Passport / ID expiry Date',
                    'birth_date' => 'Birth Date',
                    'birth_country' => 'Birth Country',
                ],
                [
                    'email.unique' => 'User with such Email already registered',
                    'country.numeric' => 'Unknown Country',
                    'state.numeric' => 'Unknown State',
                    'phone_number.digits_between' => 'Incorrect phone number format',
                    'expiration_date.after_or_equal' => 'Expiry date cannot be earlier than the current date',
                    'birth_date.before_or_equal' => 'You have to be at least 18 years old to be eligible to buy ZNX',
                ]
            )
        );

        DB::beginTransaction();

        try {
            $profile = Profile::where('user_id', $user->id)->first();

            if (!$profile) {
                return response()->json(
                    [
                        'message' => 'Error updating profile',
                        'errors' => []
                    ],
                    500
                );
            }

            // Update user main info
            $user->email = $request->email;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone_number = $request->phone_number;
            $user->area_code = $request->area_code;
            $user->save();

            // Update profile
            $profile->country_id = $request->country;
            $profile->state_id = $request->state;
            $profile->city = $request->city;
            $profile->address = $request->address;
            $profile->post_code = $request->postcode;
            $profile->passport_id = $request->passport;
            $profile->passport_expiration_date = date('Y-m-d H:i:s', strtotime($request->expiration_date));
            $profile->birth_date = date('Y-m-d H:i:s', strtotime($request->birth_date));
            $profile->birth_country_id = $request->birth_country;
            $profile->save();

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error updating profile',
                    'errors' => []
                ],
                500
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
        $profile = $user->profile;

        return view(
            'user.profile-settings',
            [
                'user' => $user,
                'verification' => $verification,
                'idDocuments' => $userIDDocuments,
                'addressDocuments' => $userAddressDocuments,
                'profile' => $profile,
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

        try {

            $user = Auth::user();

            $document = Document::where('did', $request->did)->where('user_id', $user->id)->first();

            if (!$document) {
                return response()->json(
                    [
                        'message' => 'File not found',
                        'errors' => []
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


        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error deleting file',
                    'errors' => []
                ],
                500
            );

        }


        return response()->json(
            []
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
        $this->validate(
            $request,
            [
                'document_files' => 'required'
            ],
            ValidationMessages::getList(
                [
                    'document_files' => 'Document Files'
                ],
                [
                    'document_files.required' => 'Please select files to download',
                ]
            )
        );

        DB::beginTransaction();

        try {

            $this->uploadIdentityFiles($request);

        } catch (\Exception $e) {
            DB::rollback();

            $code = $e->getCode();

            $message = $code == 422 ? $e->getMessage() : 'Error uploading files';
            $errors = $code == 422 ? [$e->getMessage()] : [];

            return response()->json(
                [
                    'message' => $message,
                    'errors' => $errors
                ],
                $code == 422 ? 422 : 500
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
        $this->validate(
            $request,
            [
                'address_files' => 'required'
            ],
            ValidationMessages::getList(
                [
                    'address_files' => 'Address Files'
                ],
                [
                    'address_files.required' => 'Please select files to download',
                ]
            )
        );

        DB::beginTransaction();

        try {

            $this->uploadAddressFiles($request);

        } catch (\Exception $e) {

            DB::rollback();

            $code = $e->getCode();

            $message = $code == 422 ? $e->getMessage() : 'Error uploading files';
            $errors = $code == 422 ? [$e->getMessage()] : [];

            return response()->json(
                [
                    'message' => $message,
                    'errors' => $errors
                ],
                $code == 422 ? 422 : 500
            );

        }

        DB::commit();

        return response()->json(
            []
        );
    }


    /**
     * Update user wallet address
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
                'address' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'currency' => 'Currency Type',
                    'address' => 'Wallet Address',
                ]
            )
        );

        DB::beginTransaction();

        try {
            $profile = $user->profile;

            switch ($request->currency) {
//                case Currency::CURRENCY_TYPE_BTC:
//                    $profile->btc_wallet = $request->address;
//                    break;

                case Currency::CURRENCY_TYPE_ETH:
                    $profile->eth_wallet = $request->address;
                    break;

            }

            $profile->save();

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error updating wallet',
                    'errors' => []
                ],
                500
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
                'current-password' => 'required|string',
                'password' => 'required|string|min:6|confirmed',
            ],
            ValidationMessages::getList(
                [
                    'current-password' => 'Current Password',
                    'password' => 'Password'
                ],
                [
                    'password.min' => 'The Password field must be at least 6 characters',
                    'password.confirmed' => 'The Password confirmation does not match',
                ]
            )
        );

        $currentPassword = $request->input('current-password');
        $newPassword = $request->input('password');

        try {
            if (User::checkPassword($currentPassword, $user->password) === false) {
                return response()->json(
                    [
                        'message' => 'Error changing password',
                        'errors' => [
                            'current-password' => 'Wrong Current Password'
                        ]
                    ],
                    422
                );
            }

            $user->password = User::hashPassword($newPassword);

            $user->save();

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error changing password',
                    'errors' => []
                ],
                500
            );

        }

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
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                ]
            )
        );

        try {
            $user = Auth::user();

            $email = $request->email;

            $invite = Invite::where('user_id', $user->id)->where('email', $email)->first();

            if (!$invite) {
                $invite = Invite::create(
                    [
                        'user_id' => $user->id,
                        'email' => $email
                    ]
                );

                Mail::to($email)->send(new InviteFriend($user->email, $user->uid));
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Invitation failed',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            [
                'email' => $invite['email'],
                'status' => Invite::getStatus($invite['status'])
            ]
        );
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

        $ico = new Ico();

        $activeIcoPart = $ico->getActivePart();

        $ethRate = optional($activeIcoPart)->getEthRate() ?? 0;
        $startDate = optional($activeIcoPart)->getStartDate() ?? null;
        $icoPartName = optional($activeIcoPart)->getName() ?? '';

        $contributions = [];

        foreach ($user->wallet->contributions as $contribution) {
            $ethAmount = RateCalculator::weiToEth($contribution->amount);

            $contributions[] = [
                'date' => date('d.m.Y', strtotime($contribution->operation_date)),
                'time' => date('H:i:s', strtotime($contribution->operation_date)),
                'address' => $contribution->proxy,
                'amount' => (new CurrencyFormatter($ethAmount))->ethFormat()->withSuffix('ETH')->get(),
                'type' => 'In',
                'status' => 'Pending'
            ];
        }

        $ethAddressAction = EthAddressAction::where('user_id', $user->id)->get()->last();

        return view(
            'user.wallet',
            [
                'wallet' => $wallet,
                'gettingAddress' => optional($ethAddressAction)->status === EthAddressAction::STATUS_IN_PROGRESS,
                'referralLink' => action('IndexController@confirmInvitation', ['ref' => $user->uid]),
                'ico' => [
                    'znx_rate' => (new CurrencyFormatter($ethRate))->ethFormat()->get(),
                    'start_date' => $startDate ? date('Y/m/d H:i:s', strtotime($startDate)) : '',
                    'part_name' => $icoPartName
                ],
                'contributions' => $contributions
            ]
        );
    }


    /**
     * Create Etherium address
     *
     * @return JSON
     */
    public function createWalletAddress()
    {
        $user = Auth::user();

        $lastAction = EthAddressAction::where('user_id', $user->id)->get()->last();

        if (optional($lastAction)->status === EthAddressAction::STATUS_IN_PROGRESS || optional($lastAction)->status === EthAddressAction::STATUS_COMPLETE) {
            return response()->json(
                [
                    'message' => 'Operation in-progress or Etherium address already exists.',
                    'errors' => []
                ],
                500
            );
        }

        $ethAddressAction = EthAddressAction::create(
            [
                'user_id' => $user->id,
            ]
        );

        try {
            $operationID = EtheriumApi::getAddressOID($user->uid);

            $ethAddressAction->operation_id = $operationID;
            $ethAddressAction->save();

            $address = EtheriumApi::createAddress($operationID);

            $ethAddressAction->status = EthAddressAction::STATUS_COMPLETE;
            $ethAddressAction->save();

            $wallet = $user->wallet;
            $wallet->eth_wallet = $address;
            $wallet->save();

        } catch (\Exception $e) {

            $ethAddressAction->status = EthAddressAction::STATUS_FAILED;
            $ethAddressAction->error_message = $e->getMessage();
            $ethAddressAction->save();

            return response()->json(
                [
                    'message' => 'Error creating Wallet Addresss',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            [
                'address' => $wallet->eth_wallet
            ]
        );
    }


    /**
     * Calculator
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function rateCalculator(Request $request)
    {
        $this->validate(
            $request,
            [
                'znx_amount' => 'integer|min:0|max:600000000|required_without:eth_amount',
                'eth_amount' => 'numeric|min:0|max:200000|required_without:znx_amount'
            ],
            ValidationMessages::getList(
                [
                    'znx_amount' => 'ZNX Amount',
                    'eth_amount' => 'ETH Amount'
                ]
            )
        );

        try {
            $ico = new Ico();

            if (isset($request->znx_amount)) {
                $ethAmountParts = RateCalculator::znxToEth($request->znx_amount, time(), $ico);

                $ethAmount = 0;

                foreach ($ethAmountParts as $ethAmountPart) {
                    $ethAmount += $ethAmountPart['amount'];
                }

                $balance = (new CurrencyFormatter($ethAmount))->ethFormat()->get();
            } else {
                $znxAmountParts = RateCalculator::ethToZnx($request->eth_amount, time(), $ico);

                $znxAmount = 0;

                foreach ($znxAmountParts as $znxAmountPart) {
                    $znxAmount += $znxAmountPart['amount'];
                }

                $balance = (new CurrencyFormatter($znxAmount))->znxFormat()->get();
            }

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error calculating amount',
                    'errors' => []
                ],
                500
            );
        }


        return response()->json(
            [
                'balance' => $balance
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

        if (!is_null($card)) {
            return redirect()->action(
                'UserController@debitCardSuccess'
            );
        }

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
        $this->validate(
            $request,
            [
                'design' => 'required|integer'
            ]
        );

        try {
            $user = Auth::user();

            $userDebitCard = [
                'user_id' => $user->id,
                'design' => $request->design
            ];

            $card = DebitCard::where('user_id', $user->id)->first();

            if (!$card) {
                DebitCard::create($userDebitCard);
            } else {
                DebitCard::where('user_id', $user->id)->update($userDebitCard);
            }

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error ordering Debit Card',
                    'errors' => []
                ],
                500
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
        $user = Auth::user();

        $card = DebitCard::where('user_id', $user->id)->first();

        if (!is_null($card)) {
            return redirect()->action(
                'UserController@debitCardSuccess'
            );
        }

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
            ValidationMessages::getList(
                [
                    'verify_later' => 'Verify Later',
                    'document_files' => 'Document Files'
                ],
                [
                    'document_files.required_if' => 'Please select files to download',
                ]
            )
        );

        if ($request->verify_later != 1) {

            DB::beginTransaction();

            try {

                $this->uploadIdentityFiles($request);

            } catch (\Exception $e) {

                DB::rollback();

                $code = $e->getCode();

                $message = $code == 422 ? $e->getMessage() : 'Error uploading files';
                $errors = $code == 422 ? [$e->getMessage()] : [];

                return response()->json(
                    [
                        'message' => $message,
                        'errors' => $errors
                    ],
                    $code == 422 ? 422 : 500
                );

            }

            DB::commit();

        }

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
        $user = Auth::user();

        $card = DebitCard::where('user_id', $user->id)->first();

        if (!is_null($card)) {
            return redirect()->action(
                'UserController@debitCardSuccess'
            );
        }

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
            ValidationMessages::getList(
                [
                    'verify_later' => 'Verify Later',
                    'address_files' => 'Address Files'
                ],
                [
                    'address_files.required_if' => 'Please select files to download',
                ]
            )
        );

        if ($request->verify_later != 1) {

            DB::beginTransaction();

            try {

                $this->uploadAddressFiles($request);

            } catch (\Exception $e) {

                DB::rollback();

                $code = $e->getCode();

                $message = $code == 422 ? $e->getMessage() : 'Error uploading files';
                $errors = $code == 422 ? [$e->getMessage()] : [];

                return response()->json(
                    [
                        'message' => $message,
                        'errors' => $errors
                    ],
                    $code == 422 ? 422 : 500
                );
            }

            DB::commit();

        }

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

        // Debit Card
        $debitCard = DebitCard::where('user_id', $user->id)->first();

        if (!is_null($debitCard)) {
            $userDebitCard = $debitCard->design;
        } else {
            $userDebitCard = null;
        }

        return view(
            'user.debit-card-success',
            [
                'referralLink' => action('IndexController@confirmInvitation', ['ref' => $user->uid]),
                'debitCard' => $userDebitCard
            ]
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
            throw new \Exception('Incorrect files format', 422);
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
            throw new \Exception('Incorrect files format', 422);
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

}
