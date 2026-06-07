@extends('layouts.app')

@section('title', 'Recuperar Senha — SyncMatch')

@section('content')
<div style="max-width: 400px; margin: 4rem auto; background: var(--glass-bg); padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--glass-border);">
    <h1 style="text-align: center; margin-bottom: 1rem; font-family: var(--font-display);">Recuperar Senha</h1>
    <p style="text-align: center; color: var(--clr-text-muted); margin-bottom: 2rem; font-size: 0.9rem;">
        Esqueceu sua senha? Sem problemas. Informe seu e-mail e enviaremos um link de recuperação.
    </p>

    @if (session('success'))
        <div style="background: rgba(16, 185, 129, 0.1); color: #34d399; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; font-size: 0.875rem;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="email">E-mail</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div style="color: #fca5a5; font-size: 0.75rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
            Enviar Link de Recuperação
        </button>
    </form>
    
    <div style="text-align: center; margin-top: 1.5rem;">
        <a href="{{ route('login') }}" style="color: var(--clr-primary-400); text-decoration: none; font-size: 0.875rem; font-weight: 600;">
            <i class="fa-solid fa-arrow-left"></i> Voltar para o login
        </a>
    </div>
</div>
@endsection
