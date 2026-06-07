<?php

namespace App\Patterns\State;

use App\Models\Application;

interface ApplicationState
{
    /**
     * Lida com a transição para este estado.
     * Pode incluir validações, disparo de eventos ou envio de e-mails.
     */
    public function handle(Application $application): void;
}
