<?php

namespace App\Http\Middleware;

use App\Models\DB\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAuthenticatedManager
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

        // Check status
        if ($user->isDisabled() || $user->isClosed()) {
            return redirect('/');
        }

        // Check role
        if ($user->role == User::USER_ROLE_USER) {
            return redirect('/');
        }

        return $next($request);
    }
}
