<?php

namespace Benaaacademy\Auth\Middlewares;

use Auth;
use Closure;
use Illuminate\Support\Facades\Config;

class GuestMiddleware
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

            $redirect_path = Config::get("admin.default_path");

            return redirect(ADMIN . "/" . trim($redirect_path));
        }

        return $next($request);
    }
}
