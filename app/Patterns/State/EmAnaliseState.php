<?php

namespace App\Patterns\State;

use App\Models\Application;

class EmAnaliseState implements ApplicationState
{
    public function handle(Application $application): void
    {
        $application->update(['status' => 'em_analise']);
    }
}
