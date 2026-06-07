@extends('layouts.app')

@section('title', 'Vagas Disponíveis — SyncMatch')

@section('content')
<div class="page-header flex justify-between items-center flex-wrap gap-md">
    <div>
        <h1 class="page-title">
            <i class="fa-solid fa-briefcase text-gradient" style="margin-right: 0.5rem;"></i>
            Vagas Disponíveis
        </h1>
        <p class="page-subtitle">Encontre a oportunidade certa para o próximo passo da sua carreira</p>
    </div>
    @if(session('user_role') === 'recruiter' && session('user_approved'))
        <a href="{{ route('jobs.create') }}" class="btn btn-primary" id="btn-nova-vaga">
            <i class="fa-solid fa-plus"></i> Nova Vaga
        </a>
    @endif
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('jobs.index') }}" id="filter-form">
    <div class="filter-bar">
        <div class="search-wrapper" style="flex: 1; min-width: 220px;">
            <i class="fa-solid fa-magnifying-glass search-icon"></i>
            <input type="text" class="form-control" name="search"
                placeholder="Buscar vaga, empresa..."
                value="{{ request('search') }}"
                id="search-input">
        </div>

        <select name="type" class="form-select" style="width: auto; min-width: 160px;" id="filter-type"
            onchange="this.form.submit()">
            <option value="">Tipo de vaga</option>
            <option value="estagio"    {{ request('type') === 'estagio'    ? 'selected' : '' }}>Estágio</option>
            <option value="clt"        {{ request('type') === 'clt'        ? 'selected' : '' }}>CLT</option>
            <option value="pj"         {{ request('type') === 'pj'         ? 'selected' : '' }}>PJ</option>
            <option value="temporario" {{ request('type') === 'temporario' ? 'selected' : '' }}>Temporário</option>
        </select>

        <select name="mode" class="form-select" style="width: auto; min-width: 160px;" id="filter-mode"
            onchange="this.form.submit()">
            <option value="">Modalidade</option>
            <option value="presencial" {{ request('mode') === 'presencial' ? 'selected' : '' }}>Presencial</option>
            <option value="remoto"     {{ request('mode') === 'remoto'     ? 'selected' : '' }}>Remoto</option>
            <option value="hibrido"    {{ request('mode') === 'hibrido'    ? 'selected' : '' }}>Híbrido</option>
        </select>

        @if(request('search') || request('type') || request('mode'))
            <a href="{{ route('jobs.index') }}" class="btn btn-secondary btn-sm" id="btn-limpar-filtros">
                <i class="fa-solid fa-xmark"></i> Limpar
            </a>
        @endif

        <button type="submit" class="btn btn-primary btn-sm" id="btn-buscar">
            <i class="fa-solid fa-search"></i> Buscar
        </button>
    </div>
</form>

{{-- Grid de Vagas --}}
@if(count($jobs) > 0)
    <div class="grid grid-auto">
        @foreach($jobs as $index => $job)
        @php
            $typeLabels = ['estagio' => 'Estágio', 'clt' => 'CLT', 'pj' => 'PJ', 'temporario' => 'Temporário'];
            $modeLabels = ['presencial' => 'Presencial', 'remoto' => 'Remoto', 'hibrido' => 'Híbrido'];
            $typeColors = ['estagio' => 'badge-purple', 'clt' => 'badge-primary', 'pj' => 'badge-info', 'temporario' => 'badge-warning'];
            $modeColors = ['presencial' => 'badge-success', 'remoto' => 'badge-pink', 'hibrido' => 'badge-info'];
            $stagger = 'stagger-' . (($index % 6) + 1);
        @endphp
        <a href="{{ route('jobs.show', $job['id']) }}" class="job-card animate-fadeInUp {{ $stagger }}" id="job-card-{{ $job['id'] }}">

            <div class="flex items-center justify-between gap-sm">
                <span class="job-card-company">
                    <i class="fa-regular fa-building" style="color: var(--clr-primary-400);"></i>
                    {{ $job['company']['name'] ?? '—' }}
                </span>
                @if($job['status'] === 'fechada')
                    <span class="badge badge-danger"><i class="fa-solid fa-lock"></i> Fechada</span>
                @else
                    <span class="badge badge-success"><i class="fa-solid fa-circle" style="font-size: 6px;"></i> Aberta</span>
                @endif
            </div>

            <h2 class="job-card-title">{{ $job['title'] }}</h2>

            @if($job['salary_range'])
                <div class="flex items-center gap-xs text-sm" style="color: var(--clr-primary-400); font-weight: 600;">
                    <i class="fa-solid fa-sack-dollar"></i>
                    {{ $job['salary_range'] }}
                </div>
            @endif

            <div class="job-card-tags">
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

            <div class="job-card-footer">
                <span class="text-xs text-dim">
                    <i class="fa-regular fa-clock"></i>
                    {{ \Carbon\Carbon::parse($job['created_at'])->diffForHumans() }}
                </span>
                <span class="btn btn-primary btn-sm" style="pointer-events: none;">
                    Ver detalhes <i class="fa-solid fa-arrow-right"></i>
                </span>
            </div>
        </a>
        @endforeach
    </div>

    <p class="text-center text-muted mt-xl text-sm">
        <i class="fa-solid fa-list-check"></i>
        Mostrando <strong>{{ $jobs->count() }}</strong> de <strong>{{ $jobs->total() }}</strong> {{ $jobs->total() === 1 ? 'vaga' : 'vagas' }}
    </p>

    <div class="mt-md" style="display:flex; justify-content:center;">
        {{ $jobs->appends(request()->query())->links() }}
    </div>

@else
    <div class="empty-state animate-fadeInUp">
        <div class="empty-state-icon">🔍</div>
        <div class="empty-state-title">Nenhuma vaga encontrada</div>
        <p>Tente ajustar os filtros ou verifique novamente mais tarde.</p>
        @if(request('search') || request('type') || request('mode'))
            <a href="{{ route('jobs.index') }}" class="btn btn-secondary mt-lg">
                <i class="fa-solid fa-xmark"></i> Limpar filtros
            </a>
        @endif
    </div>
@endif
@endsection
