<?php

namespace App\Patterns\Factory;

use App\Models\User;

/**
 * Interface do padrão Factory Method.
 * Define o contrato para a criação de perfis de usuário.
 * Cada tipo de usuário (Student, Recruiter, CompanyOwner) possui
 * sua própria implementação desta interface.
 */
interface ProfileCreatorInterface
{
    /**
     * Cria o perfil associado ao usuário recém-criado.
     *
     * @param  User   $user  O usuário recém-persistido
     * @param  array  $data  Dados adicionais do formulário de cadastro
     */
    public function createProfile(User $user, array $data): void;
}
