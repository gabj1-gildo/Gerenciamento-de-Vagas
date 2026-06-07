@extends('layouts.app')

@section('title', 'Empresas — SyncMatch')

@section('content')
<div class="page-header flex justify-between items-center flex-wrap gap-md">
    <div>
        <h1 class="page-title">
            <i class="fa-regular fa-building text-gradient" style="margin-right: 0.5rem;"></i>
            Empresas
        </h1>
        <p class="page-subtitle">Ecossistema de empresas parceiras do SyncMatch</p>
    </div>
    @if(session('user_role') === 'admin')
        <a href="{{ route('companies.create') }}" class="btn btn-primary" id="btn-nova-empresa">
            <i class="fa-solid fa-plus"></i> Nova Empresa
        </a>
    @endif
</div>

@if(count($companies))
    <div class="grid grid-auto">
        @foreach($companies as $index => $company)
        @php $stagger = 'stagger-' . (($index % 6) + 1); @endphp
        <div class="company-card animate-fadeInUp {{ $stagger }}" id="company-card-{{ $company['id'] }}">
            <div class="flex items-start justify-between gap-md">
                <div class="flex items-center gap-md">
                    <div class="company-avatar">
                        {{ strtoupper(substr($company['name'], 0, 1)) }}
                    </div>
                    <div>
                        <h2 style="font-family: var(--font-display); font-size: 1.0625rem; font-weight: 700; color: var(--clr-text); line-height: 1.2;">
                            {{ $company['name'] }}
                        </h2>
                        @if($company['area'] ?? false)
                            <p class="text-xs text-muted mt-xs">{{ $company['area'] }}</p>
                        @endif
                    </div>
                </div>
                @if($company['city'] ?? false)
                    <span class="badge badge-info" style="flex-shrink: 0;">
                        <i class="fa-solid fa-location-dot"></i>
                        {{ $company['city'] }}
                    </span>
                @endif
            </div>

            <p class="text-sm text-muted" style="line-height: 1.6;">
                {{ Str::limit($company['description'] ?? 'Sem descrição cadastrada.', 130) }}
            </p>

            <div class="flex items-center justify-between gap-sm">
                <div class="company-stat" style="flex: 1;">
                    <span class="company-stat-number">{{ $company['jobs_count'] ?? 0 }}</span>
                    <span class="company-stat-label">Vagas</span>
                </div>
            </div>

            <div class="divider"></div>

            <div class="flex items-center justify-between gap-sm">
                @if($company['cnpj'] ?? false)
                    <span class="text-xs text-dim" style="font-family: monospace;">
                        CNPJ: {{ $company['cnpj'] }}
                    </span>
                @endif
                <div class="flex gap-sm" style="margin-left: auto;">
                    <a href="{{ route('companies.show', $company['id']) }}" class="btn btn-secondary btn-sm" id="btn-ver-empresa-{{ $company['id'] }}">
                        Ver Detalhes <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    @if(session('user_role') === 'admin')
                        <a href="{{ route('companies.edit', $company['id']) }}" class="btn btn-secondary btn-sm" id="btn-editar-empresa-{{ $company['id'] }}">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-md" style="display:flex; justify-content:center;">
        {{ $companies->appends(request()->query())->links() }}
    </div>
@else
    <div class="empty-state animate-fadeInUp">
        <div class="empty-state-icon">🏢</div>
        <div class="empty-state-title">Nenhuma empresa cadastrada</div>
        <p>Ainda não há empresas parceiras no SyncMatch.</p>
        @if(session('user_role') === 'admin')
            <a href="{{ route('companies.create') }}" class="btn btn-primary mt-lg">
                <i class="fa-solid fa-plus"></i> Cadastrar Empresa
            </a>
        @endif
    </div>
@endif
@endsection
