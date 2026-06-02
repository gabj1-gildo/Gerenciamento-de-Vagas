@extends('layouts.app')

@section('title', 'Meu Perfil Profissional — SyncMatch')

@section('content')
<div class="container-sm">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fa-solid fa-user-circle text-gradient" style="margin-right: 0.5rem;"></i>
            Meu Perfil Profissional
        </h1>
        <p class="page-subtitle">Complete suas informações para se candidatar às vagas disponíveis</p>
    </div>

    {{-- Status do Currículo --}}
    @if($profile->resume_path)
        <div class="alert alert-success animate-fadeInUp mb-xl">
            <i class="fa-solid fa-circle-check"></i>
            <div>
                <strong>Currículo PDF cadastrado!</strong> Você está pronto para se candidatar.
                <div class="mt-sm">
                    <a href="{{ asset('storage/' . $profile->resume_path) }}" target="_blank" class="btn btn-success btn-sm" id="btn-ver-curriculo">
                        <i class="fa-solid fa-file-pdf"></i> Visualizar PDF
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning animate-fadeInUp mb-xl">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div>
                <strong>Currículo pendente</strong> — Faça upload do seu PDF para se candidatar às vagas.
            </div>
        </div>
    @endif

    <div class="card animate-fadeInUp" style="border-radius: var(--radius-xl);">
        <div class="card-body">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="form-perfil">
                @csrf

                {{-- Contato --}}
                <div class="form-group">
                    <label class="form-label" for="phone">
                        <i class="fa-solid fa-phone"></i> Telefone de Contato
                    </label>
                    <input type="text" name="phone" id="phone" class="form-control"
                        placeholder="(XX) XXXXX-XXXX" value="{{ old('phone', $profile->phone) }}">
                </div>

                {{-- Bio --}}
                <div class="form-group">
                    <label class="form-label" for="bio">
                        <i class="fa-solid fa-user"></i> Resumo Profissional
                    </label>
                    <textarea name="bio" id="bio" rows="4" class="form-control"
                        placeholder="Conte sobre sua trajetória, objetivos e o que te diferencia...">{{ old('bio', $profile->bio) }}</textarea>
                </div>

                {{-- Skills --}}
                <div class="form-group">
                    <label class="form-label" for="skills">
                        <i class="fa-solid fa-tags"></i> Competências e Habilidades
                    </label>
                    <input type="text" name="skills" id="skills" class="form-control"
                        placeholder="Ex: PHP, Laravel, JavaScript, React, Git, MySQL"
                        value="{{ old('skills', $profile->skills) }}">
                    <p class="form-hint">Separe as competências com vírgulas</p>

                    {{-- Preview das skills --}}
                    @if($profile->skills)
                    <div class="flex flex-wrap gap-xs mt-sm" id="skills-preview">
                        @foreach(explode(',', $profile->skills) as $skill)
                            @if(trim($skill))
                                <span class="badge badge-primary">{{ trim($skill) }}</span>
                            @endif
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Educação e Experiência --}}
                <div class="grid grid-2 gap-md">
                    <div class="form-group">
                        <label class="form-label" for="education">
                            <i class="fa-solid fa-graduation-cap"></i> Educação / Formação
                        </label>
                        <textarea name="education" id="education" rows="5" class="form-control"
                            placeholder="Ex: Bacharelado em Ciência da Computação&#10;Universidade X — 2022 até Atual">{{ old('education', $profile->education) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="experience">
                            <i class="fa-solid fa-briefcase"></i> Experiência Profissional
                        </label>
                        <textarea name="experience" id="experience" rows="5" class="form-control"
                            placeholder="Ex: Desenvolvedor Estagiário&#10;Empresa Y — 6 meses&#10;Desenvolvendo sistemas web...">{{ old('experience', $profile->experience) }}</textarea>
                    </div>
                </div>

                {{-- Upload Currículo --}}
                <div class="form-group">
                    <label class="form-label" for="resume">
                        <i class="fa-solid fa-file-pdf" style="color: #ef4444;"></i>
                        {{ $profile->resume_path ? 'Atualizar Currículo (PDF)' : 'Upload Currículo (PDF) *' }}
                    </label>
                    <input type="file" name="resume" id="resume" class="form-control" accept=".pdf">
                    <p class="form-hint">
                        Apenas arquivos PDF. Tamanho máximo: 10MB.
                        {{ $profile->resume_path ? 'Deixe em branco para manter o atual.' : '' }}
                    </p>
                </div>

                <div class="divider"></div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-salvar-perfil">
                    <i class="fa-solid fa-floppy-disk"></i> Salvar Perfil
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
