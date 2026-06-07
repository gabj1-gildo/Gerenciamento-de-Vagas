<?php

namespace App\Patterns\State;

use App\Models\Application;

class RejeitadoState implements ApplicationState
{
    public function handle(Application $application): void
    {
        $application->update(['status' => 'rejeitado']);
    }
}
