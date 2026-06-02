@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col">
            @include('top_bar')

            @if(count($companies) == 0)
            <div class="row mt-5">
                <div class="col text-center">
                    <h3 class="text-secondary">Não possui Empresas cadastradas</h3>
                    <a href="{{ route('nova_nota') }}" class="btn btn-primary mt-3">Cadastrar empresa</a>
                </div>
            </div>

            @else

            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('nova_nota') }}" class="btn btn-primary">Nova Nota</a>
            </div>

            @if(session('success'))
            <div class="alert alert-success text-center">
                {{session('success')}}
            </div>
            @endif

            <div class="row">
                @foreach($companies as $companie)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $companie['titulo'] }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($companie['conteudo'], 100) }}</p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">Última edição: {{ $companie['updated_at'] }}</small>
                            <a href="{{ route('editarNota', ['id' => Crypt::encrypt($companie['id']) ]) }}">Editar</a>
                            <a href="{{ route('deletarNota', ['id' => Crypt::encrypt($companie['id']) ]) }}" class="text-danger">Excluir</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @endif
        </div>
    </div>
</div>
@endsection