<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Faça login no SyncMatch e acesse as melhores vagas de emprego.">
    <title>Entrar — SyncMatch</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <style>
        body {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 100vh;
            background: var(--clr-bg);
        }

        /* ── Painel Esquerdo (Hero) ── */
        .login-hero {
            background: var(--gradient-hero);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        .login-hero::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%);
            top: -100px; left: -100px;
        }

        .login-hero::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(236,72,153,0.15) 0%, transparent 70%);
            bottom: -50px; right: -50px;
        }

        .login-hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 400px;
        }

        .login-logo {
            font-family: var(--font-display);
            font-size: 3rem;
            font-weight: 900;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            letter-spacing: -1px;
        }

        .login-tagline {
            font-size: 1.25rem;
            color: rgba(255,255,255,0.8);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .login-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .login-features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255,255,255,0.7);
            font-size: 0.9375rem;
        }

        .login-features li i {
            color: var(--clr-primary-400);
            font-size: 1rem;
        }

        /* ── Painel Direito (Form) ── */
        .login-panel {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
        }

        .login-form-card {
            width: 100%;
            max-width: 420px;
        }

        .login-form-title {
            font-family: var(--font-display);
            font-size: 1.875rem;
            font-weight: 800;
            color: var(--clr-text);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .login-form-subtitle {
            color: var(--clr-text-muted);
            font-size: 0.9375rem;
            margin-bottom: 2rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--clr-text-dim);
            font-size: 0.9rem;
            pointer-events: none;
        }

        .input-wrapper .form-control {
            padding-left: 42px;
        }

        .input-wrapper .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--clr-text-dim);
            cursor: pointer;
            font-size: 0.9rem;
            transition: var(--transition-fast);
            border: none;
            background: none;
        }

        .input-wrapper .toggle-password:hover { color: var(--clr-primary-400); }

        .login-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .login-divider::before, .login-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--glass-border);
        }

        .login-divider span {
            font-size: 0.75rem;
            color: var(--clr-text-dim);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .login-hero { display: none; }
            .login-panel { padding: 2rem 1rem; }
        }
    </style>
</head>
<body>

{{-- Painel Esquerdo --}}
<div class="login-hero">
    <div class="login-hero-content">
        <div class="login-logo">SyncMatch</div>
        <p class="login-tagline">Conectamos os melhores talentos às oportunidades que transformam carreiras.</p>
        <ul class="login-features">
            <li><i class="fa-solid fa-circle-check"></i> Vagas verificadas de empresas reais</li>
            <li><i class="fa-solid fa-circle-check"></i> Candidatura com um clique</li>
            <li><i class="fa-solid fa-circle-check"></i> Acompanhamento em tempo real</li>
            <li><i class="fa-solid fa-circle-check"></i> Gestão completa de recrutamento</li>
        </ul>
    </div>
</div>

{{-- Painel Direito --}}
<div class="login-panel">
    <div class="login-form-card animate-fadeInUp">
        <h1 class="login-form-title">Bem-vindo de volta</h1>
        <p class="login-form-subtitle">Entre na sua conta para continuar</p>

        @if($errors->has('loginError'))
            <div class="alert alert-danger" id="alert-login-error">
                <i class="fa-solid fa-triangle-exclamation"></i>
                {{ $errors->first('loginError') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success" id="alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('loginSubmit') }}" method="POST" id="login-form">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">E-mail</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="seu@email.com"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >
                </div>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label class="form-label" for="senha" style="margin-bottom: 0;">Senha</label>
                    <a href="{{ route('password.request') }}" style="font-size: 0.75rem; color: var(--clr-primary-400); text-decoration: none; font-weight: 600;">Esqueceu a senha?</a>
                </div>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input
                        type="password"
                        class="form-control"
                        id="senha"
                        name="senha"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="toggle-password" id="toggle-senha" aria-label="Mostrar senha">
                        <i class="fa-regular fa-eye" id="toggle-senha-icon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg mt-lg" id="btn-submit-login">
                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                Entrar
            </button>
        </form>

        <div class="login-divider"><span>ou</span></div>

        <p class="text-center text-sm text-muted">
            Ainda não tem uma conta?
            <a href="{{ route('novo_usuario') }}" id="link-cadastrar" style="font-weight: 600;">Criar conta grátis</a>
        </p>
    </div>
</div>

<script>
    // Toggle senha visível/oculta
    document.getElementById('toggle-senha').addEventListener('click', function() {
        const input = document.getElementById('senha');
        const icon  = document.getElementById('toggle-senha-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
</script>
</body>
</html>
