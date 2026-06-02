<?php

namespace App\Patterns\Strategy;

use App\Models\Job;

/**
 * Concrete Strategy — Padrão Strategy.
 * Estratégia de autorização para o Dono da Empresa.
 * O usuário criador da empresa tem acesso total às vagas da empresa.
 */
class CompanyOwnerAuthorizationStrategy implements AuthorizationStrategyInterface
{
    public function isAuthorized(int $userId, Job $job): bool
    {
        // O dono da empresa é o user_id registrado na company
        return $job->company && $job->company->user_id == $userId;
    }
}
