<?php

namespace App\Services;

use App\Events\ApplicationApproved;
use App\Models\Application;
use App\Models\Job;
use App\Patterns\Strategy\AdminAuthorizationStrategy;
use App\Patterns\Strategy\ApprovedRecruiterAuthorizationStrategy;
use App\Patterns\Strategy\CompanyOwnerAuthorizationStrategy;

/**
 * Service Layer — Padrão Facade.
 *
 * Encapsula toda a lógica de negócio relacionada a candidaturas.
 *
 * Responsabilidades:
 *   1. Verificar autorização usando múltiplas Strategies (Strategy Pattern)
 *   2. Atualizar status de candidaturas
 *   3. Disparar o evento ApplicationApproved (Observer Pattern)
 *      que por sua vez aciona o CloseJobOnApplicationApproval
 */
class ApplicationService
{
    /**
     * Verifica se o usuário tem autorização para operar sobre uma vaga,
     * aplicando as Strategies em ordem até que uma retorne true.
     *
     * @param  int  $userId  ID do usuário da sessão
     * @param  Job  $job     Vaga sendo acessada
     */
    public function isAuthorized(int $userId, Job $job): bool
    {
        $strategies = [
            new AdminAuthorizationStrategy(),
            new CompanyOwnerAuthorizationStrategy(),
            new ApprovedRecruiterAuthorizationStrategy(),
        ];

        foreach ($strategies as $strategy) {
            if ($strategy->isAuthorized($userId, $job)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Atualiza o status de uma candidatura e dispara eventos quando necessário.
     *
     * Ao aprovar (status = 'aprovado'), dispara ApplicationApproved que
     * aciona o Listener CloseJobOnApplicationApproval (Observer Pattern).
     *
     * @param  Application  $application  Candidatura a ser atualizada
     * @param  string       $newStatus    Novo status
     */
    public function updateStatus(Application $application, string $newStatus): void
    {
        $application->update(['status' => $newStatus]);

        // Observer: dispara evento para que os Listeners reajam de forma desacoplada
        if ($newStatus === 'aprovado') {
            ApplicationApproved::dispatch($application);
        }
    }
}
