<?php

namespace App\Http\Middleware;

use App\Models\DB\User;
use App\Models\Services\MailService;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class ProtectAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        $user = Auth::user();

        $authToken = Session::get('auth_token') ?? '';

        $isTokenCorrect = password_verify($user->email . $user->id . $user->password, $authToken);

        if(!$isTokenCorrect) {

            Auth::logout();

            return redirect('/');

        }

        return $next($request);
    }
}
