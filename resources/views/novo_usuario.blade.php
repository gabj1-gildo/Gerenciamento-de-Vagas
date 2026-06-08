<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Crie sua conta no SyncMatch e conecte-se às melhores vagas.">
    <title>Criar Conta — SyncMatch</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 2rem 1rem 4rem;
        }

        .register-card {
            width: 100%;
            max-width: 640px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-xl);
            padding: 2.5rem;
            backdrop-filter: blur(20px);
        }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .form-grid-full { grid-column: 1 / -1; }

        .section-divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .section-divider::before, .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--glass-border);
        }

        .section-divider span {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--clr-text-dim);
            white-space: nowrap;
        }

        .company-fields {
            background: rgba(99,102,241,0.05);
            border: 1px solid rgba(99,102,241,0.15);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            display: none;
        }

        .company-fields.show { display: block; animation: slideDown 0.3s ease; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--clr-text-dim);
            font-size: 0.875rem;
            pointer-events: none;
        }

        .input-wrapper .form-control { padding-left: 40px; }

        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--clr-text-dim);
            cursor: pointer;
            background: none;
            border: none;
            transition: var(--transition-fast);
        }

        .toggle-pw:hover { color: var(--clr-primary-400); }

        .error-msg { font-size: 0.75rem; color: #fca5a5; margin-top: 4px; }

        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .role-cards-grid { grid-template-columns: 1fr 1fr; }
            .register-card { padding: 1.5rem; }
        }
    </style>
</head>
<body>

{{-- Header simples --}}
<div style="width: 100%; max-width: 640px; display: flex; justify-content: space-between; align-items: center; padding: 0 0 1.5rem;">
    <div style="display: flex; flex-direction: column; align-items: flex-end; line-height: 0.9;">
        <a href="{{ url('/') }}" style="font-family: var(--font-display); font-size: 1.5rem; font-weight: 800; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-decoration: none;">
            SyncMatch
        </a>
        <span style="font-size: 0.55rem; font-weight: 700; color: var(--clr-text-dim); letter-spacing: 0.5px; margin-top: 2px;">by ApexSync</span>
    </div>
    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">
        <i class="fa-solid fa-arrow-right-to-bracket"></i> Já tenho conta
    </a>
</div>

<div class="register-card animate-fadeInUp">

    <div class="text-center mb-xl">
        <h1 class="page-title" style="font-size: 1.75rem;">Criar sua conta</h1>
        <p class="text-muted mt-sm">Junte-se à rede de vagas e recrutamento do SyncMatch</p>
    </div>

    {{-- Toast de erros --}}
    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showToast('error', {!! json_encode($errors->first()) !!});
            });
        </script>
    @endif

    <form method="POST" action="{{ route('salvar_usuario') }}" id="register-form">
        @csrf

        {{-- ── STEP 1: Tipo de Conta ──────────────────────── --}}
        <div class="section-divider"><span>1 — Tipo de Conta</span></div>

        <div class="role-cards-grid" id="role-cards" style="grid-template-columns: repeat(3, 1fr);">

            <div class="role-card-option">
                <input type="radio" name="role" id="role-student" value="student"
                    {{ old('role', 'student') === 'student' ? 'checked' : '' }}>
                <label class="role-card-label" for="role-student">
                    <span class="role-card-icon">🎓</span>
                    <span class="role-card-title">Candidato</span>
                    <span class="role-card-desc">Busco vagas e oportunidades</span>
                </label>
            </div>

            <div class="role-card-option">
                <input type="radio" name="role" id="role-recruiter" value="recruiter_existing"
                    {{ old('role') === 'recruiter_existing' ? 'checked' : '' }}>
                <label class="role-card-label" for="role-recruiter">
                    <span class="role-card-icon">🤝</span>
                    <span class="role-card-title">Recrutador</span>
                    <span class="role-card-desc">Faço parte de uma empresa</span>
                </label>
            </div>

            <div class="role-card-option">
                <input type="radio" name="role" id="role-company" value="recruiter_new"
                    {{ old('role') === 'recruiter_new' ? 'checked' : '' }}>
                <label class="role-card-label" for="role-company">
                    <span class="role-card-icon">🏢</span>
                    <span class="role-card-title">Empresa</span>
                    <span class="role-card-desc">Registro minha empresa</span>
                </label>
            </div>

        </div>

        {{-- ── STEP 2: Dados Pessoais ─────────────────────── --}}
        <div class="section-divider mt-lg"><span>2 — Dados Pessoais</span></div>

        <div class="form-grid">
            <div class="form-group form-grid-full">
                <label class="form-label" for="nome">Nome Completo *</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" class="form-control" id="nome" name="nome"
                        placeholder="Seu nome completo" value="{{ old('nome') }}" required>
                </div>
                @error('nome')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group form-grid-full">
                <label class="form-label" for="email">E-mail *</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="seu@email.com" value="{{ old('email') }}" required>
                </div>
                @error('email')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="birth_date">Data de Nascimento *</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-calendar input-icon"></i>
                    <input type="date" class="form-control" id="birth_date" name="birth_date"
                        value="{{ old('birth_date') }}" required>
                </div>
                @error('birth_date')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="gender">Sexo *</label>
                <select name="gender" id="gender" class="form-select" required>
                    <option value="">Selecione...</option>
                    <option value="masculino" {{ old('gender') === 'masculino' ? 'selected' : '' }}>Masculino</option>
                    <option value="feminino" {{ old('gender') === 'feminino' ? 'selected' : '' }}>Feminino</option>
                    <option value="outro" {{ old('gender') === 'outro' ? 'selected' : '' }}>Outro</option>
                </select>
                @error('gender')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group form-grid-full" id="social-name-section" style="display: none;">
                <label class="form-label" for="social_name">Nome Social</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-user-tag input-icon"></i>
                    <input type="text" class="form-control" id="social_name" name="social_name"
                        placeholder="Como prefere ser chamado" value="{{ old('social_name') }}">
                </div>
                @error('social_name')<div class="error-msg">{{ $message }}</div>@enderror
                <p class="form-hint" style="font-size: 0.75rem; color: var(--clr-text-muted); margin-top: 4px;">
                    <i class="fa-solid fa-circle-info"></i> Deixe em branco se preferir usar seu nome completo.
                </p>
            </div>


            <div class="form-group">
                <label class="form-label" for="senha">Senha *</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="senha" name="senha"
                        placeholder="Mínimo 6 caracteres" required>
                    <button type="button" class="toggle-pw" id="toggle-senha">
                        <i class="fa-regular fa-eye" id="icon-senha"></i>
                    </button>
                </div>
                @error('senha')<div class="error-msg">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="senha_confirmation">Confirmar Senha *</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="senha_confirmation" name="senha_confirmation"
                        placeholder="Repita a senha" required>
                    <button type="button" class="toggle-pw" id="toggle-confirm">
                        <i class="fa-regular fa-eye" id="icon-confirm"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Seletor de Empresa Existente ───────────────── --}}
        <div id="existing-company-section" style="display:none;">
            <div class="section-divider"><span>3 — Selecione sua Empresa</span></div>
            <div class="form-group">
                <label class="form-label" for="company_id">
                    <i class="fa-regular fa-building"></i> Empresa *
                </label>
                <select name="company_id" id="company_id" class="form-select">
                    <option value="">Selecione a empresa...</option>
                    @foreach($companies as $company)
                        <option value="{{ $company['id'] }}" {{ old('company_id') == $company['id'] ? 'selected' : '' }}>
                            {{ $company['name'] }} {{ isset($company['city']) ? '— ' . $company['city'] : '' }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')<div class="error-msg">{{ $message }}</div>@enderror
                <p class="form-hint">
                    <i class="fa-solid fa-circle-info"></i>
                    Após o cadastro, aguarde a aprovação do responsável pela empresa.
                </p>
            </div>
        </div>

        {{-- ── Formulário de Nova Empresa ──────────────────── --}}
        <div id="new-company-section" class="company-fields">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <span style="font-size: 1.5rem;">🏢</span>
                <div>
                    <div style="font-weight: 700; font-size: 0.9375rem; color: var(--clr-text);">Dados da sua Empresa</div>
                    <div style="font-size: 0.8125rem; color: var(--clr-text-muted);">Você será registrado como dono desta empresa</div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group form-grid-full">
                    <label class="form-label" for="company_name">Nome da Empresa *</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-building input-icon"></i>
                        <input type="text" class="form-control" id="company_name" name="company_name"
                            placeholder="Ex: Wamag Corp" value="{{ old('company_name') }}">
                    </div>
                    @error('company_name')<div class="error-msg">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="company_cnpj">CNPJ *</label>
                    <input type="text" class="form-control" id="company_cnpj" name="company_cnpj"
                        placeholder="00.000.000/0000-00" value="{{ old('company_cnpj') }}">
                    @error('company_cnpj')<div class="error-msg">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="company_city">Cidade Sede *</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-location-dot input-icon"></i>
                        <input type="text" class="form-control" id="company_city" name="company_city"
                            placeholder="Ex: São Paulo" value="{{ old('company_city') }}">
                    </div>
                    @error('company_city')<div class="error-msg">{{ $message }}</div>@enderror
                </div>

                <div class="form-group form-grid-full">
                    <label class="form-label" for="company_area">Área de Atuação</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-tags input-icon"></i>
                        <input type="text" class="form-control" id="company_area" name="company_area"
                            placeholder="Ex: Tecnologia, Indústria" value="{{ old('company_area') }}">
                    </div>
                </div>

                <div class="form-group form-grid-full">
                    <label class="form-label" for="company_description">Descrição da Empresa</label>
                    <textarea class="form-control" id="company_description" name="company_description"
                        rows="3" placeholder="Descreva brevemente a empresa...">{{ old('company_description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Botão Submit --}}
        <div class="mt-xl">
            <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn-criar-conta">
                <i class="fa-solid fa-user-plus"></i>
                Criar Conta
            </button>
        </div>
    </form>

    <div class="divider"></div>
    <p class="text-center text-sm text-muted">
        Já possui conta?
        <a href="{{ route('login') }}" id="link-login" style="font-weight: 600;">Entrar no SyncMatch</a>
    </p>
</div>

<script>
    function updateFormFields() {
        const role = document.querySelector('input[name="role"]:checked')?.value;
        const existingSection  = document.getElementById('existing-company-section');
        const newSection       = document.getElementById('new-company-section');
        const companyId        = document.getElementById('company_id');
        const companyName      = document.getElementById('company_name');
        const companyCnpj      = document.getElementById('company_cnpj');
        const companyCity      = document.getElementById('company_city');

        // Reset todos
        existingSection.style.display = 'none';
        newSection.classList.remove('show');
        companyId.removeAttribute('required');
        companyName.removeAttribute('required');
        companyCnpj.removeAttribute('required');
        companyCity.removeAttribute('required');

        if (role === 'recruiter_existing') {
            existingSection.style.display = 'block';
            companyId.setAttribute('required', '');
        } else if (role === 'recruiter_new') {
            newSection.classList.add('show');
            companyName.setAttribute('required', '');
            companyCnpj.setAttribute('required', '');
            companyCity.setAttribute('required', '');
        }
    }

    function updateSocialNameVisibility() {
        const genderSelect = document.getElementById('gender');
        const socialNameSection = document.getElementById('social-name-section');
        if (genderSelect.value === 'outro') {
            socialNameSection.style.display = 'block';
            socialNameSection.style.animation = 'slideDown 0.3s ease';
        } else {
            socialNameSection.style.display = 'none';
        }
    }

    // Listeners nos radio buttons
    document.querySelectorAll('input[name="role"]').forEach(radio => {
        radio.addEventListener('change', updateFormFields);
    });

    document.getElementById('gender').addEventListener('change', updateSocialNameVisibility);

    // Toggle senha
    function setupToggle(btnId, inputId, iconId) {
        document.getElementById(btnId).addEventListener('click', function() {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
    }

    setupToggle('toggle-senha', 'senha', 'icon-senha');
    setupToggle('toggle-confirm', 'senha_confirmation', 'icon-confirm');

    // Executar no load
    document.addEventListener('DOMContentLoaded', () => {
        updateFormFields();
        updateSocialNameVisibility();
    });
</script>

{{-- Toast system (standalone, sem layout) --}}
<div id="toast-container" style="position:fixed;top:1.25rem;right:1.25rem;z-index:9999;display:flex;flex-direction:column;gap:0.6rem;pointer-events:none;"></div>
<style>
    .toast{min-width:280px;max-width:420px;padding:.9rem 1.25rem;border-radius:12px;font-size:.875rem;font-weight:500;display:flex;align-items:flex-start;gap:.75rem;backdrop-filter:blur(16px);box-shadow:0 8px 32px rgba(0,0,0,.35);pointer-events:all;animation:toastIn .35s cubic-bezier(.21,1.02,.73,1) both;position:relative;overflow:hidden;}
    .toast.toast-success{background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.35);}
    .toast.toast-error{background:rgba(239,68,68,.15);border:1px solid rgba(239,68,68,.35);}
    .toast-icon{font-size:1rem;flex-shrink:0;margin-top:1px;}
    .toast-body{flex:1;color:var(--clr-text);line-height:1.45;}
    .toast-body strong{display:block;margin-bottom:2px;font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;}
    .toast-close{background:none;border:none;color:var(--clr-text-dim);cursor:pointer;font-size:.9rem;padding:0;flex-shrink:0;}
    .toast-progress{position:absolute;bottom:0;left:0;height:3px;border-radius:0 0 12px 12px;animation:toastProgress 4s linear forwards;}
    .toast-success .toast-progress{background:#10b981;}
    .toast-error .toast-progress{background:#ef4444;}
    @keyframes toastIn{from{opacity:0;transform:translateX(60px) scale(.95)}to{opacity:1;transform:translateX(0) scale(1)}}
    @keyframes toastProgress{from{width:100%}to{width:0%}}
    .toast.toast-out{animation:toastOut .3s ease forwards;}
    @keyframes toastOut{to{opacity:0;transform:translateX(60px);max-height:0;padding:0;margin:0;}}
</style>
<script>
function showToast(type, message) {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    const icon  = type === 'success' ? '\u2714' : '\u26a0\ufe0f';
    const label = type === 'success' ? 'Sucesso' : 'Aten\u00e7\u00e3o';
    toast.innerHTML = `<span class="toast-icon">${icon}</span><div class="toast-body"><strong>${label}</strong>${message}</div><button class="toast-close" onclick="dismissToast(this.parentElement)">&times;</button><div class="toast-progress"></div>`;
    container.appendChild(toast);
    setTimeout(() => dismissToast(toast), 4000);
}
function dismissToast(t) {
    if (!t || t.classList.contains('toast-out')) return;
    t.classList.add('toast-out');
    setTimeout(() => t.remove(), 300);
}
</script>
</body>
</html>