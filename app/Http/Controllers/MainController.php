<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Companie;
use App\Models\User;
use App\Services\UserRegistrationService;
use App\Repositories\JobRepository;

/**
 * MainController — Controlador principal do SyncMatch.
 *
 * Após a refatoração GoF, este controller delega:
 *  - Criação de usuários → UserRegistrationService (Facade)
 *    que por sua vez usa UserProfileFactory (Factory Method)
 *
 * O controller foca apenas em: receber request, validar, delegar e redirecionar.
 */
class MainController extends Controller
{
    public function __construct(
        private readonly UserRegistrationService $registrationService,
        private readonly JobRepository $jobRepository
    ) {}

    public function home() {
        $role = session('user_role');

        if ($role === 'student') {
            return redirect()->route('jobs.index');
        }

        if ($role === 'master') {
            return redirect()->route('master.panel');
        }

        $id = session('user_id');
        $companies = Companie::where('user_id', $id)
                            ->withCount('jobs')
                            ->paginate(10);

        return view('companies/index', ['companies' => $companies]);
    }

    public function companiesIndex() {
        $id   = session('user_id');
        $role = session('user_role');

        if ($role === 'admin' || $role === 'master') {
            $companies = Companie::withCount('jobs')->paginate(10);
        } else {
            $profile = \App\Models\RecruiterProfile::where('user_id', $id)->first();
            if ($profile) {
                $companies = $profile->companies()->withCount('jobs')->paginate(10);
            } else {
                // Paginação vazia caso não haja perfil
                $companies = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            }
        }
        return view('companies/index', ['companies' => $companies]);
    }

    public function jobsIndex(Request $request) {
        $search = $request->input('search');
        $type = $request->input('type');
        $mode = $request->input('mode');

        $jobs = $this->jobRepository->searchJobs($search, $type, $mode, 10);

        return view('jobs/index', ['jobs' => $jobs]);
    }

    public function companiesCreate() {
        if (session('user_role') !== 'admin' && session('user_role') !== 'master') {
            abort(403, 'Apenas administradores podem cadastrar empresas.');
        }
        return view('companies/create');
    }

    public function companiesShow($id) {
        $companies = Companie::where('id', $id)->get()->toArray();
        $jobs      = $this->jobRepository->getJobsByCompanyId($id, 10);

        // Se for admin ou dono da empresa, carregar recrutadores
        $recruiters = [];
        $company    = Companie::find($id);
        if (session('user_role') === 'admin' || session('user_role') === 'master' || ($company && $company->user_id == session('user_id'))) {
            if ($company) {
                $recruiters = $company->recruiters()->with('user')->get();
            }
        }

        return view('companies/show', [
            'companies'  => $companies,
            'jobs'       => $jobs,
            'recruiters' => $recruiters
        ]);
    }

    public function jobsShow($id) {
        $job = $this->jobRepository->findByIdWithCompany($id);
        if (!$job) abort(404);
        return view('jobs/show', ['job' => $job->toArray()]);
    }

    public function novoUsuario() {
        $companies = Companie::all()->toArray();
        return view('novo_usuario', compact('companies'));
    }

    /**
     * Valida os dados do formulário e delega o registro ao UserRegistrationService.
     * O Service usa a UserProfileFactory (Factory Method) internamente.
     */
    public function salvarUsuario(Request $request) {
        $request->validate([
            'nome'             => 'required|string|max:200',
            'email'            => 'required|email:rfc,dns|unique:users,email',
            'role'             => 'required|in:student,recruiter_existing,recruiter_new',
            'company_id'       => 'required_if:role,recruiter_existing|nullable|exists:companies,id',
            'company_name'     => 'required_if:role,recruiter_new|nullable|string|max:200',
            'company_cnpj'     => 'required_if:role,recruiter_new|nullable|string|max:25',
            'company_city'     => 'required_if:role,recruiter_new|nullable|string|max:100',
            'birth_date'       => 'required|date|before:today',
            'gender'           => 'required|in:masculino,feminino,outro',
            'social_name'      => 'nullable|string|max:200|required_if:gender,outro',
            'senha'            => 'required|string|min:6|confirmed',
        ], [
            'nome.required'          => 'O campo Nome é obrigatório.',
            'email.required'         => 'O campo E-mail é obrigatório.',
            'email.unique'           => 'Este e-mail já está cadastrado no sistema.',
            'role.required'          => 'A escolha do tipo de conta é obrigatória.',
            'company_id.required_if' => 'A seleção da empresa é obrigatória para recrutadores.',
            'company_name.required_if' => 'O nome da empresa é obrigatório.',
            'company_cnpj.required_if' => 'O CNPJ da empresa é obrigatório.',
            'company_city.required_if' => 'A cidade da empresa é obrigatória.',
            'birth_date.required'    => 'A data de nascimento é obrigatória.',
            'birth_date.date'        => 'Data de nascimento inválida.',
            'birth_date.before'      => 'A data de nascimento deve ser no passado.',
            'gender.required'        => 'O sexo é obrigatório.',
            'gender.in'              => 'Selecione uma opção de sexo válida.',
            'social_name.required_if'=> 'O nome social é obrigatório quando o sexo é "Outro".',
            'senha.required'         => 'O campo senha é obrigatório.',
            'senha.min'              => 'A senha deve ter no mínimo :min caracteres.',
            'senha.confirmed'        => 'A confirmação de senha não confere.',
        ]);

        // Delega ao Service que usa Factory Method para criar o perfil correto
        $this->registrationService->register($request->all());

        return redirect()->route('login')
                         ->with('success', 'Cadastro realizado com sucesso! Faça login para continuar.');
    }

    public function companiesEdit($id) {
        $company = Companie::findOrFail($id);
        return view('companies/edit', ['company' => $company]);
    }

    public function jobsEdit($id) {
        $iduser    = session('user_id');
        $companies = Companie::where('user_id', $iduser)->get()->toArray();
        $job       = Job::where('id', $id)->get()->toArray();
        return view('jobs/edit', ['jobs' => $job, 'companies' => $companies]);
    }

    public function companiesUpdate(Request $request) {
        $Companie              = Companie::findOrFail($request->input('id'));
        $Companie->name        = $request->input('name');
        $Companie->cnpj        = $request->input('cnpj');
        $Companie->area        = $request->input('area');
        $Companie->city        = $request->input('city');
        $Companie->description = $request->input('description');
        $Companie->updated_at  = now();
        $Companie->save();

        return redirect()->route('companies.index')->with('success', 'Empresa atualizada com sucesso!');
    }

    public function jobsUpdate(Request $request) {
        $Job              = Job::findOrFail($request->input('id'));
        $Job->company_id  = $request->input('company_id');
        $Job->title       = $request->input('title');
        $Job->description = $request->input('description');
        $Job->type        = $request->input('type');
        $Job->mode        = $request->input('mode');
        $Job->salary_range = $request->input('salary_range');
        $Job->status      = $request->input('status');
        $Job->updated_at  = now();
        $Job->save();

        return redirect()->route('jobs.index')->with('success', 'Vaga atualizada com sucesso!');
    }

    public function jobsDestroy($id) {
        $job = Job::find($id);
        if ($job) {
            $job->delete();
        }
        return redirect()->route('jobs.index')->with('success', 'Vaga deletada com sucesso!');
    }

    public function companiesDestroy($id) {
        if (session('user_role') !== 'admin' && session('user_role') !== 'master') {
            abort(403, 'Apenas administradores podem excluir empresas.');
        }

        $Companie = Companie::find($id);
        if ($Companie) {
            $Companie->delete();
        }
        return redirect()->route('companies.index')->with('success', 'Empresa deletada com sucesso!');
    }

    public function companiesStore(Request $request) {
        if (session('user_role') !== 'admin' && session('user_role') !== 'master') {
            abort(403, 'Apenas administradores podem cadastrar empresas.');
        }

        $Companie              = new Companie();
        $Companie->name        = $request->input('name');
        $Companie->cnpj        = $request->input('cnpj');
        $Companie->area        = $request->input('area');
        $Companie->city        = $request->input('city');
        $Companie->description = $request->input('description');
        $Companie->user_id     = session('user_id');
        $Companie->updated_at  = now();
        $Companie->save();

        return redirect()->route('companies.index')->with('success', 'Empresa cadastrada com sucesso!');
    }

    public function jobsCreate() {
        $id   = session('user_id');
        $role = session('user_role');

        if ($role === 'recruiter') {
            $profile = \App\Models\RecruiterProfile::where('user_id', $id)->first();
            if (!$profile) {
                return redirect()->route('jobs.index')
                                 ->withErrors(['error' => 'Sua conta de recrutador ainda não foi aprovada por nenhuma empresa.']);
            }
            $companies = $profile->companies()->wherePivot('approved', true)->get()->toArray();
            if (empty($companies)) {
                return redirect()->route('jobs.index')
                                 ->withErrors(['error' => 'Sua conta de recrutador ainda não foi aprovada por nenhuma empresa.']);
            }
        } else {
            $companies = Companie::all()->toArray();
        }

        return view('jobs/create', ['companies' => $companies]);
    }

    public function jobsStore(Request $request) {
        $id   = session('user_id');
        $role = session('user_role');

        if ($role === 'recruiter') {
            $profile = \App\Models\RecruiterProfile::where('user_id', $id)->first();
            if (!$profile) {
                return redirect()->route('jobs.index')
                                 ->withErrors(['error' => 'Sua conta de recrutador ainda não foi aprovada.']);
            }
            $isApproved = $profile->companies()->where('companies.id', $request->input('company_id'))->wherePivot('approved', true)->exists();
            if (!$isApproved) {
                return redirect()->route('jobs.index')
                                 ->withErrors(['error' => 'Sua conta de recrutador ainda não foi aprovada por esta empresa.']);
            }
        }

        $Job               = new Job();
        $Job->company_id   = $request->input('company_id');
        $Job->title        = $request->input('title');
        $Job->description  = $request->input('description');
        $Job->type         = $request->input('type');
        $Job->mode         = $request->input('mode');
        $Job->salary_range = $request->input('salary_range');
        $Job->status       = $request->input('status');
        $Job->updated_at   = now();
        $Job->save();

        return redirect()->route('jobs.index')->with('success', 'Vaga criada com sucesso!');
    }

    public function approveRecruiter($id, Request $request) {
        $profile = \App\Models\RecruiterProfile::findOrFail($id);
        $companyId = $request->input('company_id');
        $company = Companie::find($companyId);

        if (session('user_role') !== 'admin' && session('user_role') !== 'master' && (!$company || $company->user_id != session('user_id'))) {
            abort(403, 'Acesso não autorizado para aprovar este recrutador.');
        }

        $profile->companies()->updateExistingPivot($companyId, ['approved' => true]);

        return redirect()->back()->with('success', 'Recrutador aprovado com sucesso!');
    }
}
