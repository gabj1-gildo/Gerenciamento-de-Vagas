<?php

namespace App\Patterns\Factory;

use App\Models\User;
use App\Models\Companie;
use App\Models\RecruiterProfile;

/**
 * Concrete Creator — Padrão Factory Method.
 * Responsável por criar uma nova Empresa e vincular o usuário como seu dono.
 * O dono da empresa é automaticamente um recrutador aprovado (approved = true).
 */
class CompanyOwnerProfileCreator implements ProfileCreatorInterface
{
    /**
     * Cria a empresa jurídica e o perfil de recrutador-dono, já aprovado.
     *
     * @param  User   $user  Usuário criado
     * @param  array  $data  Deve conter 'company_name', 'company_cnpj', 'company_area',
     *                        'company_city', 'company_description'
     */
    public function createProfile(User $user, array $data): void
    {
        // 1. Criar a entidade jurídica (Empresa)
        $company = Companie::create([
            'name'        => $data['company_name'],
            'cnpj'        => $data['company_cnpj'],
            'area'        => $data['company_area'] ?? null,
            'city'        => $data['company_city'],
            'description' => $data['company_description'] ?? null,
            'user_id'     => $user->id,
        ]);

        // 2. Criar perfil de recrutador-dono já aprovado
        $profile = RecruiterProfile::create([
            'user_id'    => $user->id,
        ]);
        $profile->companies()->attach($company->id, ['approved' => true]);
    }
}
