<?php

namespace Benaaacademy\Auth\Controllers;

use Action;
use Config;
use Benaaacademy\Platform\Controller;
use Benaaacademy\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Request;
use Session;
use Validator;

/**
 * Class AuthController
 * @package Benaaacademy\Auth\Controllers
 */
class AuthController extends Controller
{

    /**
     * View payload
     * @var array
     */
    public $data = [];

    /**
     * Login to the backend using username/password combination
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login()
    {

        if (Request::isMethod("post")) {

            $rules = array(
                'username' => 'required',
                'password' => 'required'
            );

            $validator = Validator::make(Request::all(), $rules);

            if ($validator->fails()) {

                return Redirect::back()->withErrors($validator)->withInput(Request::except('password'));

            } else {

                $userdata = array(
                    'username' => Request::get('username'),
                    'password' => Request::get('password'),
                    'backend' => 1,
                    'status' => 1
                );

                if (Auth::attempt($userdata, Request::get("remember"))) {

                    $user_lang = Auth::user()->lang;

                    if (in_array($user_lang, array_keys(config("i18n.locales", [])))) {
                        Session::put('locale', $user_lang);
                    }

                    // Fire login action

                    Action::fire("auth.login", Auth::user());

                    // Redirection

                    if (!is_null(Request::get("url"))) {
                        return redirect(Request::get("url"));
                    } else {
                        return redirect(admin_url(config("admin.default_path")));
                    }

                } else {

                    return redirect()->route('admin.auth.login')
                        ->withErrors(array("message" => trans("auth::auth.invalid_login")))
                        ->withInput(Request::except('password'));
                }
            }
        }

        return view("auth::login");
    }

    /**
     * Logout the current user
     * @return mixed
     */
    public function logout()
    {

        $user = Auth::user();

        Auth::logout();

        // Fire logout action

        Action::fire("auth.logout", $user);

        return Redirect::route("admin.auth.login");
    }


    /**
     * Forget password request
     * @return mixed
     */
    public function forget()
    {

        if (Request::isMethod("post")) {

            $rules = array(
                'email' => 'required|email'
            );

            $validator = Validator::make(Request::all(), $rules);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput(Request::all());
            } else {

                $email = Request::get("email");

                // Send activation link to user
                // Check user is already exists

                $user = User::where("email", $email)->first();

                if (count($user)) {

                    $code = Str::random(30);

                    $link = URL::route("admin.auth.reset") . "/" . $code;

                    $headers = 'From: "' . Config::get("site_name") . '" <' . Config::get("site_email") . '>';

                    $content = trans("auth::auth.hi") . " " . $user->first_name . ", \r\n" . trans("auth::auth.check_password_link") . "\r\n" . $link;

                    mail($user->email, trans("auth::auth.reset_password"), $content, $headers);

                    User::where("email", $email)
                        ->update(array(
                            "code" => $code
                        ));

                    // fire Forget action
                    Action::fire("auth.forget", $user);

                    return Redirect::back()
                        ->withErrors(array("email_sent" => trans("auth::auth.password_reset_link_sent")))
                        ->withInput(Request::all());
                } else {
                    return Redirect::back()
                        ->withErrors(array("not_registed" => trans("auth::auth.email_not_found")))
                        ->withInput(Request::all());
                }
            }
        }

        return view("auth::forget");
    }

    /**
     * Reset password request
     * @param bool $code
     * @param bool $reseted
     * @return string
     */
    public function reset($code = false, $reseted = false)
    {

        $this->data["reseted"] = $reseted;

        if ($reseted) {
            return view("auth::reset", $this->data);
        }

        if (Request::filled("code")) {
            $code = Request::get("code");
        }

        $this->data["code"] = $code;

        $user = User::where("code", $code)->first();

        if (count($user) == 0) {
            return "Forbidden";
        }

        if (Request::isMethod("post")) {

            $rules = array(
                'password' => 'required|min:7',
                'repassword' => 'required|same:password',
            );

            $validator = Validator::make(Request::all(), $rules, [
                'password.has' => 'كلمة المرور يجب أن تحتوى على حروف كبيرة وحروف صغيرة وأرقام وحروف خاصة'
            ]);

            if ($validator->fails()) {
                return Redirect::route("admin.auth.reset", array("code" => $code))
                    ->withErrors($validator)
                    ->withInput(Request::all());
            } else {

                // Reset user password

                User::where("id", "=", $user->id)->update([
                    "updated_at" => date("Y-m-d H:i:s"),
                    "code" => "",
                    "password" => Hash::make(Request::get("password"))
                ]);

                // Fire reset password action

                Action::fire("auth.reset", $user);

                return Redirect::to(ADMIN . "/auth/reset/" . $code . "/1");
            }
        }

        return view("auth::reset", $this->data);
    }
}
