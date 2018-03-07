<?php

namespace App\Http\Controllers;

use App\Models\DB\Contribution;
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
use App\Models\Wallet\EtheriumApi;
use App\Models\Wallet\Ico;
use App\Models\Wallet\RateCalculator;
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
            $request,
            [
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
        $this->validate(
            $request,
            [
                'uid' => 'required|string',
            ],
            ValidationMessages::getList(
                [
                    'uid' => 'User ID',
                ]
            )
        );

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

        // ICO Table
        $ico = new Ico();

        $currentDate = time();

        $icoInfo = [];

        foreach ($ico->getParts() as $icoPart) {
            $startDate = $icoPart->getStartDate();
            $endDate = $icoPart->getEndDate();

            $weiReceived = Contribution::where('time_stamp', '>=', strtotime($startDate))
                ->where('time_stamp', '<', strtotime($endDate))
                ->get()
                ->sum('amount');

            $icoName = $icoPart->getName();

            if (strtotime($icoPart->getStartDate()) < $currentDate) {
                $icoName .= ' (started ' . $startDate . ')';
            } else {
                $icoName .= ' (starts ' . $startDate . ')';
            }

            if ($icoPart->getID() === $ico->getActivePart()->getID()) {
                $icoName .= ' - current';
            }

            $icoInfo[] = [
                'name' => $icoName,
                'limit' => number_format($icoPart->getLimit(), 0, '.', ' '),
                'balance' => number_format($icoPart->getBalance(), 0, '.', ' '),
                'eth' => RateCalculator::weiToEth($weiReceived)
            ];
        }

        // Issue Tokens Table
        $znxTransactions = ZantecoinTransaction::all();

        return view(
            'admin.wallet',
            [
                'ico' => $icoInfo,
            ]
        );
    }

    /**
     * Grant Marketing Coins
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function grantMarketingCoins(Request $request)
    {
        $this->validate(
            $request,
            [
                'address' => 'required|string',
                'amount' => 'required|integer',
            ],
            ValidationMessages::getList(
                [
                    'address' => 'Beneficiary Address',
                    'amount' => 'Grant ZNX Amount',
                ]
            )
        );

        DB::beginTransaction();

        try {

            /**
             * @todo store transactions in the db
             */
            EtheriumApi::marketingCoins($request->amount, $request->address);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(
                [
                    'message' => 'Error granting coins',
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

}
