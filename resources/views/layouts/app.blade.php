<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SyncMatch — Conectando talentos às melhores oportunidades de carreira.">
    <title>@yield('title', 'SyncMatch — Gerenciamento de Vagas')</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">

    {{-- Tipografia (Google Fonts) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Ícones (Font Awesome 6) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Design System SyncMatch --}}
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    @yield('head')

    <style>
        /* ─── Toast Notifications ──────────────────────────── */
        #toast-container {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            pointer-events: none;
        }

        .toast {
            min-width: 280px;
            max-width: 420px;
            padding: 0.9rem 1.25rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            backdrop-filter: blur(16px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.35);
            pointer-events: all;
            animation: toastIn 0.35s cubic-bezier(.21,1.02,.73,1) both;
            position: relative;
            overflow: hidden;
        }

        .toast.toast-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.35);
            color: #6ee7b7;
        }

        .toast.toast-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        .toast-icon { font-size: 1rem; flex-shrink: 0; margin-top: 1px; }
        .toast-body { flex: 1; color: var(--clr-text); line-height: 1.45; }
        .toast-body strong { display: block; margin-bottom: 2px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }

        .toast-close {
            background: none;
            border: none;
            color: var(--clr-text-dim);
            cursor: pointer;
            font-size: 0.9rem;
            padding: 0;
            flex-shrink: 0;
            transition: color 0.15s;
        }
        .toast-close:hover { color: var(--clr-text); }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 12px 12px;
            animation: toastProgress 4s linear forwards;
        }
        .toast-success .toast-progress { background: #10b981; }
        .toast-error   .toast-progress { background: #ef4444; }

        @keyframes toastIn {
            from { opacity: 0; transform: translateX(60px) scale(0.95); }
            to   { opacity: 1; transform: translateX(0)   scale(1); }
        }
        @keyframes toastProgress {
            from { width: 100%; }
            to   { width: 0%; }
        }
        .toast.toast-out {
            animation: toastOut 0.3s ease forwards;
        }
        @keyframes toastOut {
            to { opacity: 0; transform: translateX(60px); max-height: 0; padding: 0; margin: 0; }
        }
    </style>
</head>
<body>

{{-- ─── Toast Container + Funções (definidas cedo para garantir disponibilidade) --}}
<div id="toast-container"></div>
<script>
function showToast(type, message) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast toast-' + type;
    const icon  = type === 'success' ? '\u2714' : '\u26a0\ufe0f';
    const label = type === 'success' ? 'Sucesso' : 'Aten\u00e7\u00e3o';
    toast.innerHTML =
        '<span class="toast-icon">' + icon + '</span>' +
        '<div class="toast-body"><strong>' + label + '</strong>' + message + '</div>' +
        '<button class="toast-close" onclick="dismissToast(this.parentElement)">&times;</button>' +
        '<div class="toast-progress"></div>';
    container.appendChild(toast);
    setTimeout(function() { dismissToast(toast); }, 4000);
}
function dismissToast(t) {
    if (!t || t.classList.contains('toast-out')) return;
    t.classList.add('toast-out');
    setTimeout(function() { if (t.parentNode) t.parentNode.removeChild(t); }, 300);
}
</script>

{{-- ─── Navbar ───────────────────────────────────────────── --}}
<nav class="navbar-syncmatch">
    <a class="navbar-brand-wrap" href="{{ url('/') }}" style="align-items: flex-start; text-decoration: none;">
        <div style="display: flex; flex-direction: column; align-items: flex-end; line-height: 1;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span class="navbar-logo-text">SyncMatch</span>
                <span class="navbar-logo-badge">Beta</span>
            </div>
            <span style="font-size: 0.55rem; color: var(--clr-text-dim); font-weight: 700; letter-spacing: 0.5px; margin-top: -2px; padding-right: 42px;">by ApexSync</span>
        </div>
    </a>

    {{-- Links de Navegação --}}
    <ul class="navbar-nav-links">
        @if(session('user_role') === 'master')
            <li><a href="{{ route('companies.index') }}" id="nav-empresas-master"><i class="fa-regular fa-building"></i> Empresas</a></li>
            <li><a href="{{ route('jobs.index') }}" id="nav-vagas-master"><i class="fa-solid fa-briefcase"></i> Vagas</a></li>
            <li><a href="{{ route('master.panel') }}" id="nav-painel-master" style="color: var(--clr-primary-400); font-weight: 700;"><i class="fa-solid fa-shield-halved"></i> Painel Master</a></li>
        @elseif(session('user_role') === 'recruiter')
            <li><a href="{{ route('companies.index') }}" id="nav-empresas"><i class="fa-regular fa-building"></i> Empresas</a></li>
            <li><a href="{{ route('jobs.index') }}" id="nav-vagas"><i class="fa-solid fa-briefcase"></i> Vagas</a></li>
            @if(session('user_approved'))
                <li><a href="{{ route('jobs.create') }}" id="nav-nova-vaga"><i class="fa-solid fa-plus"></i> Nova Vaga</a></li>
            @endif
        @elseif(session('user_role') === 'student')
            <li><a href="{{ route('jobs.index') }}" id="nav-vagas-disp"><i class="fa-solid fa-search"></i> Vagas Disponíveis</a></li>
            <li><a href="{{ route('candidate.applications') }}" id="nav-minhas-cand"><i class="fa-solid fa-file-lines"></i> Minhas Candidaturas</a></li>
            <li><a href="{{ route('profile.edit') }}" id="nav-perfil"><i class="fa-solid fa-user"></i> Meu Perfil</a></li>
        @elseif(session('user_role') === 'admin')
            <li><a href="{{ route('companies.index') }}" id="nav-empresas-admin"><i class="fa-regular fa-building"></i> Empresas</a></li>
            <li><a href="{{ route('jobs.index') }}" id="nav-vagas-admin"><i class="fa-solid fa-briefcase"></i> Vagas</a></li>
        @else
            <li><a href="{{ route('jobs.index') }}" id="nav-vagas-pub"><i class="fa-solid fa-briefcase"></i> Vagas</a></li>
        @endif
    </ul>

    {{-- Usuário --}}
    <div class="navbar-user-area">
        @if(session('user_id'))
            <div class="navbar-user-info">
                <div class="navbar-user-avatar" title="{{ session('user_nome') }}">
                    {{ strtoupper(substr(session('user_nome'), 0, 1)) }}
                </div>
                <div>
                    <div style="color: var(--clr-text); font-weight: 600; font-size: 0.875rem; line-height: 1.2;">
                        {{ session('user_nome') }}
                    </div>
                    <div style="font-size: 0.7rem; color: var(--clr-text-dim); text-transform: uppercase; letter-spacing: 0.5px;">
                        @switch(session('user_role'))
                            @case('student') Candidato @break
                            @case('recruiter') Recrutador @break
                            @case('admin') Administrador @break
                            @case('master') 🛡️ Master @break
                            @default {{ session('user_role') }}
                        @endswitch
                    </div>
                </div>
            </div>
            <a class="btn btn-secondary btn-sm" href="{{ route('logout') }}" id="btn-logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Sair
            </a>
        @else
            <a class="btn btn-secondary btn-sm" href="{{ route('login') }}" id="btn-login">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Entrar
            </a>
            <a class="btn btn-primary btn-sm" href="{{ route('novo_usuario') }}" id="btn-cadastrar">
                <i class="fa-solid fa-user-plus"></i> Cadastrar
            </a>
        @endif
    </div>
</nav>

{{-- ─── Conteúdo Principal ────────────────────────────────── --}}
<main>
    <div class="container" style="padding-top: 2rem; padding-bottom: 2rem;">

        {{-- Dispara toasts via session --}}
        @if(session('success'))
            <script>showToast('success', {{ json_encode(session('success')) }});</script>
        @endif
        @if($errors->any())
            <script>showToast('error', {{ json_encode($errors->first()) }});</script>
        @endif

        @yield('content')

    </div>
</main>

{{-- ─── Footer ─────────────────────────────────────────────── --}}
<footer class="footer">
    <p>© {{ date('Y') }} <span>SyncMatch</span>, um produto <strong>ApexSync</strong>. Conectando talentos às melhores oportunidades.</p>
</footer>

@yield('scripts')
</body>
</html>
