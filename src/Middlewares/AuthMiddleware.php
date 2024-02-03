<?php

namespace Benaaacademy\Auth\Middlewares;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthMiddleware
 */
class AuthMiddleware
{

    /**
     * @param $request
     * @param Closure $next
     * @param null $guard
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $guard == "api") {
                $response = new BenaaacademyResponse();
                return $response->json(NULL, "Authentication error", 401);
            } else {
                return redirect()->route('admin.auth.login')->with("url", $request->url());
            }
        }

        return $next($request);
    }
}
