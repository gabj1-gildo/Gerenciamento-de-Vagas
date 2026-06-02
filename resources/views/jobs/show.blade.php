@extends('layouts.app')

@section('title', $job['title'] . ' — SyncMatch')

@section('content')
@php
    $typeLabels = ['estagio' => 'Estágio', 'clt' => 'CLT', 'pj' => 'PJ', 'temporario' => 'Temporário'];
    $modeLabels = ['presencial' => 'Presencial', 'remoto' => 'Remoto', 'hibrido' => 'Híbrido'];
    $typeColors = ['estagio' => 'badge-purple', 'clt' => 'badge-primary', 'pj' => 'badge-info', 'temporario' => 'badge-warning'];
    $modeColors = ['presencial' => 'badge-success', 'remoto' => 'badge-pink', 'hibrido' => 'badge-info'];

    $hasApplied = null;
    if (session('user_role') === 'student') {
        $hasApplied = \App\Models\Application::where('job_id', $job['id'])
                          ->where('user_id', session('user_id'))->first();
    }
@endphp

{{-- Breadcrumb --}}
<div class="flex items-center gap-sm mb-xl text-sm text-muted">
    <a href="{{ route('jobs.index') }}" style="color: var(--clr-text-muted);">
        <i class="fa-solid fa-briefcase"></i> Vagas
    </a>
    <i class="fa-solid fa-chevron-right" style="font-size: 0.6rem; color: var(--clr-text-dim);"></i>
    <span style="color: var(--clr-text);">{{ $job['title'] }}</span>
</div>

<div class="flex gap-xl items-start" style="flex-wrap: wrap;">

    {{-- Coluna Principal --}}
    <div style="flex: 1; min-width: 300px;">

        {{-- Hero da Vaga --}}
        <div class="card animate-fadeInUp" style="margin-bottom: var(--space-lg); border-radius: var(--radius-xl); overflow: hidden;">
            {{-- Header Gradiente --}}
            <div style="background: linear-gradient(135deg, rgba(99,102,241,0.12) 0%, rgba(139,92,246,0.08) 100%); padding: 2rem; border-bottom: 1px solid var(--glass-border);">
                <div class="flex items-start justify-between gap-md flex-wrap">
                    <div class="flex items-center gap-lg">
                        <div class="company-avatar" style="width: 64px; height: 64px; font-size: 1.5rem; border-radius: var(--radius-lg);">
                            {{ strtoupper(substr($job['company']['name'] ?? 'E', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm text-muted mb-xs">
                                <i class="fa-regular fa-building" style="color: var(--clr-primary-400);"></i>
                                {{ $job['company']['name'] ?? '—' }}
                            </p>
                            <h1 style="font-family: var(--font-display); font-size: 1.625rem; font-weight: 800; color: var(--clr-text); line-height: 1.2; letter-spacing: -0.5px;">
                                {{ $job['title'] }}
                            </h1>
                        </div>
                    </div>

                    <div>
                        @if($job['status'] === 'fechada')
                            <span class="badge badge-danger" style="font-size: 0.8rem; padding: 6px 14px;">
                                <i class="fa-solid fa-lock"></i> Vaga Fechada
                            </span>
                        @else
                            <span class="badge badge-success" style="font-size: 0.8rem; padding: 6px 14px;">
                                <i class="fa-solid fa-circle" style="font-size: 6px;"></i> Aberta
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Métricas Rápidas --}}
                <div class="flex gap-md flex-wrap mt-lg" style="margin-top: 1.5rem;">
                    @if($job['salary_range'])
                        <div class="flex items-center gap-xs" style="background: rgba(99,102,241,0.12); padding: 8px 14px; border-radius: var(--radius-md); border: 1px solid rgba(99,102,241,0.2);">
                            <i class="fa-solid fa-sack-dollar" style="color: var(--clr-primary-400);"></i>
                            <span style="font-weight: 700; color: var(--clr-text); font-size: 0.9375rem;">{{ $job['salary_range'] }}</span>
                        </div>
                    @endif
                    @if($job['company']['city'] ?? false)
                        <div class="flex items-center gap-xs text-muted text-sm">
                            <i class="fa-solid fa-location-dot" style="color: var(--clr-accent-400);"></i>
                            {{ $job['company']['city'] }}
                        </div>
                    @endif
                    <div class="flex gap-sm flex-wrap">
                        @if(isset($job['type']))
                            <span class="badge {{ $typeColors[$job['type']] ?? 'badge-primary' }}">
                                {{ $typeLabels[$job['type']] ?? $job['type'] }}
                            </span>
                        @endif
                        @if(isset($job['mode']))
                            <span class="badge {{ $modeColors[$job['mode']] ?? 'badge-info' }}">
                                <i class="fa-solid fa-{{ $job['mode'] === 'remoto' ? 'house-laptop' : ($job['mode'] === 'hibrido' ? 'arrows-left-right' : 'building') }}"></i>
                                {{ $modeLabels[$job['mode']] ?? $job['mode'] }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Corpo: Descrição --}}
            <div class="card-body">
                <h2 style="font-family: var(--font-display); font-size: 1.125rem; font-weight: 700; color: var(--clr-text); margin-bottom: 1rem;">
                    <i class="fa-solid fa-align-left" style="color: var(--clr-primary-400); margin-right: 0.5rem;"></i>
                    Descrição da Vaga
                </h2>
                <div style="color: var(--clr-text-muted); line-height: 1.8; white-space: pre-line; font-size: 0.9375rem;">
                    {{ $job['description'] }}
                </div>
            </div>

            {{-- Footer: Ações --}}
            <div class="card-footer flex justify-between items-center flex-wrap gap-md">
                <a href="{{ route('jobs.index') }}" class="btn btn-secondary" id="btn-voltar-vagas">
                    <i class="fa-solid fa-arrow-left"></i> Voltar
                </a>

                <div class="flex gap-sm flex-wrap">
                    {{-- Ações do Recrutador/Dono --}}
                    @if(session('user_role') === 'recruiter')
                        @if($job['company']['user_id'] == session('user_id') || session('user_approved'))
                            <a href="{{ route('job.applications', $job['id']) }}" class="btn btn-secondary" id="btn-ver-candidatos">
                                <i class="fa-solid fa-users"></i> Candidatos
                            </a>
                            @if($job['company']['user_id'] == session('user_id'))
                                <a href="{{ route('jobs.edit', $job['id']) }}" class="btn btn-secondary" id="btn-editar-vaga">
                                    <i class="fa-solid fa-pen"></i> Editar
                                </a>
                                <button id="btn-excluir-vaga"
                                    onclick="document.getElementById('delete-form-{{ $job['id'] }}').submit();"
                                    class="btn btn-danger"
                                    onclick="return confirm('Deseja excluir esta vaga?');">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <form id="delete-form-{{ $job['id'] }}" action="{{ route('jobs.destroy', $job['id']) }}" method="GET" style="display: none;">@csrf</form>
                            @endif
                        @endif
                    @endif

                    {{-- Ações do Candidato --}}
                    @if(session('user_role') === 'student')
                        @if($job['status'] === 'fechada')
                            <span class="badge badge-danger" style="padding: 10px 16px; font-size: 0.8125rem;">
                                <i class="fa-solid fa-lock"></i> Encerrada
                            </span>
                        @elseif($hasApplied)
                            <div class="flex items-center gap-sm" style="background: rgba(16,185,129,0.08); border: 1px solid rgba(16,185,129,0.2); padding: 10px 16px; border-radius: var(--radius-md);">
                                <i class="fa-solid fa-circle-check" style="color: var(--clr-success);"></i>
                                <span style="font-size: 0.875rem; color: var(--clr-text);">
                                    Candidatura enviada — <strong>{{ ucfirst(str_replace('_', ' ', $hasApplied->status)) }}</strong>
                                </span>
                            </div>
                        @else
                            <button class="btn btn-primary" id="btn-abrir-modal" onclick="document.getElementById('modal-candidatura').style.display='flex'">
                                <i class="fa-solid fa-paper-plane"></i> Me Candidatar
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- Coluna Lateral: Info da Empresa --}}
    <div style="width: 280px; flex-shrink: 0;">
        <div class="card animate-fadeInUp stagger-2">
            <div class="card-body">
                <h3 style="font-family: var(--font-display); font-size: 0.9375rem; font-weight: 700; color: var(--clr-text); margin-bottom: 1rem;">
                    <i class="fa-regular fa-building" style="color: var(--clr-primary-400);"></i>
                    Sobre a Empresa
                </h3>
                <div class="flex items-center gap-md mb-md">
                    <div class="company-avatar" style="width: 48px; height: 48px; border-radius: var(--radius-sm); flex-shrink: 0;">
                        {{ strtoupper(substr($job['company']['name'] ?? 'E', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--clr-text); font-size: 0.9375rem;">
                            {{ $job['company']['name'] ?? '—' }}
                        </div>
                        @if($job['company']['area'] ?? false)
                            <div class="text-sm text-muted">{{ $job['company']['area'] }}</div>
                        @endif
                    </div>
                </div>

                @if($job['company']['city'] ?? false)
                    <div class="flex items-center gap-xs text-sm text-muted mb-sm">
                        <i class="fa-solid fa-location-dot" style="color: var(--clr-accent-400); width: 16px;"></i>
                        {{ $job['company']['city'] }}
                    </div>
                @endif

                @if($job['company']['description'] ?? false)
                    <div class="divider"></div>
                    <p class="text-sm text-muted" style="line-height: 1.6;">
                        {{ Str::limit($job['company']['description'], 120) }}
                    </p>
                @endif

                <a href="{{ route('companies.show', $job['company']['id']) }}" class="btn btn-secondary btn-sm btn-block mt-md" id="btn-ver-empresa">
                    Ver perfil da empresa <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="card animate-fadeInUp stagger-3 mt-lg">
            <div class="card-body">
                <div class="flex items-center gap-sm text-sm text-muted">
                    <i class="fa-regular fa-calendar" style="color: var(--clr-primary-400);"></i>
                    Publicada {{ \Carbon\Carbon::parse($job['created_at'])->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Candidatura (sem Bootstrap) --}}
@if(session('user_role') === 'student' && !$hasApplied && $job['status'] !== 'fechada')
<div id="modal-candidatura"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); backdrop-filter:blur(8px); z-index:9999; align-items:center; justify-content:center; padding:1rem;"
     onclick="if(event.target===this)this.style.display='none'">
    <div style="background: #13132b; border: 1px solid var(--glass-border); border-radius: var(--radius-xl); padding: 2rem; max-width: 500px; width: 100%; animation: fadeInUp 0.3s ease;">
        <div class="flex justify-between items-center mb-lg">
            <h2 style="font-family: var(--font-display); font-size: 1.25rem; font-weight: 800; color: var(--clr-text);">
                <i class="fa-solid fa-paper-plane" style="color: var(--clr-primary-400);"></i>
                Confirmar Candidatura
            </h2>
            <button onclick="document.getElementById('modal-candidatura').style.display='none'"
                    style="background:none; border:none; color:var(--clr-text-muted); font-size:1.25rem; cursor:pointer; padding:4px;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <p class="text-sm text-muted mb-lg">
            <i class="fa-solid fa-info-circle" style="color: var(--clr-primary-400);"></i>
            Seu currículo PDF cadastrado no perfil será compartilhado com o recrutador.
        </p>

        <form action="{{ route('applications.store', $job['id']) }}" method="POST" id="form-candidatura">
            @csrf
            <div class="form-group">
                <label class="form-label" for="cover_letter">Carta de Apresentação (Opcional)</label>
                <textarea name="cover_letter" id="cover_letter" rows="5" class="form-control"
                    placeholder="Por que você é o candidato ideal para esta vaga? Destaque sua experiência..."></textarea>
                <p class="form-hint">Máximo 5.000 caracteres</p>
            </div>
            <div class="flex gap-md justify-end">
                <button type="button" class="btn btn-secondary" id="btn-cancelar-modal"
                    onclick="document.getElementById('modal-candidatura').style.display='none'">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary" id="btn-enviar-candidatura">
                    <i class="fa-solid fa-paper-plane"></i> Enviar Candidatura
                </button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection
