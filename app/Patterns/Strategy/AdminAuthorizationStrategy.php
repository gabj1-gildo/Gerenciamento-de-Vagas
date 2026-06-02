<?php

namespace App\Patterns\Strategy;

use App\Models\Job;

/**
 * Concrete Strategy — Padrão Strategy.
 * Estratégia de autorização para Administradores do sistema.
 * Administradores têm acesso irrestrito a qualquer vaga.
 */
class AdminAuthorizationStrategy implements AuthorizationStrategyInterface
{
    public function isAuthorized(int $userId, Job $job): bool
    {
        return session('user_role') === 'admin';
    }
}
