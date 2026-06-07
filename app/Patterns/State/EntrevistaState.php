<?php

namespace App\Patterns\State;

use App\Models\Application;

class EntrevistaState implements ApplicationState
{
    public function handle(Application $application): void
    {
        $application->update(['status' => 'entrevista']);
    }
}
