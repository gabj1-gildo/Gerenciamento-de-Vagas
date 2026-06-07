@extends('layouts.app')

@section('title', 'Painel Master — SyncMatch')

@section('head')
<style>
    .master-panel-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .master-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        padding: 0.3rem 0.75rem;
        border-radius: 50px;
        box-shadow: 0 2px 12px rgba(124, 58, 237, 0.4);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        text-align: center;
        transition: var(--transition-fast);
    }

    .stat-card:hover {
        background: rgba(255,255,255,0.07);
        transform: translateY(-2px);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        font-family: var(--font-display);
        background: var(--gradient-primary);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--clr-text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.4rem;
    }

    .panel-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid var(--glass-border);
        padding-bottom: 0.5rem;
    }

    .panel-tab {
        padding: 0.5rem 1.25rem;
        border-radius: var(--radius-md) var(--radius-md) 0 0;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        color: var(--clr-text-muted);
        background: transparent;
        border: none;
        border-bottom: 2px solid transparent;
        transition: var(--transition-fast);
    }

    .panel-tab.active {
        color: var(--clr-primary-400);
        border-bottom-color: var(--clr-primary-400);
    }

    .panel-tab:hover:not(.active) {
        color: var(--clr-text);
    }

    .tab-content { display: none; }
    .tab-content.active { display: block; animation: fadeIn 0.25s ease; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .filter-bar {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .filter-bar .form-control,
    .filter-bar .form-select {
        max-width: 220px;
    }

    .users-table-wrap {
        overflow-x: auto;
        border-radius: var(--radius-lg);
        border: 1px solid var(--glass-border);
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }

    .users-table thead th {
        background: rgba(255,255,255,0.05);
        padding: 0.875rem 1rem;
        text-align: left;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--clr-text-dim);
        white-space: nowrap;
    }

    .users-table tbody tr {
        border-top: 1px solid var(--glass-border);
        transition: background 0.15s;
    }

    .users-table tbody tr:hover {
        background: rgba(255,255,255,0.03);
    }

    .users-table td {
        padding: 0.875rem 1rem;
        color: var(--clr-text);
        vertical-align: middle;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .role-badge.master   { background: rgba(124,58,237,0.2); color: #a78bfa; border: 1px solid rgba(124,58,237,0.3); }
    .role-badge.admin    { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.25); }
    .role-badge.recruiter{ background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }
    .role-badge.student  { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }

    .role-select-inline {
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--glass-border);
        color: var(--clr-text);
        border-radius: var(--radius-sm);
        padding: 0.3rem 0.5rem;
        font-size: 0.8rem;
        cursor: pointer;
    }

    .user-avatar-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--gradient-primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        color: #fff;
        flex-shrink: 0;
    }

    .user-name-cell {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .company-create-form {
        background: rgba(255,255,255,0.03);
        border: 1px solid var(--glass-border);
        border-radius: var(--radius-lg);
        padding: 2rem;
    }

    .form-grid-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .form-grid-3 { grid-template-columns: 1fr; }
        .filter-bar .form-control,
        .filter-bar .form-select { max-width: 100%; }
    }

    /* ─── Modal de Confirmação ────────────────────────────── */
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.65);
        backdrop-filter: blur(6px);
        animation: overlayIn 0.2s ease;
    }
    .modal-overlay.show { display: flex; }
    @keyframes overlayIn { from { opacity: 0; } to { opacity: 1; } }

    .modal-box {
        background: #1a1a2e;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 18px;
        padding: 2rem 2rem 1.75rem;
        max-width: 440px;
        width: 100%;
        box-shadow: 0 24px 64px rgba(0,0,0,0.6), 0 0 0 1px rgba(124,58,237,0.15);
        animation: modalIn 0.3s cubic-bezier(.21,1.02,.73,1);
        position: relative;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.92) translateY(12px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }

    .modal-icon-wrap {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: rgba(124, 58, 237, 0.15);
        border: 1px solid rgba(124, 58, 237, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1.25rem;
    }

    .modal-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--clr-text);
        text-align: center;
        margin-bottom: 0.5rem;
        font-family: var(--font-display);
    }

    .modal-desc {
        font-size: 0.875rem;
        color: var(--clr-text-muted);
        text-align: center;
        line-height: 1.55;
        margin-bottom: 1.5rem;
    }

    .modal-desc .modal-user {
        color: var(--clr-text);
        font-weight: 700;
    }

    .modal-desc .modal-role-from,
    .modal-desc .modal-role-to {
        display: inline-block;
        padding: 0.15rem 0.6rem;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .modal-desc .modal-role-from {
        background: rgba(255,255,255,0.06);
        color: var(--clr-text-muted);
    }
    .modal-desc .modal-role-to {
        background: rgba(124,58,237,0.2);
        color: #a78bfa;
        border: 1px solid rgba(124,58,237,0.3);
    }

    .modal-actions {
        display: flex;
        gap: 0.75rem;
    }
    .modal-actions .btn { flex: 1; justify-content: center; }

    .modal-close-x {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: none;
        border: none;
        color: var(--clr-text-dim);
        cursor: pointer;
        font-size: 1.1rem;
        line-height: 1;
        transition: color 0.15s;
        padding: 0.25rem;
    }
    .modal-close-x:hover { color: var(--clr-text); }
</style>
@endsection

@section('content')

<div class="master-panel-header">
    <div>
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
            <h1 class="page-title" style="font-size: 1.75rem; margin: 0;">Painel Master</h1>
            <span class="master-badge"><i class="fa-solid fa-shield-halved"></i> Super Admin</span>
        </div>
        <p class="text-muted" style="margin: 0;">Gerencie usuários, perfis e empresas do sistema SyncMatch.</p>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $users->count() }}</div>
        <div class="stat-label">Total de Usuários</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $users->where('role', 'master')->count() }}</div>
        <div class="stat-label">Masters</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $users->where('role', 'admin')->count() }}</div>
        <div class="stat-label">Admins (Empresa)</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $users->where('role', 'recruiter')->count() }}</div>
        <div class="stat-label">Recrutadores</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $users->where('role', 'student')->count() }}</div>
        <div class="stat-label">Candidatos</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $companies->count() }}</div>
        <div class="stat-label">Empresas</div>
    </div>
</div>

{{-- Tabs --}}
<div class="panel-tabs">
    <button class="panel-tab active" id="tab-users-btn" onclick="switchTab('users')">
        <i class="fa-solid fa-users"></i> Gerenciar Usuários
    </button>
    <button class="panel-tab" id="tab-companies-btn" onclick="switchTab('companies')">
        <i class="fa-regular fa-building"></i> Criar Empresa
    </button>
</div>

{{-- ── TAB: Usuários ──────────────────────────────────────────── --}}
<div class="tab-content active" id="tab-users">

    {{-- Filtros --}}
    <form method="GET" action="{{ route('master.panel') }}" class="filter-bar">
        <div style="position: relative; flex: 1; min-width: 180px;">
            <i class="fa-solid fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--clr-text-dim); font-size: 0.8rem;"></i>
            <input type="text" name="search" class="form-control" placeholder="Buscar por nome ou e-mail..."
                value="{{ request('search') }}" style="padding-left: 36px;">
        </div>
        <select name="role" class="form-select" style="max-width: 180px;">
            <option value="">Todos os perfis</option>
            <option value="master"    {{ request('role') === 'master'    ? 'selected' : '' }}>🛡️ Master</option>
            <option value="admin"     {{ request('role') === 'admin'     ? 'selected' : '' }}>🏢 Admin de Empresa</option>
            <option value="recruiter" {{ request('role') === 'recruiter' ? 'selected' : '' }}>🤝 Recrutador</option>
            <option value="student"   {{ request('role') === 'student'   ? 'selected' : '' }}>🎓 Candidato</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filtrar</button>
        @if(request('search') || request('role'))
            <a href="{{ route('master.panel') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i> Limpar</a>
        @endif
    </form>

    <div class="users-table-wrap">
        <table class="users-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuário</th>
                    <th>E-mail</th>
                    <th>Perfil</th>
                    <th>Cadastro</th>
                    <th>Alterar Perfil</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td style="color: var(--clr-text-dim); font-size: 0.75rem;">{{ $u->id }}</td>
                        <td>
                            <div class="user-name-cell">
                                <div class="user-avatar-sm">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                                <span style="font-weight: 600;">{{ $u->name }}</span>
                                @if($u->id == session('user_id'))
                                    <span style="font-size: 0.65rem; color: var(--clr-primary-400); font-weight: 700;">(você)</span>
                                @endif
                            </div>
                        </td>
                        <td style="color: var(--clr-text-muted);">{{ $u->email }}</td>
                        <td>
                            @php
                                $labels = ['master' => 'Master', 'admin' => 'Admin de Empresa', 'recruiter' => 'Recrutador', 'student' => 'Candidato'];
                                $icons  = ['master' => '🛡️', 'admin' => '🏢', 'recruiter' => '🤝', 'student' => '🎓'];
                            @endphp
                            <span class="role-badge {{ $u->role }}">
                                {{ $icons[$u->role] ?? '' }} {{ $labels[$u->role] ?? $u->role }}
                            </span>
                        </td>
                        <td style="color: var(--clr-text-dim); font-size: 0.78rem;">
                            {{ \Carbon\Carbon::parse($u->created_at)->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($u->id == session('user_id'))
                                <span style="font-size: 0.75rem; color: var(--clr-text-dim); font-style: italic;">—</span>
                            @else
                                <form method="POST"
                                      action="{{ route('master.updateRole', $u->id) }}"
                                      id="form-perfil-{{ $u->id }}"
                                      style="display: flex; gap: 0.4rem; align-items: center;">
                                    @csrf
                                    <select name="role" class="role-select-inline" id="perfil-select-{{ $u->id }}">
                                        <option value="student"   {{ $u->role === 'student'   ? 'selected' : '' }}>🎓 Candidato</option>
                                        <option value="recruiter" {{ $u->role === 'recruiter' ? 'selected' : '' }}>🤝 Recrutador</option>
                                        <option value="admin"     {{ $u->role === 'admin'     ? 'selected' : '' }}>🏢 Admin de Empresa</option>
                                        <option value="master"    {{ $u->role === 'master'    ? 'selected' : '' }}>🛡️ Master</option>
                                    </select>
                                    <button type="button"
                                        class="btn btn-primary btn-sm"
                                        style="padding: 0.3rem 0.65rem;"
                                        onclick="abrirModalPerfil(
                                            {{ $u->id }},
                                            '{{ addslashes($u->name) }}',
                                            '{{ $u->role }}'
                                        )">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2.5rem; color: var(--clr-text-muted);">
                            <i class="fa-solid fa-users-slash" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
                            Nenhum usuário encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding: 1rem;">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>

{{-- ── TAB: Criar Empresa ──────────────────────────────────────── --}}
<div class="tab-content" id="tab-companies">
    <div class="company-create-form">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
            <span style="font-size: 1.75rem;">🏢</span>
            <div>
                <div style="font-weight: 700; font-size: 1rem;">Criar Nova Empresa</div>
                <div style="font-size: 0.8125rem; color: var(--clr-text-muted);">Cadastre uma empresa e defina quem será o administrador responsável.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('master.createCompany') }}">
            @csrf

            <div class="form-grid-3" style="margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="comp-name">Nome da Empresa *</label>
                    <input type="text" class="form-control" id="comp-name" name="name"
                        placeholder="Ex: Wamag Corp" value="{{ old('name') }}" required>
                    @error('name')<div style="font-size:0.75rem;color:#fca5a5;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="comp-cnpj">CNPJ *</label>
                    <input type="text" class="form-control" id="comp-cnpj" name="cnpj"
                        placeholder="00.000.000/0000-00" value="{{ old('cnpj') }}" required>
                    @error('cnpj')<div style="font-size:0.75rem;color:#fca5a5;margin-top:4px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="comp-city">Cidade *</label>
                    <input type="text" class="form-control" id="comp-city" name="city"
                        placeholder="Ex: São Paulo" value="{{ old('city') }}" required>
                    @error('city')<div style="font-size:0.75rem;color:#fca5a5;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-grid-3" style="margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label" for="comp-area">Área de Atuação</label>
                    <input type="text" class="form-control" id="comp-area" name="area"
                        placeholder="Ex: Tecnologia" value="{{ old('area') }}">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" for="comp-user">Administrador (Dono) *</label>
                    <select name="user_id" id="comp-user" class="form-select" required>
                        <option value="">Selecione o usuário responsável...</option>
                        @foreach($users as $u)
                            @php
                                $perfilLabels = ['master' => 'Master', 'admin' => 'Admin de Empresa', 'recruiter' => 'Recrutador', 'student' => 'Candidato'];
                            @endphp
                            <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }}) — {{ $perfilLabels[$u->role] ?? $u->role }}
                            </option>
                        @endforeach
                    </select>
                    <p style="font-size: 0.75rem; color: var(--clr-text-muted); margin-top: 4px;">
                        <i class="fa-solid fa-circle-info"></i>
                        O usuário será automaticamente promovido para <strong>Admin de Empresa</strong> se não tiver esse perfil.
                    </p>
                    @error('user_id')<div style="font-size:0.75rem;color:#fca5a5;margin-top:4px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label" for="comp-desc">Descrição da Empresa</label>
                <textarea class="form-control" id="comp-desc" name="description" rows="3"
                    placeholder="Descreva brevemente a empresa...">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" id="btn-criar-empresa">
                <i class="fa-solid fa-building-circle-check"></i> Criar Empresa
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')

{{-- ─── Modal de Confirmação de Perfil ───────────────────────── --}}
<div class="modal-overlay" id="modal-confirmar" role="dialog" aria-modal="true" aria-labelledby="modal-title-txt">
    <div class="modal-box">
        <button class="modal-close-x" onclick="fecharModal()" aria-label="Fechar">&times;</button>

        <div class="modal-icon-wrap">🛡️</div>

        <div class="modal-title" id="modal-title-txt">Alterar Perfil</div>
        <p class="modal-desc" id="modal-desc-txt"></p>

        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="fecharModal()" id="modal-btn-cancelar">
                <i class="fa-solid fa-xmark"></i> Cancelar
            </button>
            <button class="btn btn-primary" onclick="confirmarAlteracao()" id="modal-btn-confirmar">
                <i class="fa-solid fa-check"></i> Confirmar
            </button>
        </div>
    </div>
</div>

<script>
    const perfilLabels = {
        student:   '🎓 Candidato',
        recruiter: '🤝 Recrutador',
        admin:     '🏢 Admin de Empresa',
        master:    '🛡️ Master'
    };

    let _formPendente = null;

    function abrirModalPerfil(userId, userName, roleAtual) {
        const selectEl  = document.getElementById('perfil-select-' + userId);
        const novoRole  = selectEl.value;
        const form      = document.getElementById('form-perfil-' + userId);

        // Se não mudou nada, não abre o modal
        if (novoRole === roleAtual) {
            showToast('error', 'Selecione um perfil diferente do atual.');
            return;
        }

        _formPendente = form;

        const labelAtual = perfilLabels[roleAtual] || roleAtual;
        const labelNovo  = perfilLabels[novoRole]  || novoRole;

        document.getElementById('modal-desc-txt').innerHTML = `
            Você está prestes a alterar o perfil de
            <span class="modal-user">${userName}</span>.<br><br>
            <span class="modal-role-from">${labelAtual}</span>
            &nbsp;<i class="fa-solid fa-arrow-right" style="color:var(--clr-text-dim);font-size:.7rem;"></i>&nbsp;
            <span class="modal-role-to">${labelNovo}</span>
        `;

        const overlay = document.getElementById('modal-confirmar');
        overlay.classList.add('show');
        document.getElementById('modal-btn-confirmar').focus();
    }

    function fecharModal() {
        const overlay = document.getElementById('modal-confirmar');
        overlay.classList.remove('show');
        _formPendente = null;
    }

    function confirmarAlteracao() {
        if (_formPendente) {
            const form = _formPendente; // salva ref ANTES de fecharModal() zerar a variável
            fecharModal();
            form.submit();
        }
    }

    // Fechar ao clicar no overlay
    document.getElementById('modal-confirmar').addEventListener('click', function(e) {
        if (e.target === this) fecharModal();
    });

    // Fechar com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') fecharModal();
    });

    function switchTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.panel-tab').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');
        document.getElementById('tab-' + tab + '-btn').classList.add('active');
    }

    @if($errors->has('name') || $errors->has('cnpj') || $errors->has('city') || $errors->has('user_id'))
        document.addEventListener('DOMContentLoaded', () => switchTab('companies'));
    @endif
</script>
@endsection
