@extends('layouts.app')

@section('title', 'Editar Empresa')

@section('content')
<h1>Editar Empresa</h1>

<form action="{{ route('companies.update') }}" method="POST">
    @csrf
   
    <input type="hidden" name="id" value="{{ old('name', $company->id) }}">
    <div class="mb-3">
        <label for="name" class="form-label">Nome da empresa *</label>
        <input type="text" name="name" id="name" class="form-control"
               value="{{ old('name', $company->name) }}" required>
    </div>

    <div class="mb-3">
        <label for="cnpj" class="form-label">CNPJ</label>
        <input type="text" name="cnpj" id="cnpj" class="form-control"
               value="{{ old('cnpj', $company->cnpj) }}">
    </div>

    <div class="mb-3">
        <label for="area" class="form-label">Área de atuação</label>
        <input type="text" name="area" id="area" class="form-control"
               value="{{ old('area', $company->area) }}">
    </div>

    <div class="mb-3">
        <label for="city" class="form-label">Cidade</label>
        <input type="text" name="city" id="city" class="form-control"
               value="{{ old('city', $company->city) }}">
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Descrição</label>
        <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $company->description) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="{{ route('companies.show', $company->id) }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection
