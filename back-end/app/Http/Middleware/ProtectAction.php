<?php

namespace App\Http\Middleware;

use App\Models\DB\User;
use App\Models\Services\MailService;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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

        $requestParams = $request->except(['signature', 'action']);
        $requestParams['_timestamp_'] = time();

        try {

            $decryptedStr = Crypt::decryptString($request->signature);
            $decryptedParams = unserialize($decryptedStr);

            $isSignatureCorrect = true;

            // signature must contain timestamp
            if (!$decryptedParams || !isset($decryptedParams['_timestamp_'])) {
                $isSignatureCorrect = false;
            }

            // signature expired in 5 minutes
            if (time() - $decryptedParams['_timestamp_'] > 120) {
                $isSignatureCorrect = false;
            }

            unset($decryptedParams['_timestamp_']);

            foreach ($decryptedParams as $name => $value) {
                if ($requestParams[$name] != $value) {
                    $isSignatureCorrect = false;
                }
            }

            if ($isSignatureCorrect === false) {

                throw new \Exception('Incorrect signature');

            }

        } catch (\Exception $e) {

            $signature = Crypt::encryptString(serialize($requestParams));
            $action = $request->action ?? 'Unknown action';

            MailService::sendProtectActionEmail($user->email, $signature, $action);

            return abort(205);

        }

        return $next($request);
    }
}
