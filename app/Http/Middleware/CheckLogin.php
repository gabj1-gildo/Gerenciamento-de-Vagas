<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!session('user_id')){
            return redirect('/login');
        }

        // Se o usuário estiver logado mas o e-mail não estiver verificado, redireciona para a tela de aviso
        $user = \App\Models\User::find(session('user_id'));
        if ($user && !$user->email_verified_at) {
            // Evitar loop de redirecionamento para as próprias rotas de verificação e logout
            if (!$request->is('email/verify') && !$request->is('email/resend') && !$request->is('logout')) {
                return redirect()->route('verification.notice');
            }
        }

        return $next($request);
    }
}
