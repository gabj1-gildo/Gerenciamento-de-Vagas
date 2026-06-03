<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationController;
use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\CheckMaster;
use App\Http\Middleware\CheckSession;
use Illuminate\Support\Facades\Auth;

Route::middleware([CheckSession::class])->group(function(){
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/loginSubmit', [AuthController::class, 'loginSubmit'])->name('loginSubmit');
});

Route::middleware([CheckLogin::class])->group(function () {
    Route::get('/', [MainController::class, 'home'])->name('home');

    Route::get('/companies_create', [MainController::class, 'companiesCreate'])->name('companies.create');
    Route::post('/companies_store', [MainController::class, 'companiesStore'])->name('companies.store');
    Route::get('/companies_index', [MainController::class, 'companiesIndex'])->name('companies.index');
    Route::get('/companies_show/{id}', [MainController::class, 'companiesShow'])->name('companies.show');
    Route::get('/companies_edit/{id}', [MainController::class, 'companiesEdit'])->name('companies.edit');
    Route::get('/companies_destroy/{id}', [MainController::class, 'companiesDestroy'])->name('companies.destroy');
    Route::post('/companies_update', [MainController::class, 'companiesUpdate'])->name('companies.update');

    Route::get('/jobs_create', [MainController::class, 'jobsCreate'])->name('jobs.create');
    Route::post('/jobs_store', [MainController::class, 'jobsStore'])->name('jobs.store');
    Route::get('/jobs_index', [MainController::class, 'jobsIndex'])->name('jobs.index');
    Route::get('/jobs_show/{id}', [MainController::class, 'jobsShow'])->name('jobs.show');
    Route::get('/jobs_edit/{id}', [MainController::class, 'jobsEdit'])->name('jobs.edit');
    Route::get('/jobs_destroy/{id}', [MainController::class, 'jobsDestroy'])->name('jobs.destroy');
    Route::post('/jobs_update', [MainController::class, 'jobsUpdate'])->name('jobs.update');

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Candidatos (Estudantes)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/jobs/{jobId}/apply', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/my-applications', [ApplicationController::class, 'candidateApplications'])->name('candidate.applications');

    // Recrutadores (Empresas)
    Route::get('/jobs/{jobId}/applicants', [ApplicationController::class, 'jobApplications'])->name('job.applications');
    Route::post('/applications/{applicationId}/status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');
    Route::post('/recruiters/{id}/approve', [MainController::class, 'approveRecruiter'])->name('recruiters.approve');
});

// ─── Rotas do Super Admin (Master) ────────────────────────────────────────
Route::middleware([CheckLogin::class, CheckMaster::class])->prefix('master')->name('master.')->group(function () {
    Route::get('/', [MasterController::class, 'panel'])->name('panel');
    Route::post('/users/{id}/role', [MasterController::class, 'updateRole'])->name('updateRole');
    Route::post('/companies/create', [MasterController::class, 'createCompany'])->name('createCompany');
    Route::post('/companies/{companyId}/admin', [MasterController::class, 'changeCompanyAdmin'])->name('changeCompanyAdmin');
    Route::post('/companies/{companyId}/recruiters', [MasterController::class, 'linkRecruiter'])->name('linkRecruiter');
});

Route::get('/novo_usuario', [MainController::class, 'novoUsuario'])->name('novo_usuario');
Route::post('/salvar_usuario', [MainController::class, 'salvarUsuario'])->name('salvar_usuario');
