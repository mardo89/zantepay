<?php

namespace App\Http\Controllers;

use App\Models\DB\ZantecoinTransaction;
use App\Models\Wallet\Currency;
use App\Models\DB\Country;
use App\Models\DB\DebitCard;
use App\Models\DB\Document;
use App\Models\DB\Invite;
use App\Models\DB\PasswordReset;
use App\Models\DB\Profile;
use App\Models\DB\SocialNetworkAccount;
use App\Models\DB\State;
use App\Models\DB\User;
use App\Models\DB\Verification;
use App\Models\DB\Wallet;
use App\Models\Validation\ValidationMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
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
        $this->validate(
            $request, [
            'uid' => 'required|string',
            'role' => 'required|integer',
        ],
            ValidationMessages::getList(
                [
                    'role' => 'User Role'
                ],
                [
                    'role.integer' => 'Unknown User Role',
                ]
            )
        );

        try {

            $user = User::where('uid', $request->uid)->first();

            if (is_null($user)) {
                throw new \Exception('User does not exist');
            }

            $user->role = $request->role;
            $user->save();

        } catch (\Exception $e) {

            return response()->json(
                [
                    'message' => 'Error changing role',
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
     * Remove user with all data
     *
     * @param Request $request
     *
     * @return View
     */
    public function removeProfile(Request $request)
    {
        $this->validate($request, [
            'uid' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $user = User::where('uid', $request->uid)->first();

            if (is_null($user)) {
                throw new \Exception('User does not exist');
            }

            Profile::where('user_id', $user->id)->delete();
            Invite::where('user_id', $user->id)->delete();
            DebitCard::where('user_id', $user->id)->delete();
            Verification::where('user_id', $user->id)->delete();
            PasswordReset::where('email', $user->email)->delete();
            SocialNetworkAccount::where('user_id', $user->id)->delete();
            ZantecoinTransaction::where('user_id', $user->id)->delete();
            Wallet::where('user_id', $user->id)->delete();

            // Documents
            $documents = Document::where('user_id', $user->id)->get();
            foreach ($documents as $document) {
                if (Storage::exists($document->file_path)) {
                    Storage::delete($document->file_path);
                }
            }
            Document::where('user_id', $user->id)->delete();

            $user->delete();

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error deleting user',
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
     * Wallets operations
     *
     * @return View
     */
    public function wallet()
    {
        return view(
            'admin.wallet',
            [
            ]
        );
    }

}
