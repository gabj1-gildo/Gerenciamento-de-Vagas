<?php

namespace App\Patterns\Factory;

use App\Models\User;

/**
 * Concrete Creator — Padrão Factory Method.
 * Responsável por criar o perfil de candidato (student).
 * Candidatos não possuem uma tabela de perfil adicional por padrão;
 * o perfil de currículo (CandidateProfile) é criado separadamente via ProfileController.
 */
class StudentProfileCreator implements ProfileCreatorInterface
{
    /**
     * Para candidatos, nenhum perfil extra é necessário no cadastro.
     * O CandidateProfile é criado sob demanda quando o usuário
     * preenche o currículo na tela de perfil.
     */
    public function createProfile(User $user, array $data): void
    {
        // Candidatos não requerem perfil adicional no ato do cadastro.
        // O CandidateProfile é gerado via ProfileController (lazy creation).
    }
}
