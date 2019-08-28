<?php

namespace Botble\ACL\Http\Middleware;

use Auth;
use Botble\ACL\Models\User;
use Closure;

class RedirectIfAuthenticated
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
        if (Auth::guard($guard)->check()) {
            /**
             * @var User $user
             */
            $user = Auth::user();
            if ($user->hasPermission('dashboard.index')) {
                return redirect(route('dashboard.index'));
            }

            return redirect()->to('/');
        }

        return $next($request);
    }
}
