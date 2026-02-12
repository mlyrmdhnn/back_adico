<?php

namespace App\Providers;

use App\Models\DataCustomer;
use App\Models\User;
use App\Policies\CustomerPolicy;
use App\Policies\DeveloperPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if($user->role == 'developer') {
                return true;
            }
        });
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(DataCustomer::class, CustomerPolicy::class);
    }
}
