<?php

namespace App\Http\Middleware;

use App\Models\DB\User;
use App\Models\Services\MailService;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use PHPUnit\Runner\Exception;

class ProtectAction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        if ($user->status == User::USER_STATUS_INACTIVE) {
            return redirect('/');
        }

        $requestParams = array_values($request->except(['signature']));

        $signatureStr = implode('&', $requestParams);

        try {

            if (Crypt::decryptString($request->signature) !== $signatureStr) {

                throw new \Exception('Incorrect signature');

            }

        } catch (\Exception $e) {

            $signature = Crypt::encryptString($signatureStr);

            MailService::sendProtectActionEmail($user->email, $signature);

            return abort(205);

        }

        return $next($request);
    }
}
