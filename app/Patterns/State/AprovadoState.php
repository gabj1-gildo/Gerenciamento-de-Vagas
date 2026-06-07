<?php

namespace App\Patterns\State;

use App\Models\Application;
use App\Events\ApplicationApproved;

class AprovadoState implements ApplicationState
{
    public function handle(Application $application): void
    {
        $application->update(['status' => 'aprovado']);
        
        // Observer: dispara evento para fechar a vaga
        ApplicationApproved::dispatch($application);
    }
}
