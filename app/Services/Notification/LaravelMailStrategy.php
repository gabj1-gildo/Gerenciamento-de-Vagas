<?php

namespace App\Services\Notification;

use App\Models\User;
use App\Mail\VerifyEmailMail;
use Illuminate\Support\Facades\Mail;

/**
 * Concrete Strategy — Padrão GoF Strategy.
 *
 * Implementa a estratégia de notificação por E-mail usando o sistema nativo
 * de Mail do Laravel (que pode ser configurado para SMTP, Resend, etc.).
 */
class LaravelMailStrategy implements NotificationStrategy
{
    /**
     * Envia o e-mail de verificação de conta.
     */
    public function sendVerificationLink(User $user, string $token): void
    {
        Mail::to($user->email)->send(new VerifyEmailMail($user, $token));
    }
}
