<?php

namespace App\Patterns\Factory;

use InvalidArgumentException;

/**
 * Factory — Padrão Factory Method.
 *
 * Centraliza a criação de ProfileCreators sem que o cliente precise
 * conhecer as classes concretas. Ao adicionar um novo tipo de usuário,
 * basta adicionar um novo case neste factory e criar o Creator correspondente.
 */
class UserProfileFactory
{
    /**
     * Retorna o ProfileCreator adequado ao tipo (role) de usuário solicitado.
     *
     * @param  string  $role  Tipo do usuário: 'student', 'recruiter_existing',
     *                         'recruiter_new', 'admin'
     *
     * @throws InvalidArgumentException  Caso o role não seja reconhecido
     */
    public static function make(string $role): ProfileCreatorInterface
    {
        return match ($role) {
            'student'             => new StudentProfileCreator(),
            'recruiter_existing'  => new RecruiterProfileCreator(),
            'recruiter_new'       => new CompanyOwnerProfileCreator(),
            'admin'               => new StudentProfileCreator(), // Admins não têm perfil extra
            default               => throw new InvalidArgumentException(
                "Tipo de usuário desconhecido: [{$role}]"
            ),
        };
    }
}
