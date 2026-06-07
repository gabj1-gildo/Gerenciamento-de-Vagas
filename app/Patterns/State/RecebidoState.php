<?php

namespace App\Patterns\State;

use App\Models\Application;

class RecebidoState implements ApplicationState
{
    public function handle(Application $application): void
    {
        $application->update(['status' => 'recebido']);
        // Email de confirmação de recebimento poderia ser enviado aqui
    }
}
