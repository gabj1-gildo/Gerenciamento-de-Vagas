<?php

namespace App\Events;

use App\Models\Application;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento — Padrão Observer.
 *
 * Disparado quando uma candidatura é marcada como "aprovada" pelo recrutador.
 * Os Listeners registrados reagem a este evento de forma desacoplada,
 * sem que o código de aprovação precise conhecer as consequências.
 *
 * Consequência atual: CloseJobOnApplicationApproval fecha a vaga automaticamente.
 */
class ApplicationApproved
{
    use Dispatchable, SerializesModels;

    /**
     * @param  Application  $application  A candidatura que foi aprovada
     */
    public function __construct(public readonly Application $application)
    {
        //
    }
}
