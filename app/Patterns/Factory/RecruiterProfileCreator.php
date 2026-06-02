<?php

namespace App\Patterns\Factory;

use App\Models\User;
use App\Models\RecruiterProfile;

/**
 * Concrete Creator — Padrão Factory Method.
 * Responsável por criar o perfil de recrutador vinculado a uma empresa existente.
 * O recrutador inicia com aprovação pendente (approved = false).
 */
class RecruiterProfileCreator implements ProfileCreatorInterface
{
    /**
     * Cria um RecruiterProfile com aprovação pendente.
     * O recrutador só terá acesso após ser aprovado pelo dono da empresa ou admin.
     *
     * @param  User   $user  Usuário criado
     * @param  array  $data  Deve conter 'company_id'
     */
    public function createProfile(User $user, array $data): void
    {
        RecruiterProfile::create([
            'user_id'    => $user->id,
            'company_id' => $data['company_id'],
            'approved'   => false,
        ]);
    }
}
