<?php

namespace App\Http\Controllers;

use App\Exceptions\EtheriumException;
use App\Exceptions\TransactionException;
use App\Models\Services\AccountsService;
use App\Models\Services\CountriesService;
use App\Models\Services\DebitCardsService;
use App\Models\Services\DocumentsService;
use App\Models\Services\EtheriumService;
use App\Models\Services\InvitesService;
use App\Models\Services\ProfilesService;
use App\Models\Services\TransactionsService;
use App\Models\Services\UsersService;
use App\Models\Services\VerificationService;
use App\Models\Services\WalletsService;
use App\Models\Validation\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psy\Util\Json;


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

        $states = CountriesService::getCountryStates($request->country);
        $codes = CountriesService::getCountryCodes($request->country);

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
     * @param Request $request
     *
     * @return Json
     */
    public function acceptTerms(Request $request)
    {
        DB::beginTransaction();

        try {

            $toNewsletters = (bool) $request->to_newsletters;

            AccountsService::acceptTerms($toNewsletters);

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
        $user = AccountsService::getActiveUser();

        // Profile
        $profile = ProfilesService::getProfileInfo($user);

        // Countries List
        $countries = CountriesService::getCountries();;

        // States List
        $states = CountriesService::getCountryStates($profile->country_id);

        // Area Codes List
        $codes = CountriesService::getCountryCodes($profile->country_id);

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
        $user = AccountsService::getActiveUser();

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

            // Update user main info
            UsersService::updateUser(
                $user,
                [
                    'email' => $request->email,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone_number' => $request->phone_number,
                    'area_code' => $request->area_code,
                ]
            );

            // Update profile
            ProfilesService::updateProfile(
                $user,
                [
                    'country' => $request->country,
                    'state' => $request->state,
                    'city' => $request->city,
                    'address' => $request->address,
                    'postcode' => $request->postcode,
                    'passport' => $request->passport,
                    'expiration_date' => date('Y-m-d H:i:s', strtotime($request->expiration_date)),
                    'birth_date' => date('Y-m-d H:i:s', strtotime($request->birth_date)),
                    'birth_country' => $request->birth_country,
                ]
            );

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
        $user = AccountsService::getActiveUser();

        $profile = ProfilesService::getProfileInfo($user);

        $verificationPending = VerificationService::verificationPending($user->verification);

        $verificationStatus = VerificationService::verificationStatus($user->verification);

        return view(
            'user.profile-settings',
            [
                'user' => $user,
                'profile' => $profile,
                'verificationPending' => $verificationPending,
                'verificationStatus' => $verificationStatus
            ]
        );
    }


//    /**
//     * Remove document
//     *
//     * @param Request $request
//     *
//     * @return json
//     */
//    public function removeDocument(Request $request)
//    {
//
//        try {
//
//            $user = AccountsService::getActiveUser();
//
//            DocumentsService::removeUserDocument($user, $request->did);
//
//        } catch (\Exception $e) {
//
//            return response()->json(
//                [
//                    'message' => 'Error deleting file',
//                    'errors' => []
//                ],
//                500
//            );
//
//        }
//
//        return response()->json(
//            []
//        );
//    }
//
//
//    /**
//     * Upload identity documents
//     *
//     * @param Request $request
//     *
//     * @return json
//     */
//    public function uploadIdentityDocuments(Request $request)
//    {
//        $this->validate(
//            $request,
//            [
//                'document_files' => 'required|bail'
//            ],
//            ValidationMessages::getList(
//                [
//                    'document_files' => 'Document Files'
//                ],
//                [
//                    'document_files.required' => 'Please select files to download',
//                ]
//            )
//        );
//
//        DB::beginTransaction();
//
//        try {
//
//            $user = AccountsService::getActiveUser();
//
//            DocumentsService::uploadIdentityFiles($user, $request);
//
//        } catch (\Exception $e) {
//            DB::rollback();
//
//            $code = $e->getCode();
//
//            $message = $code == 422 ? $e->getMessage() : 'Error uploading files';
//            $errors = $code == 422 ? [$e->getMessage()] : [];
//
//            return response()->json(
//                [
//                    'message' => $message,
//                    'errors' => $errors
//                ],
//                $code == 422 ? 422 : 500
//            );
//
//        }
//
//        DB::commit();
//
//        return response()->json(
//            []
//        );
//    }
//
//
//    /**
//     * Upload address documents
//     *
//     * @param Request $request
//     *
//     * @return json
//     */
//    public function uploadAddressDocuments(Request $request)
//    {
//        $this->validate(
//            $request,
//            [
//                'address_files' => 'required|bail'
//            ],
//            ValidationMessages::getList(
//                [
//                    'address_files' => 'Address Files'
//                ],
//                [
//                    'address_files.required' => 'Please select files to download',
//                ]
//            )
//        );
//
//        DB::beginTransaction();
//
//        try {
//
//            $user = AccountsService::getActiveUser();
//
//            DocumentsService::uploadAddressFiles($user, $request);
//
//
//        } catch (\Exception $e) {
//
//            DB::rollback();
//
//            $code = $e->getCode();
//
//            $message = $code == 422 ? $e->getMessage() : 'Error uploading files';
//            $errors = $code == 422 ? [$e->getMessage()] : [];
//
//            return response()->json(
//                [
//                    'message' => $message,
//                    'errors' => $errors
//                ],
//                $code == 422 ? 422 : 500
//            );
//
//        }
//
//        DB::commit();
//
//        return response()->json(
//            []
//        );
//    }


    /**
     * Update user wallet address
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function updateWallet(Request $request)
    {
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

            $user = AccountsService::getActiveUser();

            ProfilesService::updateWalletAddress($user, $request->address, $request->currency);

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

            $user = AccountsService::getActiveUser();
            $invite = InvitesService::createInvite($user, $request->email);

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
            $invite
        );
    }


    /**
     * User wallet
     *
     * @return View
     */
    public function wallet()
    {
        $user = AccountsService::getActiveUser();

        $walletInfo = WalletsService::getInfo($user);

        return view(
            'user.wallet',
            $walletInfo
        );
    }


    /**
     * Create Etherium address
     *
     * @return JSON
     */
    public function createWalletAddress()
    {
        try {

            $user = Auth::user();

            $address = EtheriumService::createAddress($user);

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => ($e instanceof EtheriumException) ? $e->getMessage() : 'Error creating Wallet Addresss',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            [
                'address' => $address
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

            $balance = EtheriumService::exchangeCalculator($request->znx_amount, $request->eth_amount);

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

        DB::beginTransaction();

        try {

            $user = AccountsService::getActiveUser();

            $balance = TransactionsService::createTransferZnxTransaction($user, $request->eth_amount);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => ($e instanceof TransactionException) ? $e->getMessage() : 'Error transferring ETH',
                    'errors' => []
                ],
                500
            );
        }

        DB::commit();

        return response()->json(
            $balance
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

        DB::beginTransaction();

        try {

            $user = AccountsService::getActiveUser();

            TransactionsService::createWithdrawEthTransaction($user, $request->address);

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
        $user = AccountsService::getActiveUser();

        if (DebitCardsService::checkDebitCard($user->id)) {
            return redirect()->action(
                'UserController@debitCardSuccess'
            );
        }

        if (!VerificationService::verificationComplete($user->verification)) {

            return view(
                'user.debit-card-verify',
                [
                    'referralLink' => action('IndexController@confirmInvitation', ['ref' => $user->uid]),
                ]
            );

        }

        if (!ProfilesService::isEuropeCitizenship($user)) {

            return view('user.debit-card-country');

        }

        return view('user.debit-card-design');
    }


    /**
     * Debit card design
     *
     * @return View
     */
    public function debitCardDesign()
    {
        return view('user.debit-card-design');
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
        $this->validate(
            $request,
            [
                'design' => 'required|integer|bail'
            ]
        );

        DB::beginTransaction();

        try {

            $user = AccountsService::getActiveUser();

            DebitCardsService::createDebitCard($user, $request->design);

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
     * Debit card pre-order success
     *
     * @return View
     */
    public function debitCardSuccess()
    {
        $user = AccountsService::getActiveUser();

        $userDebitCard = DebitCardsService::getDebitCardDesign($user->id);

        return view(
            'user.debit-card-success',
            [
                'referralLink' => action('IndexController@confirmInvitation', ['ref' => $user->uid]),
                'debitCard' => $userDebitCard
            ]
        );
    }

    /**
     * Save data from Verify.me request
     *
     * @param Request $request
     *
     * @return json
     */
    public function trackVerifyRequest(Request $request)
    {
        $this->validate(
            $request,
            [
                'session_id' => 'required|string|bail'
            ]
        );


        try {

            $user = AccountsService::getActiveUser();

            VerificationService::trackVerificationRequest($user->verification, $request->session_id);

        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(
                [
                    'message' => 'Verification error',
                    'errors' => []
                ],
                500
            );

        }

        return response()->json(
            []
        );
    }

}
