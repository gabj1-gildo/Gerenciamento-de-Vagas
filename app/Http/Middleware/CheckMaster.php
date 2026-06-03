<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckMaster — Protege rotas exclusivas do Super Admin (Master).
 * Usuários sem o role 'master' recebem HTTP 403.
 */
class CheckMaster
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session('user_id') || session('user_role') !== 'master') {
            abort(403, 'Acesso restrito ao Super Admin.');
        }

        return $next($request);
    }
}
