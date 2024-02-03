<?php

namespace Benaaacademy\Auth\Providers;

use Benaaacademy\Platform\Facades\Plugin;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Support\ServiceProvider;

/**
 * Class AuthServiceProvider
 * @package Benaaacademy\Auth\Providers
 */
class AuthServiceProvider extends ServiceProvider
{

    /**
     * Registering all permission to laravel
     * authorization gate
     * @param GateContract $gate
     */
    function boot(GateContract $gate)
    {

        foreach (Plugin::all() as $plugin) {
            foreach ($plugin->getPermissions() as $permission) {
                $gate->define($plugin->getKey() . "." . $permission, function ($user) use ($plugin, $permission) {
                    return $user->hasRole("superadmin") || $user->hasAccess([$plugin->getKey() . "." . $permission]);
                });
            }
        }

    }

}
