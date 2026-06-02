<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Job;
use App\Models\CandidateProfile;
use App\Services\ApplicationService;

/**
 * ApplicationController — Controlador de candidaturas.
 *
 * Após a refatoração GoF, este controller delega:
 *  - Verificação de autorização → ApplicationService (Facade)
 *    que usa múltiplas AuthorizationStrategies (Strategy Pattern)
 *  - Atualização de status → ApplicationService
 *    que dispara ApplicationApproved (Observer Pattern)
 *
 * O controller foca apenas em: receber request, validar, delegar e redirecionar.
 */
class ApplicationController extends Controller
{
    public function __construct(
        private readonly ApplicationService $applicationService
    ) {}

    public function store(Request $request, $jobId)
    {
        $userId = session('user_id');

        // Verifica se candidato tem currículo cadastrado
        $profile = CandidateProfile::where('user_id', $userId)->first();
        if (!$profile || !$profile->resume_path) {
            return redirect()->back()
                             ->withErrors(['error' => 'Você precisa cadastrar seu currículo (PDF) em seu perfil antes de se candidatar.']);
        }

        $job = Job::findOrFail($jobId);
        if ($job->status === 'fechada') {
            return redirect()->back()
                             ->withErrors(['error' => 'Esta vaga já está fechada para novas candidaturas.']);
        }

        $exists = Application::where('job_id', $jobId)->where('user_id', $userId)->exists();
        if ($exists) {
            return redirect()->back()
                             ->withErrors(['error' => 'Você já se candidatou para esta vaga.']);
        }

        $request->validate([
            'cover_letter' => 'nullable|string|max:5000',
        ]);

        Application::create([
            'job_id'       => $jobId,
            'user_id'      => $userId,
            'cover_letter' => $request->input('cover_letter'),
            'status'       => 'recebido'
        ]);

        return redirect()->back()->with('success', 'Sua candidatura foi enviada com sucesso!');
    }

    public function candidateApplications()
    {
        $userId      = session('user_id');
        $applications = Application::where('user_id', $userId)
            ->with(['job.company'])
            ->get();

        return view('applications.candidate_index', compact('applications'));
    }

    public function jobApplications($jobId)
    {
        $userId = session('user_id');
        $job    = Job::with('company')->findOrFail($jobId);

        // Verifica autorização via ApplicationService (Strategy Pattern)
        if (!$this->applicationService->isAuthorized($userId, $job)) {
            abort(403, 'Acesso não autorizado ou conta de recrutador aguardando aprovação.');
        }

        $applications = Application::where('job_id', $jobId)
            ->with(['user.candidateProfile'])
            ->get();

        return view('applications.job_applicants', compact('job', 'applications'));
    }

    public function updateStatus(Request $request, $applicationId)
    {
        $userId      = session('user_id');
        $application = Application::with('job.company')->findOrFail($applicationId);

        // Verifica autorização via ApplicationService (Strategy Pattern)
        if (!$this->applicationService->isAuthorized($userId, $application->job)) {
            abort(403, 'Acesso não autorizado ou conta de recrutador aguardando aprovação.');
        }

        $request->validate([
            'status' => 'required|in:recebido,em_analise,entrevista,aprovado,rejeitado'
        ]);

        $newStatus = $request->input('status');

        // Delega ao Service: atualiza status e dispara Observer se aprovado
        $this->applicationService->updateStatus($application, $newStatus);

        $message = $newStatus === 'aprovado'
            ? 'Candidatura aprovada e vaga fechada automaticamente!'
            : 'Status da candidatura atualizado com sucesso!';

        return redirect()->back()->with('success', $message);
    }
}
