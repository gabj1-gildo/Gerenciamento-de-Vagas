<?php

namespace App\Listeners;

use App\Events\ApplicationApproved;

/**
 * Listener — Padrão Observer.
 *
 * Reage ao evento ApplicationApproved fechando automaticamente a vaga
 * que recebeu a candidatura aprovada. Este Listener é completamente
 * desacoplado da lógica de aprovação — ele apenas "observa" o evento.
 *
 * Para adicionar novas reações (ex: enviar e-mail ao candidato),
 * basta criar um novo Listener sem modificar o código existente.
 */
class CloseJobOnApplicationApproval
{
    /**
     * Ao receber o evento, fecha a vaga associada à candidatura.
     */
    public function handle(ApplicationApproved $event): void
    {
        $job = $event->application->job;

        if ($job && $job->status !== 'fechada') {
            $job->update(['status' => 'fechada']);
        }
    }
}
