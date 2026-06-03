<?php

namespace App\Patterns\Strategy;

use App\Models\Job;
use App\Models\RecruiterProfile;

/**
 * Concrete Strategy — Padrão Strategy.
 * Estratégia de autorização para Recrutadores aprovados.
 * O recrutador deve estar vinculado à mesma empresa da vaga E estar aprovado.
 */
class ApprovedRecruiterAuthorizationStrategy implements AuthorizationStrategyInterface
{
    public function isAuthorized(int $userId, Job $job): bool
    {
        $profile = RecruiterProfile::where('user_id', $userId)->first();

        if (!$profile) {
            return false;
        }

        // Verifica se o recrutador está associado à empresa da vaga e se foi aprovado nela
        return $profile->companies()
                       ->where('companies.id', $job->company_id)
                       ->wherePivot('approved', true)
                       ->exists();
    }
}
