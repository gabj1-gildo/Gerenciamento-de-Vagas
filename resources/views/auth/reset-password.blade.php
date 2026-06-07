@extends('layouts.app')

@section('title', 'Redefinir Senha — SyncMatch')

@section('content')
<div style="max-width: 400px; margin: 4rem auto; background: var(--glass-bg); padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--glass-border);">
    <h1 style="text-align: center; margin-bottom: 1rem; font-family: var(--font-display);">Redefinir Senha</h1>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
            <label class="form-label" for="email">E-mail</label>
            <input id="email" type="email" class="form-control" name="email" value="{{ request()->email ?? old('email') }}" required autofocus>
            @error('email')
                <div style="color: #fca5a5; font-size: 0.75rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Nova Senha</label>
            <input id="password" type="password" class="form-control" name="password" required>
            @error('password')
                <div style="color: #fca5a5; font-size: 0.75rem; margin-top: 5px;">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirmar Nova Senha</label>
            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
            Redefinir Senha
        </button>
    </form>
</div>
@endsection
