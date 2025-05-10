<?php

namespace App\Providers;

use App\Models\FriendRequest;
use App\Observers\FriendRequesObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

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
       FriendRequest::observe(FriendRequesObserver::class);
    }
}
