<?php

namespace Benaaacademy\Auth\Controllers;

use Benaaacademy\Platform\APIController;
use Benaaacademy\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class AuthApiController
 */
class AuthApiController extends APIController
{

    /**
     * AuthApiController constructor.
     */
    function __construct(Request $request)
    {
        parent::__construct($request);
    }

    /**
     * Create a new API access token
     * @param string $username (required) The username.
     * @param string $password (required) The password.
     * @return \Illuminate\Http\JsonResponse
     */
    function access(Request $request)
    {

        if (!$request->filled("username")) {
            return $this->error("Missing username");
        }

        if (!$request->filled("password")) {
            return $this->error("Missing password");
        }

        $username = $request->get("username");
        $password = $request->get("password");

        $userdata = [
            "username" => $username,
            "password" => $password,
            'status' => 1
        ];

        if (Auth::once($userdata)) {

            $user = User::where("username", $username)->first();

            if ($username != "guest") {
                // update user API token
                $user->api_token = $user->newApiToken();
                $user->save();
            }

            $user->load("photo");

            return $this->response($user);

        }

        return $this->error("Invalid username or password");


    }

    /**
     * Revoke an API access token
     * @param string $api_token (required) The access token.
     * @return \Illuminate\Http\JsonResponse
     */
    function revoke(Request $request)
    {

        if (!$request->filled("api_token")) {
            $this->response("Missing API token");
        }

        $user = User::where("api_token", $request->api_token)->first();

        if (count($user)) {

            // Remove user API token
            $user->api_token = NULL;
            $user->save();

            return $this->response($user);

        }

        return $this->error("Invalid API token");


    }


}
