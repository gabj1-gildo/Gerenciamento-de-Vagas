@extends('layouts.app')

@section('title', 'Candidatos — ' . $job->title . ' — SyncMatch')

@section('content')
<div class="flex justify-between items-start flex-wrap gap-md mb-xl">
    <div>
        <div class="flex items-center gap-sm mb-sm text-sm text-muted">
            <a href="{{ route('jobs.index') }}" style="color:var(--clr-text-muted);">
                <i class="fa-solid fa-briefcase"></i> Vagas
            </a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;color:var(--clr-text-dim);"></i>
            <a href="{{ route('jobs.show', $job->id) }}" style="color:var(--clr-text-muted);">{{ $job->title }}</a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;color:var(--clr-text-dim);"></i>
            <span style="color:var(--clr-text);">Candidatos</span>
        </div>
        <h1 class="page-title" style="font-size: 1.625rem;">
            <i class="fa-solid fa-users text-gradient" style="margin-right: 0.5rem;"></i>
            {{ $job->title }}
        </h1>
        <div class="flex items-center gap-sm mt-sm flex-wrap">
            <span class="text-sm text-muted">
                <i class="fa-regular fa-building" style="color:var(--clr-primary-400);"></i>
                {{ $job->company->name }}
            </span>
            @if($job->status === 'fechada')
                <span class="badge badge-danger"><i class="fa-solid fa-lock"></i> Fechada</span>
            @else
                <span class="badge badge-success"><i class="fa-solid fa-circle" style="font-size:6px;"></i> Aberta</span>
            @endif
            <span class="badge badge-primary">
                <i class="fa-solid fa-users"></i>
                {{ $applications->count() }} candidato{{ $applications->count() !== 1 ? 's' : '' }}
            </span>
        </div>
    </div>
    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-secondary btn-sm" id="btn-ver-vaga-detalhe">
        <i class="fa-solid fa-eye"></i> Ver Vaga
    </a>
</div>

@php
$stages = [
    'recebido'   => ['title' => 'Recebidos',    'icon' => 'fa-inbox',           'color' => 'var(--clr-info)',    'badge' => 'badge-info'],
    'em_analise' => ['title' => 'Em Análise',   'icon' => 'fa-magnifying-glass','color' => 'var(--clr-warning)', 'badge' => 'badge-warning'],
    'entrevista' => ['title' => 'Entrevista',   'icon' => 'fa-calendar-check',  'color' => 'var(--clr-accent-400)','badge' => 'badge-purple'],
    'aprovado'   => ['title' => 'Aprovados',    'icon' => 'fa-trophy',          'color' => 'var(--clr-success)', 'badge' => 'badge-success'],
    'rejeitado'  => ['title' => 'Não Avançou', 'icon' => 'fa-circle-xmark',    'color' => 'var(--clr-danger)',  'badge' => 'badge-danger'],
];
@endphp

{{-- Kanban Board --}}
<div class="kanban-board animate-fadeInUp" style="grid-template-columns: repeat(5, 1fr);">
    @foreach($stages as $stageKey => $stageInfo)
    @php $stageApps = $applications->where('status', $stageKey); @endphp
    <div class="kanban-col" style="border-top: 3px solid {{ $stageInfo['color'] }}; border-radius: 0 0 var(--radius-lg) var(--radius-lg);">
        <div class="kanban-col-header">
            <i class="fa-solid {{ $stageInfo['icon'] }}" style="color: {{ $stageInfo['color'] }};"></i>
            {{ $stageInfo['title'] }}
            <span style="margin-left: auto; background: rgba(255,255,255,0.08); border-radius: var(--radius-full); padding: 2px 8px; font-size: 0.7rem; font-weight: 700;">
                {{ $stageApps->count() }}
            </span>
        </div>

        <div style="min-height: 350px; display: flex; flex-direction: column; gap: 0.5rem;">
            @forelse($stageApps as $app)
            <div class="kanban-applicant" id="applicant-card-{{ $app->id }}">
                {{-- Candidato --}}
                <div class="flex items-center gap-sm mb-sm">
                    <div class="navbar-user-avatar" style="width: 32px; height: 32px; font-size: 0.75rem; flex-shrink: 0;">
                        {{ strtoupper(substr($app->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size: 0.8125rem; font-weight: 700; color: var(--clr-text); line-height: 1.2;">
                            {{ Str::limit($app->user->name, 20) }}
                        </div>
                        <div style="font-size: 0.7rem; color: var(--clr-text-dim);">
                            {{ Str::limit($app->user->email, 22) }}
                        </div>
                    </div>
                </div>

                {{-- Skills --}}
                @if($app->user->candidateProfile?->skills)
                <div style="display: flex; flex-wrap: wrap; gap: 3px; margin-bottom: 6px;">
                    @foreach(array_slice(explode(',', $app->user->candidateProfile->skills), 0, 2) as $skill)
                        <span class="badge badge-primary" style="font-size: 0.6rem; padding: 2px 6px;">{{ trim($skill) }}</span>
                    @endforeach
                </div>
                @endif

                {{-- Carta resumo --}}
                @if($app->cover_letter)
                <div style="font-size: 0.7rem; color: var(--clr-text-dim); margin-bottom: 6px; line-height: 1.4; font-style: italic;">
                    "{{ Str::limit($app->cover_letter, 60) }}"
                </div>
                @endif

                {{-- Ações --}}
                <div style="display: flex; flex-direction: column; gap: 4px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 8px; margin-top: 4px;">
                    @if($app->user->candidateProfile?->resume_path)
                        <a href="{{ asset('storage/' . $app->user->candidateProfile->resume_path) }}"
                           target="_blank" class="btn btn-secondary btn-sm"
                           id="btn-curriculo-{{ $app->id }}"
                           style="font-size: 0.7rem; padding: 4px 8px;">
                            <i class="fa-solid fa-file-pdf" style="color: #ef4444;"></i> Currículo
                        </a>
                    @endif

                    <form action="{{ route('applications.updateStatus', $app->id) }}" method="POST" id="form-status-{{ $app->id }}">
                        @csrf
                        <select name="status" class="form-select" style="font-size: 0.75rem; padding: 5px 30px 5px 10px;"
                                data-original="{{ $app->status }}"
                                id="select-status-{{ $app->id }}"
                                onchange="confirmStatus(this, '{{ addslashes($app->user->name) }}', {{ $app->id }})">
                            <option value="recebido"   {{ $app->status === 'recebido'   ? 'selected' : '' }}>Recebido</option>
                            <option value="em_analise" {{ $app->status === 'em_analise' ? 'selected' : '' }}>Em Análise</option>
                            <option value="entrevista" {{ $app->status === 'entrevista' ? 'selected' : '' }}>Entrevista</option>
                            <option value="aprovado"   {{ $app->status === 'aprovado'   ? 'selected' : '' }}>Aprovar ✓</option>
                            <option value="rejeitado"  {{ $app->status === 'rejeitado'  ? 'selected' : '' }}>Não Avançou</option>
                        </select>
                    </form>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 2rem 0.5rem; color: var(--clr-text-dim); font-size: 0.75rem; opacity: 0.6;">
                <i class="fa-regular fa-folder-open" style="font-size: 1.5rem; display: block; margin-bottom: 0.5rem;"></i>
                Sem candidatos
            </div>
            @endforelse
        </div>
    </div>
    @endforeach
</div>

@if($applications->count() === 0)
<div class="empty-state animate-fadeInUp mt-xl">
    <div class="empty-state-icon">👥</div>
    <div class="empty-state-title">Nenhum candidato ainda</div>
    <p>Esta vaga ainda não recebeu candidaturas.</p>
</div>
@endif

@endsection

@section('scripts')
<script>
function confirmStatus(select, candidateName, appId) {
    const newStatus = select.value;
    const original  = select.dataset.original;

    let msg = `Alterar status de "${candidateName}" para "${newStatus.replace('_', ' ').toUpperCase()}"?`;
    if (newStatus === 'aprovado') {
        msg += '\n\n⚠️ Isso irá fechar automaticamente esta vaga para novas candidaturas!';
    }

    if (confirm(msg)) {
        document.getElementById('form-status-' + appId).submit();
    } else {
        select.value = original;
    }
}
</script>
@endsection
