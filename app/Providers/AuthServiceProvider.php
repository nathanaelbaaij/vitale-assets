<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('administrator', function ($user) {
            return $user->hasRole('administrator');
        });

        Gate::define('editor', function ($user) {
            return $user->hasRole('editor');
        });

        Gate::define('member', function ($user) {
            return $user->hasRole('member');
        });
    }
}
