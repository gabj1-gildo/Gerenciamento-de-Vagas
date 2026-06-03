<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Companie;
use App\Models\RecruiterProfile;

/**
 * MasterController — Painel do Super Admin (Master).
 *
 * Responsabilidades:
 *  - Listar todos os usuários do sistema
 *  - Alterar o perfil (role) de qualquer usuário
 *  - Criar empresas e atribuir um admin a elas
 *  - Alterar o admin (dono) de uma empresa existente
 *  - Vincular/desvincular recrutadores a empresas
 */
class MasterController extends Controller
{
    /** Painel principal: lista usuários, empresas e recrutadores. */
    public function panel(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users            = $query->orderBy('created_at', 'desc')->get();
        $companies        = Companie::with('admin')->orderBy('name')->get();
        $recruiterProfiles = RecruiterProfile::with(['user', 'companies'])->get();

        return view('master.panel', compact('users', 'companies', 'recruiterProfiles'));
    }

    /** Altera o perfil (role) de um usuário. */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:master,admin,recruiter,student',
        ], [
            'role.required' => 'O perfil é obrigatório.',
            'role.in'       => 'Perfil inválido.',
        ]);

        $user     = User::findOrFail($id);
        $novoRole = $request->input('role');

        if ($user->id == session('user_id') && $novoRole !== 'master') {
            return redirect()->back()
                             ->withErrors(['error' => 'Você não pode alterar seu próprio perfil de master.']);
        }

        if ($novoRole === 'master') {
            $masterExistente = User::where('role', 'master')->where('id', '!=', $id)->first();
            if ($masterExistente) {
                return redirect()->back()->withErrors([
                    'error' => "Já existe um Master no sistema ({$masterExistente->name}). Rebaixe-o antes de promover outro usuário.",
                ]);
            }
        }

        $labels = ['master' => 'Master', 'admin' => 'Admin de Empresa', 'recruiter' => 'Recrutador', 'student' => 'Candidato'];
        $label  = $labels[$novoRole] ?? $novoRole;

        $user->update(['role' => $novoRole]);

        return redirect()->route('master.panel')
                         ->with('success', "Perfil de \"{$user->name}\" atualizado para \"{$label}\" com sucesso!");
    }

    /** Cria uma nova empresa atribuindo um usuário como admin. */
    public function createCompany(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200',
            'cnpj'        => 'required|string|max:25',
            'city'        => 'required|string|max:100',
            'area'        => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'user_id'     => 'required|exists:users,id',
        ], [
            'name.required'    => 'O nome da empresa é obrigatório.',
            'cnpj.required'    => 'O CNPJ é obrigatório.',
            'city.required'    => 'A cidade é obrigatória.',
            'user_id.required' => 'Selecione um usuário como administrador da empresa.',
            'user_id.exists'   => 'Usuário inválido.',
        ]);

        $admin = User::findOrFail($request->input('user_id'));
        if ($admin->role !== 'admin' && $admin->role !== 'master') {
            $admin->update(['role' => 'admin']);
        }

        $company = Companie::create([
            'name'        => $request->input('name'),
            'cnpj'        => $request->input('cnpj'),
            'area'        => $request->input('area'),
            'city'        => $request->input('city'),
            'description' => $request->input('description'),
            'user_id'     => $admin->id,
        ]);

        return redirect()->route('master.panel', ['tab' => 'empresas'])
                         ->with('success', "Empresa \"{$company->name}\" criada e atribuída a \"{$admin->name}\" com sucesso!");
    }

    /** Altera o administrador (dono) de uma empresa existente. */
    public function changeCompanyAdmin(Request $request, $companyId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ], [
            'user_id.required' => 'Selecione o novo administrador.',
            'user_id.exists'   => 'Usuário inválido.',
        ]);

        $company  = Companie::findOrFail($companyId);
        $newAdmin = User::findOrFail($request->input('user_id'));

        // Promove para admin se necessário
        if (!in_array($newAdmin->role, ['admin', 'master'])) {
            $newAdmin->update(['role' => 'admin']);
        }

        $oldName = $company->admin ? $company->admin->name : '—';
        $company->update(['user_id' => $newAdmin->id]);

        return redirect()->route('master.panel', ['tab' => 'empresas'])
                         ->with('success', "Admin da empresa \"{$company->name}\" alterado de \"{$oldName}\" para \"{$newAdmin->name}\"!");
    }

    /** Vincula ou desvincula um recrutador a uma empresa. */
    public function linkRecruiter(Request $request, $companyId)
    {
        $request->validate([
            'recruiter_profile_id' => 'required|exists:recruiter_profiles,id',
            'action'               => 'required|in:vincular,aprovar,remover',
        ], [
            'recruiter_profile_id.required' => 'Selecione um recrutador.',
            'recruiter_profile_id.exists'   => 'Recrutador inválido.',
            'action.required'               => 'Ação inválida.',
        ]);

        $company = Companie::findOrFail($companyId);
        $profile = RecruiterProfile::with('user')->findOrFail($request->input('recruiter_profile_id'));
        $action  = $request->input('action');

        $jaVinculado = $profile->companies()->where('companies.id', $companyId)->exists();

        if ($action === 'remover') {
            $profile->companies()->detach($companyId);
            $msg = "Recrutador \"{$profile->user->name}\" removido da empresa \"{$company->name}\".";
        } elseif ($action === 'aprovar' && $jaVinculado) {
            $profile->companies()->updateExistingPivot($companyId, ['approved' => true]);
            $msg = "Recrutador \"{$profile->user->name}\" aprovado na empresa \"{$company->name}\"!";
        } else {
            // Vincular (pendente ou aprovado direto)
            $approved = ($action === 'aprovar');
            if ($jaVinculado) {
                $profile->companies()->updateExistingPivot($companyId, ['approved' => $approved]);
            } else {
                $profile->companies()->attach($companyId, ['approved' => $approved]);
            }
            $msg = "Recrutador \"{$profile->user->name}\" vinculado" . ($approved ? ' e aprovado' : ' (pendente)') . " na empresa \"{$company->name}\"!";
        }

        return redirect()->route('master.panel', ['tab' => 'empresas'])
                         ->with('success', $msg);
    }
}
