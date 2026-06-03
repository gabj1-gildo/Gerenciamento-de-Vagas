@extends('layouts.app')

@section('title', (isset($companies[0]) ? $companies[0]['name'] : 'Empresa') . ' — SyncMatch')

@section('content')
@php $company = $companies[0] ?? null; @endphp

@if(!$company)
    <div class="alert alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> Empresa não encontrada.</div>
@else

{{-- Breadcrumb --}}
<div class="flex items-center gap-sm mb-xl text-sm text-muted">
    <a href="{{ route('companies.index') }}" style="color: var(--clr-text-muted);">
        <i class="fa-regular fa-building"></i> Empresas
    </a>
    <i class="fa-solid fa-chevron-right" style="font-size: 0.6rem; color: var(--clr-text-dim);"></i>
    <span style="color: var(--clr-text);">{{ $company['name'] }}</span>
</div>

{{-- Hero da Empresa --}}
<div class="card animate-fadeInUp mb-xl" style="border-radius: var(--radius-xl); overflow: hidden;">
    <div style="background: linear-gradient(135deg, rgba(99,102,241,0.1), rgba(139,92,246,0.06)); padding: 2rem; border-bottom: 1px solid var(--glass-border);">
        <div class="flex items-start justify-between flex-wrap gap-md">
            <div class="flex items-center gap-lg">
                <div class="company-avatar" style="width: 72px; height: 72px; font-size: 1.75rem; border-radius: var(--radius-lg); flex-shrink: 0;">
                    {{ strtoupper(substr($company['name'], 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs text-muted mb-xs" style="text-transform: uppercase; letter-spacing: 0.8px; font-weight: 700;">Perfil da Empresa</p>
                    <h1 style="font-family: var(--font-display); font-size: 2rem; font-weight: 900; color: var(--clr-text); line-height: 1.1; letter-spacing: -0.5px;">
                        {{ $company['name'] }}
                    </h1>
                    @if($company['area'] ?? false)
                        <p class="text-muted mt-xs">{{ $company['area'] }}</p>
                    @endif
                </div>
            </div>

            <div class="flex gap-sm">
                @if(session('user_role') === 'admin')
                    <a href="{{ route('companies.edit', $company['id']) }}" class="btn btn-secondary btn-sm" id="btn-editar-empresa">
                        <i class="fa-solid fa-pen"></i> Editar
                    </a>
                    <a href="{{ route('companies.destroy', $company['id']) }}" class="btn btn-danger btn-sm" id="btn-excluir-empresa"
                       onclick="return confirm('Excluir empresa {{ addslashes($company['name']) }}?')">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                @endif
            </div>
        </div>

        {{-- Métricas rápidas --}}
        <div class="flex gap-md flex-wrap mt-lg">
            @if($company['cnpj'] ?? false)
                <div class="flex items-center gap-xs text-sm text-muted">
                    <i class="fa-solid fa-id-card" style="color: var(--clr-primary-400);"></i>
                    {{ $company['cnpj'] }}
                </div>
            @endif
            @if($company['city'] ?? false)
                <div class="flex items-center gap-xs text-sm text-muted">
                    <i class="fa-solid fa-location-dot" style="color: var(--clr-accent-400);"></i>
                    {{ $company['city'] }}
                </div>
            @endif
            <div class="flex items-center gap-xs text-sm" style="background: rgba(99,102,241,0.12); padding: 6px 14px; border-radius: var(--radius-full); border: 1px solid rgba(99,102,241,0.2);">
                <i class="fa-solid fa-briefcase" style="color: var(--clr-primary-400);"></i>
                <span style="font-weight: 700; color: var(--clr-text);">{{ count($jobs) }}</span>
                <span style="color: var(--clr-text-muted);">vagas</span>
            </div>
        </div>
    </div>

    @if($company['description'] ?? false)
    <div class="card-body">
        <p style="color: var(--clr-text-muted); line-height: 1.8; font-size: 0.9375rem; white-space: pre-line;">
            {{ $company['description'] }}
        </p>
    </div>
    @endif
</div>

{{-- Tabs: Vagas e Recrutadores --}}
<div class="tabs" id="company-tabs">
    <button class="tab-btn active" id="tab-vagas" onclick="switchTab('vagas')">
        <i class="fa-solid fa-briefcase"></i> Vagas ({{ count($jobs) }})
    </button>
    @if(count($recruiters) > 0 || session('user_role') === 'admin' || ($company['user_id'] ?? null) == session('user_id'))
        <button class="tab-btn" id="tab-recrutadores" onclick="switchTab('recrutadores')">
            <i class="fa-solid fa-users"></i> Equipe ({{ count($recruiters) }})
        </button>
    @endif
</div>

{{-- Tab: Vagas --}}
<div id="panel-vagas" class="tab-panel active animate-fadeIn">
    @if(session('user_role') === 'admin' || (session('user_role') === 'recruiter' && session('user_approved')))
        <div class="flex justify-end mb-lg">
            <a href="{{ route('jobs.create') }}" class="btn btn-primary btn-sm" id="btn-nova-vaga">
                <i class="fa-solid fa-plus"></i> Cadastrar Vaga
            </a>
        </div>
    @endif

    @if(count($jobs))
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Vaga</th>
                        <th>Tipo</th>
                        <th>Modalidade</th>
                        <th>Remuneração</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobs as $job)
                    @php
                        $typeLabels = ['estagio'=>'Estágio','clt'=>'CLT','pj'=>'PJ','temporario'=>'Temporário'];
                        $modeLabels = ['presencial'=>'Presencial','remoto'=>'Remoto','hibrido'=>'Híbrido'];
                    @endphp
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: var(--clr-text);">{{ $job['title'] }}</div>
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $typeLabels[$job['type']] ?? $job['type'] }}</span>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $modeLabels[$job['mode']] ?? $job['mode'] }}</span>
                        </td>
                        <td style="color: var(--clr-primary-400); font-weight: 600;">
                            {{ $job['salary_range'] ?? 'A combinar' }}
                        </td>
                        <td>
                            @if($job['status'] === 'fechada')
                                <span class="badge badge-danger"><i class="fa-solid fa-lock"></i> Fechada</span>
                            @else
                                <span class="badge badge-success"><i class="fa-solid fa-circle" style="font-size:6px;"></i> Aberta</span>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-xs">
                                <a href="{{ route('jobs.show', $job['id']) }}" class="btn btn-secondary btn-sm" id="btn-ver-job-{{ $job['id'] }}">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                @if(session('user_role') === 'admin' || session('user_approved'))
                                    <a href="{{ route('job.applications', $job['id']) }}" class="btn btn-secondary btn-sm" id="btn-candidatos-job-{{ $job['id'] }}">
                                        <i class="fa-solid fa-users"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📋</div>
            <div class="empty-state-title">Nenhuma vaga cadastrada</div>
            <p>Esta empresa ainda não publicou vagas.</p>
        </div>
    @endif
</div>

{{-- Tab: Recrutadores --}}
@if(count($recruiters) > 0 || session('user_role') === 'admin' || ($company['user_id'] ?? null) == session('user_id'))
<div id="panel-recrutadores" class="tab-panel animate-fadeIn">
    <div class="flex justify-between items-center mb-lg">
        <h2 style="font-family: var(--font-display); font-size: 1.125rem; font-weight: 700; color: var(--clr-text);">
            <i class="fa-solid fa-users" style="color: var(--clr-primary-400);"></i>
            Equipe de Recrutadores
        </h2>
        @php $pendentes = collect($recruiters)->filter(function($r) { return !$r->pivot->approved; })->count(); @endphp
        @if($pendentes > 0)
            <span class="badge badge-warning">
                <i class="fa-solid fa-clock"></i>
                {{ $pendentes }} pendente{{ $pendentes > 1 ? 's' : '' }}
            </span>
        @endif
    </div>

    @if(count($recruiters))
        <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
            @foreach($recruiters as $profile)
            <div style="background: var(--glass-bg); border: 1px solid {{ $profile->pivot->approved ? 'var(--glass-border)' : 'rgba(245,158,11,0.25)' }}; border-radius: var(--radius-lg); padding: var(--space-lg); display: flex; align-items: center; justify-content: space-between; gap: var(--space-md); flex-wrap: wrap;">
                <div class="flex items-center gap-md">
                    <div class="navbar-user-avatar" style="width: 44px; height: 44px; font-size: 1rem; flex-shrink: 0;">
                        {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--clr-text); font-size: 0.9375rem;">
                            {{ $profile->user->name }}
                        </div>
                        <div class="text-xs text-muted">{{ $profile->user->email }}</div>
                    </div>
                </div>

                <div>
                    @if($profile->pivot->approved)
                        <span class="badge badge-success" style="padding: 8px 16px; font-size: 0.8rem;">
                            <i class="fa-solid fa-circle-check"></i> Aprovado
                        </span>
                    @else
                        <div class="flex items-center gap-sm flex-wrap">
                            <span class="badge badge-warning">
                                <i class="fa-solid fa-clock"></i> Aguardando aprovação
                            </span>
                            @if(session('user_role') === 'admin' || ($company['user_id'] ?? null) == session('user_id'))
                                <form action="{{ route('recruiters.approve', $profile->id) }}" method="POST" id="form-approve-{{ $profile->id }}" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="company_id" value="{{ $company['id'] }}">
                                    <button type="submit" class="btn btn-success btn-sm" id="btn-aprovar-{{ $profile->id }}">
                                        <i class="fa-solid fa-circle-check"></i> Aprovar Acesso
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">👥</div>
            <div class="empty-state-title">Nenhum recrutador</div>
            <p>Nenhum recrutador associado a esta empresa ainda.</p>
        </div>
    @endif
</div>
@endif

{{-- Botão Voltar --}}
<div class="mt-xl">
    <a href="{{ route('companies.index') }}" class="btn btn-secondary" id="btn-voltar-empresas">
        <i class="fa-solid fa-arrow-left"></i> Voltar para Empresas
    </a>
</div>

@endif
@endsection

@section('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));

    document.getElementById('tab-' + tab).classList.add('active');
    document.getElementById('panel-' + tab).classList.add('active');
}
</script>
@endsection