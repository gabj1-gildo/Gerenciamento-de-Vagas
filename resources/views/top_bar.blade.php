<div class="row mb-3 align-items-center">
    <div class="col">
        <a href="{{ route('home') }}">
            
        </a>
    </div>
    <div class="col text-center">
        <h1 class="m-0">Empresas</h1>
    </div>
    <div class="col">
        <div class="d-flex justify-content-end align-items-center">
            <span class="me-3"><i class="fa-solid fa-user fa-lg text-secondary me-3"></i>
                 {{ session('user_nome') }}</span>
            <a href="{{ route('logout') }}" class="btn btn-secondary">Sair</a>
        </div>
    </div>
</div>