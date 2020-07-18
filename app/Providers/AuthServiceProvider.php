<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     * Do not use < less than, or > greater than
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // allows, denies, any, none
        // \Gate::any(['premium-user', 'penman-user', 'pro-user'])

        // account-holder
        // pro user, access to prelim and prelim plus
        Gate::define('free-user', function ($user) {
            return $user->subscription == 5;
        });

        // premium user, access to prelim
        Gate::define('premium-user', function ($user) {
            return $user->subscription == 10;
        });

        // penman user, access to mains
        Gate::define('penman-user', function ($user) {
            return $user->subscription == 15;
        });

        // full-user
        // pro user, access to prelim and prelim plus
        Gate::define('pro-user', function ($user) {
            return $user->subscription == 20;
        });

        // assembler user, access to prelim and mains only
        Gate::define('assembler-user', function ($user) {
            return $user->subscription == 25;
        });

        // omnipotent user, access to prelim plus and mains
        Gate::define('omnipotent-user', function ($user) {
            return $user->subscription == 30;
        });
    }
}
