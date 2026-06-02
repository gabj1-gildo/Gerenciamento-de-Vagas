<?php

namespace App\Patterns\Strategy;

use App\Models\Job;

/**
 * Interface do padrão Strategy.
 * Define o contrato para as diferentes estratégias de verificação
 * de autorização de acesso a dados de candidaturas e vagas.
 */
interface AuthorizationStrategyInterface
{
    /**
     * Verifica se o usuário tem permissão para interagir com a vaga informada.
     *
     * @param  int  $userId  ID do usuário autenticado (via sessão)
     * @param  Job  $job     Vaga sendo acessada
     */
    public function isAuthorized(int $userId, Job $job): bool;
}
