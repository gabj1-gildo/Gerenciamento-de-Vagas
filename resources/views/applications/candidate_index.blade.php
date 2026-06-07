@extends('layouts.app')

@section('title', 'Minhas Candidaturas — SyncMatch')

@section('content')
<div class="page-header">
    <h1 class="page-title">
        <i class="fa-solid fa-file-lines text-gradient" style="margin-right: 0.5rem;"></i>
        Minhas Candidaturas
    </h1>
    <p class="page-subtitle">Acompanhe o status de cada candidatura enviada</p>
</div>

@php
    $statusConfig = [
        'recebido'   => ['label' => 'Recebido',    'icon' => 'fa-inbox',       'dot' => 'status-dot-recebido',   'badge' => 'badge-info'],
        'em_analise' => ['label' => 'Em Análise',  'icon' => 'fa-magnifying-glass', 'dot' => 'status-dot-em_analise', 'badge' => 'badge-warning'],
        'entrevista' => ['label' => 'Entrevista',  'icon' => 'fa-calendar-check', 'dot' => 'status-dot-entrevista', 'badge' => 'badge-purple'],
        'aprovado'   => ['label' => 'Aprovado',    'icon' => 'fa-trophy',      'dot' => 'status-dot-aprovado',   'badge' => 'badge-success'],
        'rejeitado'  => ['label' => 'Não Avançou', 'icon' => 'fa-circle-xmark','dot' => 'status-dot-rejeitado',  'badge' => 'badge-danger'],
    ];
@endphp

@if($applications->count())

    {{-- Resumo estatístico --}}
    @php
        $counts = $applications->groupBy('status')->map->count();
    @endphp
    <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); margin-bottom: 2rem;">
        @foreach($statusConfig as $key => $cfg)
            @if(($counts[$key] ?? 0) > 0)
            <div style="background: var(--glass-bg); border: 1px solid var(--glass-border); border-radius: var(--radius-lg); padding: 1.25rem; text-align: center;">
                <div style="font-family: var(--font-display); font-size: 1.75rem; font-weight: 800; color: var(--clr-text);">
                    {{ $counts[$key] ?? 0 }}
                </div>
                <div class="text-xs text-muted mt-xs">{{ $cfg['label'] }}</div>
                <div class="status-dot {{ $cfg['dot'] }}" style="margin: 6px auto 0; display: block; width: 10px; height: 10px;"></div>
            </div>
            @endif
        @endforeach
    </div>

    {{-- Lista de Candidaturas --}}
    <div style="display: flex; flex-direction: column; gap: var(--space-md);">
        @foreach($applications as $index => $application)
        @php
            $status = $application->status;
            $cfg    = $statusConfig[$status] ?? ['label' => ucfirst($status), 'icon' => 'fa-circle', 'dot' => '', 'badge' => 'badge-primary'];
        @endphp
        <div class="card animate-fadeInUp stagger-{{ ($index % 6) + 1 }}" id="application-{{ $application->id }}" style="border-radius: var(--radius-lg); overflow: hidden;">
            <div style="display: grid; grid-template-columns: 1fr auto; gap: 1rem; padding: 1.5rem; align-items: start;">

                {{-- Info Principal --}}
                <div>
                    <div class="flex items-center gap-sm mb-sm flex-wrap">
                        <span class="badge {{ $cfg['badge'] }}">
                            <i class="fa-solid {{ $cfg['icon'] }}"></i>
                            {{ $cfg['label'] }}
                        </span>
                        @if($status === 'aprovado')
                            <span class="badge badge-success" style="animation: pulse-glow 2s infinite;">
                                <i class="fa-solid fa-star"></i> Parabéns!
                            </span>
                        @endif
                    </div>

                    <h2 style="font-family: var(--font-display); font-size: 1.125rem; font-weight: 700; color: var(--clr-text); margin-bottom: 4px;">
                        <a href="{{ route('jobs.show', $application->job->id) }}" style="color: inherit; text-decoration: none;" id="link-vaga-{{ $application->job->id }}">
                            {{ $application->job->title }}
                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.7rem; color: var(--clr-primary-400);"></i>
                        </a>
                    </h2>

                    <div class="flex items-center gap-sm text-sm text-muted flex-wrap">
                        <span>
                            <i class="fa-regular fa-building" style="color: var(--clr-primary-400);"></i>
                            {{ $application->job->company->name ?? '—' }}
                        </span>
                        @if($application->job->company->city ?? false)
                            <span>
                                <i class="fa-solid fa-location-dot" style="color: var(--clr-accent-400);"></i>
                                {{ $application->job->company->city }}
                            </span>
                        @endif
                        <span>
                            <i class="fa-regular fa-clock"></i>
                            Enviada {{ \Carbon\Carbon::parse($application->created_at)->diffForHumans() }}
                        </span>
                    </div>
                </div>

                {{-- Indicador visual de progresso --}}
                <div style="display: flex; flex-direction: column; align-items: center; gap: 3px; padding-top: 4px;">
                    @foreach(['recebido', 'em_analise', 'entrevista', 'aprovado'] as $step)
                    @php
                        $stepOrder = ['recebido'=>0,'em_analise'=>1,'entrevista'=>2,'aprovado'=>3,'rejeitado'=>-1];
                        $currentOrder = $stepOrder[$status] ?? 0;
                        $thisOrder    = $stepOrder[$step] ?? 0;
                        $isActive     = $currentOrder >= $thisOrder && $status !== 'rejeitado';
                    @endphp
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: {{ $isActive ? 'var(--clr-primary-500)' : 'var(--glass-border)' }}; transition: all 0.3s;"></div>
                    @if(!$loop->last)
                        <div style="width: 2px; height: 16px; background: {{ $isActive ? 'rgba(99,102,241,0.4)' : 'var(--glass-border)' }};"></div>
                    @endif
                    @endforeach
                    @if($status === 'rejeitado')
                        <div style="width: 8px; height: 8px; border-radius: 50%; background: var(--clr-danger); margin-top: 3px;"></div>
                    @endif
                </div>
            </div>

            {{-- Carta de Apresentação (colapsável) --}}
            @if($application->cover_letter)
            <div style="border-top: 1px solid var(--glass-border); padding: 1rem 1.5rem;">
                <details>
                    <summary style="cursor: pointer; font-size: 0.8125rem; font-weight: 600; color: var(--clr-text-muted); list-style: none; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fa-solid fa-envelope-open-text" style="color: var(--clr-primary-400);"></i>
                        Ver carta de apresentação
                    </summary>
                    <p style="margin-top: 0.75rem; color: var(--clr-text-muted); font-size: 0.875rem; line-height: 1.7; white-space: pre-line;">
                        {{ $application->cover_letter }}
                    </p>
                </details>
            </div>
            @endif

            @if(!in_array($status, ['aprovado', 'rejeitado']))
            <div style="border-top: 1px solid var(--glass-border); padding: 1rem 1.5rem; display: flex; justify-content: flex-end;">
                <form action="{{ route('applications.cancel', $application->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja desistir desta vaga?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-secondary btn-sm" style="color: var(--clr-danger); border-color: var(--clr-danger);">
                        <i class="fa-solid fa-trash"></i> Desistir da Vaga
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>

@else
    <div class="empty-state animate-fadeInUp">
        <div class="empty-state-icon">📭</div>
        <div class="empty-state-title">Nenhuma candidatura ainda</div>
        <p>Você ainda não se candidatou a nenhuma vaga. Comece explorando as oportunidades!</p>
        <a href="{{ route('jobs.index') }}" class="btn btn-primary mt-lg" id="btn-explorar-vagas">
            <i class="fa-solid fa-search"></i> Explorar Vagas
        </a>
    </div>
@endif
@endsection
