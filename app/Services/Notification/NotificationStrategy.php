<?php

namespace App\Services\Notification;

use App\Models\User;

/**
 * Strategy Interface — Padrão GoF Strategy.
 *
 * Abstrai a forma de envio de notificações no sistema, permitindo trocar o mecanismo
 * (Mail com SMTP, Resend SDK, fila, SMS, etc.) sem alterar as camadas de serviço de negócio.
 */
interface NotificationStrategy
{
    /**
     * Envia o link de verificação de e-mail para o usuário.
     *
     * @param  User    $user   Usuário destinatário
     * @param  string  $token  Token de verificação gerado
     * @return void
     */
    public function sendVerificationLink(User $user, string $token): void;
}
