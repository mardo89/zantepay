<?php

namespace App\Http\Controllers;

use App\Mail\InviteFriend;
use App\Models\Country;
use App\Models\DebitCard;
use App\Models\Document;
use App\Models\Invite;
use App\Models\Profile;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PHPUnit\Runner\Exception;


class UserController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * User profile
     *
     * @return View
     */
    public function profile()
    {
        $user = Auth::user();

        $profile = Profile::where('user_id', $user->id)->first();

        if (!$profile) {
            return redirect('/');
        }

        $passportExpDate = is_null($profile->passport_expiration_date)
            ? date('m/d/Y')
            : date('m/d/Y', strtotime($profile->passport_expiration_date));

        $birthDate = is_null($profile->birth_date)
            ? date('m/d/Y')
            : date('m/d/Y', strtotime($profile->birth_date));

        $userProfile = [
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone_number' => $user->phone_number,
            'country_id' => $profile->country_id,
            'state_id' => $profile->state_id,
            'city' => $profile->city,
            'address' => $profile->address,
            'postcode' => $profile->postcode,
            'passport_id' => $profile->passport_id,
            'passport_expiration_date' => $passportExpDate,
            'birth_date' => $birthDate,
            'birth_country' => $profile->birth_country
        ];

        // Countries List
        $dbCountries = Country::all();

        $countries = [];

        foreach ($dbCountries as $dbCountry) {
            $countries[] = [
                'id' => (int)$dbCountry->id,
                'name' => $dbCountry->name
            ];
        }

        $countries[] = [
            'id' => 0,
            'name' => 'Other country'
        ];

        // States List
        $dbStates = $userProfile['country_id'] === 0
            ? []
            : State::where('country_id', $userProfile['country_id'])->orderBy('name', 'asc')->get();

        $states = [];

        foreach ($dbStates as $dbState) {
            $states[] = [
                'id' => (int)$dbState->id,
                'name' => $dbState->name
            ];
        }

        $states[] = [
            'id' => 0,
            'name' => 'Other state'
        ];

        return view(
            'user.profile',
            [
                'profile' => $userProfile,
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

        $this->validate($request, [
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
        ]);

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
                'referralLink' => action('IndexController@invitation', ['ref' => $user->uid]),
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
                'nextStep' => action('UserController@debitCardDocuments')
            ]
        );
    }

    /**
     * Debit card documents
     *
     * @return View
     */
    public function debitCardDocuments()
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
    public function saveDebitCardDocuments(Request $request)
    {
        $user = Auth::user();

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

        $files = new \stdClass();
        $files->data = [];
        $files->rules = [];

        foreach ($request->document_files as $index => $file) {
            $fileName = 'user_' . $user->id . '_' . $index;

            $files->data[$fileName] = $file;
            $files->rules[$fileName] = 'file|max:4096|mimetypes:image/jpeg,image/png,application/pdf';
        }

        $isFailed = Validator::make($files->data, $files->rules)->fails();

        if ($isFailed) {
            return response()->json(
                [
                    'message' => 'Error uploading documents',
                    'errors' => ['Incorrect files format']
                ],
                422
            );
        }

        try {
            $oldDocuments = Document::where('user_id', $user->id)->where('document_type', Document::DOCUMENT_TYPE_IDENTITY)->get();

            // delete old files
            foreach ($oldDocuments as $document) {
                if (Storage::exists($document->file_name)) {
                    Storage::delete($document->file_name);
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

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error uploading files',
                    'errors' => ['Error uploading file']
                ],
                422
            );

        }

        return response()->json(
            [
                'nextStep' => action('UserController@debitCardAddress')
            ]
        );
    }

    /**
     * Debit card address
     *
     * @return View
     */
    public function debitCardAddress()
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
    public function saveDebitCardAddress(Request $request)
    {
        $user = Auth::user();

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

        $files = new \stdClass();
        $files->data = [];
        $files->rules = [];

        foreach ($request->address_files as $index => $file) {
            $fileName = 'user_' . $user->id . '_' . $index;

            $files->data[$fileName] = $file;
            $files->rules[$fileName] = 'file|max:4096|mimetypes:image/jpeg,image/png,application/pdf';
        }

        $isFailed = Validator::make($files->data, $files->rules)->fails();

        if ($isFailed) {
            return response()->json(
                [
                    'message' => 'Error uploading documents',
                    'errors' => ['Incorrect files format']
                ],
                422
            );
        }

        try {
            $oldDocuments = Document::where('user_id', $user->id)->where('document_type', Document::DOCUMENT_TYPE_ADDRESS)->get();

            // delete old files
            foreach ($oldDocuments as $document) {
                if (Storage::exists($document->file_name)) {
                    Storage::delete($document->file_name);
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

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error uploading files',
                    'errors' => ['Error uploading file']
                ],
                422
            );

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

        return view(
            'user.debit-card-success',
            [
                'referralLink' => action('IndexController@invitation', ['ref' => $user->uid]),
            ]
        );
    }


}
