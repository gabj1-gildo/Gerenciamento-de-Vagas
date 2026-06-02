@extends('layouts.app')

@section('title', 'Editar Vaga — SyncMatch')

@section('content')
<div class="container-sm">
    @foreach($jobs as $job)
    <div class="page-header">
        <div class="flex items-center gap-sm mb-sm text-sm text-muted">
            <a href="{{ route('jobs.index') }}" style="color:var(--clr-text-muted);">
                <i class="fa-solid fa-briefcase"></i> Vagas
            </a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            <a href="{{ route('jobs.show', $job['id']) }}" style="color:var(--clr-text-muted);">{{ $job['title'] }}</a>
            <i class="fa-solid fa-chevron-right" style="font-size:0.6rem;"></i>
            <span style="color:var(--clr-text);">Editar</span>
        </div>
        <h1 class="page-title">
            <i class="fa-solid fa-pen-to-square text-gradient" style="margin-right: 0.5rem;"></i>
            Editar Vaga
        </h1>
    </div>

    <div class="card animate-fadeInUp" style="border-radius: var(--radius-xl);">
        <div class="card-body">
            <form action="{{ route('jobs.update') }}" method="POST" id="form-editar-vaga-{{ $job['id'] }}">
                @csrf
                <input type="hidden" name="id" value="{{ $job['id'] }}">

                <div class="form-group">
                    <label class="form-label" for="company_id">
                        <i class="fa-regular fa-building"></i> Empresa *
                    </label>
                    <select name="company_id" id="company_id" class="form-select" required>
                        @foreach($companies as $company)
                            <option value="{{ $company['id'] }}"
                                {{ $job['company_id'] == $company['id'] ? 'selected' : '' }}>
                                {{ $company['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="title">Título da Vaga *</label>
                    <input type="text" name="title" id="title" class="form-control"
                        value="{{ old('title', $job['title']) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Descrição *</label>
                    <textarea name="description" id="description" rows="7" class="form-control" required>{{ old('description', $job['description']) }}</textarea>
                </div>

                <div class="grid grid-3" style="gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label" for="type">Tipo de Vaga *</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="estagio"    {{ old('type', $job['type']) === 'estagio'    ? 'selected' : '' }}>Estágio</option>
                            <option value="clt"        {{ old('type', $job['type']) === 'clt'        ? 'selected' : '' }}>CLT</option>
                            <option value="pj"         {{ old('type', $job['type']) === 'pj'         ? 'selected' : '' }}>PJ</option>
                            <option value="temporario" {{ old('type', $job['type']) === 'temporario' ? 'selected' : '' }}>Temporário</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="mode">Modalidade *</label>
                        <select name="mode" id="mode" class="form-select" required>
                            <option value="presencial" {{ old('mode', $job['mode']) === 'presencial' ? 'selected' : '' }}>Presencial</option>
                            <option value="hibrido"    {{ old('mode', $job['mode']) === 'hibrido'    ? 'selected' : '' }}>Híbrido</option>
                            <option value="remoto"     {{ old('mode', $job['mode']) === 'remoto'     ? 'selected' : '' }}>Remoto</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Status *</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="aberta"  {{ old('status', $job['status']) === 'aberta'  ? 'selected' : '' }}>Aberta</option>
                            <option value="fechada" {{ old('status', $job['status']) === 'fechada' ? 'selected' : '' }}>Fechada</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="salary_range">
                        <i class="fa-solid fa-sack-dollar"></i> Faixa Salarial / Bolsa
                    </label>
                    <input type="text" name="salary_range" id="salary_range" class="form-control"
                        placeholder="Ex: R$ 3.000 - R$ 5.000"
                        value="{{ old('salary_range', $job['salary_range']) }}">
                </div>

                <div class="divider"></div>
                <div class="flex justify-end gap-md">
                    <a href="{{ route('jobs.show', $job['id']) }}" class="btn btn-secondary" id="btn-cancelar-edicao">
                        <i class="fa-solid fa-xmark"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary" id="btn-atualizar-vaga">
                        <i class="fa-solid fa-floppy-disk"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection
