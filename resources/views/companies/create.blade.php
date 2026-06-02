@extends('layouts.app')

@section('title', 'Nova Empresa — SyncMatch')

@section('content')
<div class="container-sm">
    <div class="page-header">
        <div class="flex items-center gap-sm mb-sm text-sm text-muted">
            <a href="{{ route('companies.index') }}" style="color:var(--clr-text-muted);">
                <i class="fa-regular fa-building"></i> Empresas
            </a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            <span style="color:var(--clr-text);">Nova Empresa</span>
        </div>
        <h1 class="page-title">
            <i class="fa-solid fa-building-circle-arrow-right text-gradient" style="margin-right: 0.5rem;"></i>
            Cadastrar Nova Empresa
        </h1>
    </div>

    <div class="card animate-fadeInUp" style="border-radius: var(--radius-xl);">
        <div class="card-body">
            <form action="{{ route('companies.store') }}" method="POST" id="form-nova-empresa">
                @csrf

                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="name">
                            <i class="fa-regular fa-building"></i> Nome da Empresa *
                        </label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Ex: Wamag Corp" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cnpj">CNPJ</label>
                        <input type="text" name="cnpj" id="cnpj" class="form-control"
                            placeholder="00.000.000/0000-00" value="{{ old('cnpj') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="city">
                            <i class="fa-solid fa-location-dot"></i> Cidade Sede
                        </label>
                        <input type="text" name="city" id="city" class="form-control"
                            placeholder="Ex: São Paulo, SP" value="{{ old('city') }}">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="area">
                            <i class="fa-solid fa-tags"></i> Área de Atuação
                        </label>
                        <input type="text" name="area" id="area" class="form-control"
                            placeholder="Ex: Tecnologia, Indústria, Varejo" value="{{ old('area') }}">
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label class="form-label" for="description">Descrição da Empresa</label>
                        <textarea name="description" id="description" rows="4" class="form-control"
                            placeholder="Descreva a empresa, sua missão e cultura...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="flex justify-end gap-md">
                    <a href="{{ route('companies.index') }}" class="btn btn-secondary" id="btn-cancelar-empresa">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" id="btn-salvar-empresa">
                        <i class="fa-solid fa-building-circle-check"></i> Cadastrar Empresa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
