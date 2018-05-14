<?php

namespace App\Http\Controllers;

use App\Models\DB\AreaCode;
use App\Models\DB\EthAddressAction;
use App\Models\DB\TransferTransaction;
use App\Models\DB\WithdrawTransaction;
use App\Models\DB\ZantecoinTransaction;
use App\Models\Services\AccountsService;
use App\Models\Services\AuthService;
use App\Models\Services\BonusesService;
use App\Models\Services\DocumentsService;
use App\Models\Services\InvitesService;
use App\Models\Services\MailService;
use App\Models\Services\UsersService;
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
        $this->middleware('protect.auth');
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
        $this->validate(
            $request,
            [
                'country' => 'numeric|bail'
            ]
        );

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
     * Accept Terms
     *
     * @return View
     */
    public function acceptTerms()
    {
        DB::beginTransaction();

        try {

            $user = AccountsService::getActiveUser();

            UsersService::changeUserStatus($user, User::USER_STATUS_NOT_VERIFIED);

            MailService::sendWelcomeEmail($user->email);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error accepting Terms and Conditions',
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
                'first_name' => 'alpha|max:100|nullable|bail',
                'last_name' => 'alpha|max:100|nullable|bail',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id . ',id|bail',
                'phone_number' => 'digits_between:5,20|nullable|bail',
                'area_code' => 'numeric|bail',
                'country' => 'numeric|bail',
                'state' => 'numeric|bail',
                'city' => 'alpha|max:100|nullable|bail',
                'address' => 'string|nullable|bail',
                'postcode' => 'string|max:10|nullable|bail',
                'passport' => 'string|max:50|nullable|bail',
                'expiration_date' => 'date|after_or_equal:tomorrow|bail',
                'birth_date' => 'date|before_or_equal:-18 year|bail',
                'birth_country' => 'numeric|bail',
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

        if ($request->first_name && $request->last_name && $request->first_name === $request->last_name) {
            return response()->json(
                [
                    'message' => 'Error updating profile',
                    'errors' => [
                        'first_name' => 'First and last name should not be the same',
                        'last_name' => ''
                    ]
                ],
                422
            );
        }

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
            $user->need_relogin = 1;
            $user->save();

            // Update Secret Token
            AuthService::updateAuthToken($user->id, $user->email, $user->password);

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
     * Close user profile
     *
     * @param Request $request
     *
     * @return View
     */
    public function closeAccount(Request $request)
    {
        DB::beginTransaction();

        try {

            AccountsService::closeAccount();

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error closing account',
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

            // Change user status
            if ($verification->id_documents_status == Verification::DOCUMENTS_APPROVED) {
                UsersService::changeUserStatus($user, User::USER_STATUS_IDENTITY_VERIFIED);
            } elseif ($verification->address_documents_status == Verification::DOCUMENTS_APPROVED) {
                UsersService::changeUserStatus($user, User::USER_STATUS_ADDRESS_VERIFIED);
            } else {
                UsersService::changeUserStatus($user, User::USER_STATUS_NOT_VERIFIED);
            }


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
                'document_files' => 'required|bail'
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
                'address_files' => 'required|bail'
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
                'currency' => 'required|numeric|bail',
                'address' => 'required|string|bail',
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
        $this->validate(
            $request,
            [
                'current-password' => 'required|string|bail',
                'password' => 'required|string|min:6|max:32|different:current-password|bail',
                'password_confirmation' => 'required_with:password|same:password|bail',
            ],
            ValidationMessages::getList(
                [
                    'current-password' => 'Current Password',
                    'password' => 'Password',
                    'password_confirmation' => 'Password Confirmation',
                ],
                [
                    'password.min' => 'The Password field must be at least 6 characters',
                    'password.different' => 'New password and current password should not be the same',
                    'password_confirmation.required_with' => 'The Password Confirmation does not match',
                    'password_confirmation.same' => 'The Password Confirmation does not match',
                ]
            )
        );

        try {

            $currentPassword = $request->input('current-password');
            $newPassword = $request->input('password');

            AccountsService::changePassword($currentPassword, $newPassword);


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
        $user = AccountsService::getActiveUser();

        $invitedUsers = InvitesService::getInvitedUsers($user);

        return view(
            'user.invite-friend',
            [
                'referralLink' => action('IndexController@confirmInvitation', ['ref' => $user->uid]),
                'invitedUsers' => $invitedUsers
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
        $user = Auth::user();

        $this->validate(
            $request,
            [
                'email' => 'required|string|email|max:255|bail'
            ],
            ValidationMessages::getList(
                [
                    'email' => 'Email',
                ]
            )
        );

        try {
            $email = $request->email;

            $invite = Invite::where('user_id', $user->id)->where('email', $email)->first();

            if (!$invite) {
                $invite = Invite::create(
                    [
                        'user_id' => $user->id,
                        'email' => $email
                    ]
                );
            }

            MailService::sendInviteFriendEmail($email, $user->uid);


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
        $lastIcoPart = $ico->getIcoPartFour();

        $ethRate = optional($activeIcoPart)->getEthRate() ?? 0;
        $endDate = optional($lastIcoPart)->getEndDate() ?? null;
        $icoPartName = optional($activeIcoPart)->getName() ?? '';

        $userTransactions = [];

        $contributionTransactions = $user->wallet->contributions ?? [];
        $transferTransactions = $user->transferTransactions ?? [];
        $withdrawTransactions = $user->withdrawTransactions ?? [];

        foreach ($contributionTransactions as $contributionTransaction) {
            $ethAmount = RateCalculator::weiToEth($contributionTransaction->amount);

            $userTransactions[] = [
                'date' => date('d.m.Y', $contributionTransaction->time_stamp),
                'time' => date('H:i:s', $contributionTransaction->time_stamp),
                'address' => $contributionTransaction->proxy,
                'amount' => (new CurrencyFormatter($ethAmount))->ethFormat()->withSuffix('ETH')->get(),
                'type' => 'Buy',
                'status' => 'SUCCESS'
            ];
        }

        foreach ($transferTransactions as $transferTransaction) {
            $ethAmount = $transferTransaction->eth_amount;

            $userTransactions[] = [
                'date' => date('d.m.Y', strtotime($transferTransaction->created_at)),
                'time' => date('H:i:s', strtotime($transferTransaction->created_at)),
                'address' => '',
                'amount' => (new CurrencyFormatter($ethAmount))->ethFormat()->withSuffix('ETH')->get(),
                'type' => 'Transfer',
                'status' => 'SUCCESS'
            ];
        }

        foreach ($withdrawTransactions as $withdrawTransaction) {
            $ethAmount = $withdrawTransaction->amount;

            $userTransactions[] = [
                'date' => date('d.m.Y', strtotime($withdrawTransaction->created_at)),
                'time' => date('H:i:s', strtotime($withdrawTransaction->created_at)),
                'address' => $withdrawTransaction->wallet_address,
                'amount' => (new CurrencyFormatter($ethAmount))->ethFormat()->withSuffix('ETH')->get(),
                'type' => 'Withdraw',
                'status' => $withdrawTransaction->status
            ];
        }

        $ethAddressAction = EthAddressAction::where('user_id', $user->id)->get()->last();

        $availableZnxAmount = (new CurrencyFormatter($wallet->znx_amount))->znxFormat()->withSuffix('ZNX tokens')->get();

        return view(
            'user.wallet',
            [
                'wallet' => $wallet,
                'availableAmount' => $availableZnxAmount,
                'gettingAddress' => optional($ethAddressAction)->status === EthAddressAction::STATUS_IN_PROGRESS,
                'referralLink' => action('IndexController@confirmInvitation', ['ref' => $user->uid]),
                'ico' => [
                    'znx_rate' => (new CurrencyFormatter($ethRate))->ethFormat()->get(),
                    'end_date' => $endDate ? date('Y/m/d H:i:s', strtotime($endDate)) : '',
                    'part_name' => $icoPartName
                ],
                'transactions' => $userTransactions,
                'showWelcome' => $user->status == User::USER_STATUS_PENDING
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
                'znx_amount' => 'integer|min:0|max:600000000|required_without:eth_amount|bail',
                'eth_amount' => 'numeric|min:0|max:200000|required_without:znx_amount|bail'
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
     * Transfer Commission Bonus to ZNX
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function transferEth(Request $request)
    {
        $user = Auth::user();

        $this->validate(
            $request,
            [
                'eth_amount' => 'required|numeric|min:0.1|max:200000|bail'
            ],
            ValidationMessages::getList(
                [
                    'eth_amount' => 'ETH Amount'
                ],
                [
                    'eth_amount.min' => 'You cannot withdraw less than 0.1 ETH',
                    'eth_amount.max' => 'You cannot withdraw more than 200,000 ETH',
                ]
            )
        );

        $userWallet = $user->wallet;
        $ethAmount = (float)$request->eth_amount;

        if ($userWallet->commission_bonus < $ethAmount) {
            return response()->json(
                [
                    'message' => 'Not enough ETH to transfer',
                    'errors' => []
                ],
                500
            );
        }

        $ethAmount = $request->eth_amount;

        DB::beginTransaction();

        try {

            // Create transaction
            $transferTransaction = TransferTransaction::create(
                [
                    'user_id' => $user->id,
                    'eth_amount' => $ethAmount,
                ]
            );

            // Convert ETH to ZNX
            $ico = new Ico();

            $znxAmountParts = RateCalculator::ethToZnx($ethAmount, time(), $ico);

            $znxAmount = 0;

            foreach ($znxAmountParts as $znxAmountPart) {
                ZantecoinTransaction::create(
                    [
                        'user_id' => $user->id,
                        'amount' => $znxAmountPart['amount'],
                        'ico_part' => $znxAmountPart['icoPart'],
                        'contribution_id' => $transferTransaction->id,
                        'transaction_type' => ZantecoinTransaction::TRANSACTION_COMMISSION_TO_ZNX
                    ]
                );

                $znxAmount += $znxAmountPart['amount'];
            }

            // Update transaction
            $transferTransaction->znx_amount = $znxAmount;
            $transferTransaction->save();

            // Update Wallet
            $userWallet->znx_amount += $znxAmount;
            $userWallet->commission_bonus -= $ethAmount;
            $userWallet->save();

            MailService::sendTokenAddEmail(
                $user->email,
                (new CurrencyFormatter($znxAmount))->znxFormat()->withSuffix('ZNX')->get()
            );

            $balance = (new CurrencyFormatter($znxAmount))->znxFormat()->get();
            $total = (new CurrencyFormatter($userWallet->znx_amount))->znxFormat()->withSuffix('ZNX tokens')->get();

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => $e->getMessage(),//'Error transferring ETH',
                    'errors' => []
                ],
                500
            );
        }

        DB::commit();

        return response()->json(
            [
                'balance' => $balance,
                'total' => $total
            ]
        );
    }

    /**
     * Withdraw ETH
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function withdrawEth(Request $request)
    {
        $user = Auth::user();

        $this->validate(
            $request,
            [
                'address' => 'required|string|bail',
            ],
            ValidationMessages::getList(
                [
                    'address' => 'Address'
                ]
            )
        );

        $userWallet = $user->wallet;

        if ($userWallet->commission_bonus == 0) {
            return response()->json(
                [
                    'message' => 'Not enough ETH to withdraw',
                    'errors' => []
                ],
                500
            );
        }

        DB::beginTransaction();

        try {
            // Create transaction
            WithdrawTransaction::create(
                [
                    'user_id' => $user->id,
                    'amount' => $userWallet->commission_bonus,
                    'wallet_address' => $request->address
                ]
            );

            // Set commission bonus to 0
            $userWallet->commission_bonus = 0;
            $userWallet->save();

            // Update user status
            $user->status = User::USER_STATUS_WITHDRAW_PENDING;
            $user->save();

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error withdrawing ETH',
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
     * Debit card country
     *
     * @return View
     */
    public function debitCardCountry()
    {
        $user = Auth::user();

        $card = DebitCard::where('user_id', $user->id)->first();

        if (!is_null($card)) {
            return redirect()->action(
                'UserController@debitCardSuccess'
            );
        }

        $verificationComplete = DocumentsService::verificationComplete($user);

        return view(
            'user.debit-card-country',
            [
                'isVerified' => $verificationComplete
            ]
        );
    }


    /**
     * Save debit card country
     *
     * @param Request $request
     *
     * @return json
     */
    public function saveDebitCardCountry(Request $request)
    {
        $this->validate(
            $request,
            [
                'country' => 'required|bail'
            ],
            ValidationMessages::getList(
                [
                    'country' => 'Country'
                ],
                [
                    'country.required' => 'Please select your country',
                ]
            )

        );

        return response()->json(
            [
                'nextStep' => action('UserController@debitCardDesign')
            ]
        );
    }

    /**
     * Debit card design
     *
     * @return View
     */
    public function debitCardDesign()
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
    public function saveDebitCardDesign(Request $request)
    {
        $user = Auth::user();

        $this->validate(
            $request,
            [
                'design' => 'required|integer|bail'
            ]
        );

        DB::beginTransaction();

        try {

            $userDebitCard = [
                'user_id' => $user->id,
                'design' => $request->design
            ];

            $card = DebitCard::where('user_id', $user->id)->first();

            if (!$card) {

                DebitCard::create($userDebitCard);

                BonusesService::updateBonus($user);

                MailService::sendOrderDebitCardEmail($user->email, $user->uid, $userDebitCard['design']);

            } else {

                DebitCard::where('user_id', $user->id)->update($userDebitCard);

            }

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error ordering Debit Card',
                    'errors' => []
                ],
                500
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
                'verify_later' => 'required|boolean|bail',
                'document_files' => 'required_if:verify_later,0|bail'
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
                'verify_later' => 'required|boolean|bail',
                'address_files' => 'required_if:verify_later,0|bail'
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

        // Change Status
        UsersService::changeUserStatus($user, User::USER_STATUS_VERIFICATION_PENDING);
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

        // Change Status
        UsersService::changeUserStatus($user, User::USER_STATUS_VERIFICATION_PENDING);
    }

}
