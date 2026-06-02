@extends('layouts.app')

@section('title', 'Nova Vaga — SyncMatch')

@section('content')
<div class="container-sm">
    <div class="page-header">
        <div class="flex items-center gap-sm mb-sm text-sm text-muted">
            <a href="{{ route('jobs.index') }}" style="color:var(--clr-text-muted);">
                <i class="fa-solid fa-briefcase"></i> Vagas
            </a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            <span style="color:var(--clr-text);">Nova Vaga</span>
        </div>
        <h1 class="page-title">
            <i class="fa-solid fa-plus-circle text-gradient" style="margin-right: 0.5rem;"></i>
            Publicar Nova Vaga
        </h1>
        <p class="page-subtitle">Encontre os melhores talentos para a sua equipe</p>
    </div>

    <div class="card animate-fadeInUp" style="border-radius: var(--radius-xl);">
        <div class="card-body">
            <form action="{{ route('jobs.store') }}" method="POST" id="form-nova-vaga">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="company_id">
                        <i class="fa-regular fa-building"></i> Empresa *
                    </label>
                    <select name="company_id" id="company_id" class="form-select" required>
                        <option value="">Selecione a empresa...</option>
                        @foreach($companies as $company)
                            <option value="{{ $company['id'] }}"
                                {{ old('company_id', request('company_id')) == $company['id'] ? 'selected' : '' }}>
                                {{ $company['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="title">
                        <i class="fa-solid fa-briefcase"></i> Título da Vaga *
                    </label>
                    <input type="text" name="title" id="title" class="form-control"
                        placeholder="Ex: Desenvolvedor Full Stack Pleno" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">
                        <i class="fa-solid fa-align-left"></i> Descrição *
                    </label>
                    <textarea name="description" id="description" rows="7" class="form-control"
                        placeholder="Descreva as responsabilidades, requisitos, benefícios e diferenciais da vaga..." required>{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-3" style="gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="type">Tipo de Vaga *</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="estagio"    {{ old('type') === 'estagio'    ? 'selected' : '' }}>Estágio</option>
                            <option value="clt"        {{ old('type') === 'clt'        ? 'selected' : '' }}>CLT</option>
                            <option value="pj"         {{ old('type') === 'pj'         ? 'selected' : '' }}>PJ</option>
                            <option value="temporario" {{ old('type') === 'temporario' ? 'selected' : '' }}>Temporário</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="mode">Modalidade *</label>
                        <select name="mode" id="mode" class="form-select" required>
                            <option value="presencial" {{ old('mode') === 'presencial' ? 'selected' : '' }}>Presencial</option>
                            <option value="hibrido"    {{ old('mode') === 'hibrido'    ? 'selected' : '' }}>Híbrido</option>
                            <option value="remoto"     {{ old('mode') === 'remoto'     ? 'selected' : '' }}>Remoto</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status *</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="aberta"  {{ old('status', 'aberta') === 'aberta'  ? 'selected' : '' }}>Aberta</option>
                            <option value="fechada" {{ old('status') === 'fechada' ? 'selected' : '' }}>Fechada</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="salary_range">
                        <i class="fa-solid fa-sack-dollar"></i> Faixa Salarial / Bolsa
                    </label>
                    <input type="text" name="salary_range" id="salary_range" class="form-control"
                        placeholder="Ex: R$ 3.000 - R$ 5.000 / R$ 1.500 bolsa" value="{{ old('salary_range') }}">
                    <p class="form-hint">Deixe em branco para exibir "A combinar"</p>
                </div>

                <div class="divider"></div>

                <div class="flex justify-end gap-md">
                    <a href="{{ route('jobs.index') }}" class="btn btn-secondary" id="btn-cancelar-vaga">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" id="btn-salvar-vaga">
                        <i class="fa-solid fa-paper-plane"></i> Publicar Vaga
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
