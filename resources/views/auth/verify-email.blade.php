@extends('layouts.app')

@section('title', 'Verifique seu e-mail — SyncMatch')

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 60vh; padding: 1.5rem 0;">
    <div class="card card-glass animate-fadeInUp" style="max-width: 500px; width: 100%; text-align: center; padding: 3rem 2rem; border-radius: 20px; box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);">
        
        <div style="margin-bottom: 2rem;">
            <div style="width: 80px; height: 80px; background: rgba(244, 63, 94, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fa-solid fa-envelope-open-text" style="font-size: 2.5rem; color: var(--clr-primary-400);"></i>
            </div>
            <h1 style="font-family: var(--font-display); font-size: 2rem; font-weight: 800; color: var(--clr-text); margin: 0 0 0.5rem; letter-spacing: -0.5px;">
                Verifique seu e-mail
            </h1>
            <p style="color: var(--clr-text-muted); font-size: 0.9375rem; margin: 0;">
                Quase lá! Precisamos confirmar a identidade da sua conta.
            </p>
        </div>

        <div style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--glass-border); padding: 1.25rem; border-radius: 12px; margin-bottom: 2rem; text-align: left;">
            <p style="color: var(--clr-text-dim); font-size: 0.9rem; line-height: 1.6; margin: 0;">
                Enviamos um link de confirmação para o endereço:<br>
                <strong style="color: var(--clr-text); font-size: 0.95rem;">{{ $user->email }}</strong>
            </p>
            <p style="color: var(--clr-text-dim); font-size: 0.85rem; line-height: 1.5; margin: 0.75rem 0 0;">
                Por favor, verifique sua caixa de entrada e a pasta de spam. Você precisa clicar no link contido no e-mail para ativar o acesso ao SyncMatch.
            </p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center;">
            <form action="{{ route('verification.resend') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-resend-verification" style="box-shadow: 0 8px 24px rgba(244, 63, 94, 0.35);">
                    <i class="fa-solid fa-paper-plane" style="margin-right: 0.5rem;"></i>
                    Reenviar E-mail de Confirmação
                </button>
            </form>

            <div style="display: flex; gap: 1.5rem; margin-top: 1rem; font-size: 0.875rem;">
                <a href="{{ route('logout') }}" style="color: var(--clr-text-dim); text-decoration: none; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--clr-text)'" onmouseout="this.style.color='var(--clr-text-dim)'">
                    <i class="fa-solid fa-arrow-right-from-bracket" style="margin-right: 0.25rem;"></i>
                    Sair da conta
                </a>
            </div>
        </div>
        
    </div>
</div>
@endsection
