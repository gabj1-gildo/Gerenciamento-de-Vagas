<?php

namespace App\Services;

use App\Models\User;
use App\Patterns\Factory\UserProfileFactory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

/**
 * Service Layer — Padrão Facade.
 *
 * Encapsula toda a lógica de negócio do processo de registro de usuários,
 * separando-a do Controller (que deve apenas delegar, não processar).
 *
 * Responsabilidades:
 *   1. Criar o registro do usuário na tabela 'users'
 *   2. Delegar a criação do perfil específico via UserProfileFactory (Factory Method)
 *   3. Mapear o role do formulário para o role do banco de dados
 */
class UserRegistrationService
{
    public function __construct(
        private readonly \App\Services\Notification\NotificationStrategy $notificationStrategy
    ) {}

    /**
     * Registra um novo usuário e cria seu perfil via Factory Method.
     *
     * @param  array  $data  Dados validados do formulário de cadastro
     * @return User          O usuário recém-criado
     */
    public function register(array $data): User
    {
        // Mapear roles do formulário para os roles do banco
        $formRole = $data['role'];
        $dbRole   = match ($formRole) {
            'recruiter_existing', 'recruiter_new' => 'recruiter',
            default                               => $formRole,
        };

        $token = Str::random(64);

        // 1. Criar o usuário base
        $user = User::create([
            'name'               => $data['nome'],
            'email'              => $data['email'],
            'password'           => bcrypt($data['senha']),
            'role'               => $dbRole,
            'birth_date'         => $data['birth_date'],
            'gender'             => $data['gender'],
            'social_name'        => $data['gender'] === 'outro' ? ($data['social_name'] ?? null) : null,
            'verification_token' => $token,
            'created_at'         => now(),
        ]);

        // 2. Delegar criação do perfil à Factory (Factory Method)
        $creator = UserProfileFactory::make($formRole);
        $creator->createProfile($user, $data);

        // 3. Enviar link de verificação de e-mail usando a Strategy
        $this->notificationStrategy->sendVerificationLink($user, $token);

        return $user;
    }
}
