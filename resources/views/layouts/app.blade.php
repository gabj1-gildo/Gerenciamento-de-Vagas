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
</head>
<body>

{{-- ─── Navbar ───────────────────────────────────────────── --}}
<nav class="navbar-syncmatch">
    {{-- Brand --}}
    <a class="navbar-brand-wrap" href="{{ url('/') }}">
        <span class="navbar-logo-text">SyncMatch</span>
        <span class="navbar-logo-badge">Beta</span>
    </a>

    {{-- Links de Navegação --}}
    <ul class="navbar-nav-links">
        @if(session('user_role') === 'recruiter')
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

        {{-- Mensagens de Sucesso --}}
        @if(session('success'))
            <div class="alert alert-success animate-fadeInUp" id="alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Erros de Validação --}}
        @if($errors->any())
            <div class="alert alert-danger animate-fadeInUp" id="alert-errors">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                    <strong>Ops! Verifique os campos abaixo:</strong>
                    <ul style="list-style: none; padding: 0; margin: 4px 0 0 0;">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')

    </div>
</main>

{{-- ─── Footer ─────────────────────────────────────────────── --}}
<footer class="footer">
    <p>© {{ date('Y') }} <span>SyncMatch</span> — Conectando talentos às melhores oportunidades.</p>
</footer>

@yield('scripts')
</body>
</html>
