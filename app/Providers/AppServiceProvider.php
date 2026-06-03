<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

// Padrão Observer: imports do evento e seu listener
use App\Events\ApplicationApproved;
use App\Listeners\CloseJobOnApplicationApproval;

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
     *
     * Registra o binding Observer: ApplicationApproved → CloseJobOnApplicationApproval
     * Ao aprovar uma candidatura, o evento é disparado e o Listener fecha a vaga
     * automaticamente, de forma desacoplada (Padrão Observer / GoF).
     */
    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Event::listen(
            ApplicationApproved::class,
            CloseJobOnApplicationApproval::class,
        );
    }
}
