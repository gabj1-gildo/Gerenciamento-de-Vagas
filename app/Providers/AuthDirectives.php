<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class AuthDirectives
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Blade::if('guest', function () {
            return Auth::guest();
        });
    }
}