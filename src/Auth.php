<?php

namespace Benaaacademy\Auth;

class Auth extends \Benaaacademy\Platform\Plugin
{

    protected $dependencies = [
        "users" => \Benaaacademy\Users\Users::class
    ];

    protected $providers = [
        \Benaaacademy\Auth\Providers\AuthServiceProvider::class
    ];

    protected $route_middlewares = [
        'auth' => \Benaaacademy\Auth\Middlewares\AuthMiddleware::class,
        'guest' => \Benaaacademy\Auth\Middlewares\GuestMiddleware::class,
    ];

    function install($command)
    {
        $command->call("vendor:publish", [
            "--tag" => [$this->getKey() . ".config"],
            "--force" => true
        ]);

    }


}
