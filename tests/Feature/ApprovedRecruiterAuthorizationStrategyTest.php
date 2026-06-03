<?php

use App\Models\Companie;
use App\Models\Job;
use App\Models\RecruiterProfile;
use App\Models\User;
use App\Patterns\Strategy\ApprovedRecruiterAuthorizationStrategy;


test('is authorized returns true when recruiter is approved for same company', function () {
    // 1. Arrange: Criar registros reais no banco em memória
    $user = User::create([
        'name' => 'Ana Recrutadora',
        'email' => 'ana@teste.com',
        'password' => bcrypt('123456'),
        'role' => 'recruiter'
    ]);

    $company = Companie::create([
        'name' => 'Tech Solutions',
        'cnpj' => '11.111.111/0001-11',
        'city' => 'São Paulo',
        'user_id' => $user->id // Pode ser qualquer dono
    ]);

    $job = Job::create([
        'title' => 'Desenvolvedor PHP',
        'description' => 'Vaga de teste',
        'type' => 'clt',
        'mode' => 'remoto',
        'status' => 'aberta',
        'company_id' => $company->id
    ]);

    // Criar perfil do recrutador e vincular com a empresa (APROVADO)
    $profile = RecruiterProfile::create(['user_id' => $user->id]);
    $profile->companies()->attach($company->id, ['approved' => true]);

    // 2. Act
    $strategy = new ApprovedRecruiterAuthorizationStrategy();
    $isAuthorized = $strategy->isAuthorized($user->id, $job);

    // 3. Assert
    expect($isAuthorized)->toBeTrue();
});

test('is authorized returns false when recruiter is not approved', function () {
    $user = User::create([
        'name' => 'Beto Recrutador',
        'email' => 'beto@teste.com',
        'password' => bcrypt('123456'),
        'role' => 'recruiter'
    ]);

    $company = Companie::create([
        'name' => 'Tech Solutions',
        'cnpj' => '22.222.222/0001-22',
        'city' => 'Rio de Janeiro',
        'user_id' => $user->id
    ]);

    $job = Job::create([
        'title' => 'Desenvolvedor Frontend',
        'description' => 'Vaga de teste',
        'type' => 'clt',
        'mode' => 'remoto',
        'status' => 'aberta',
        'company_id' => $company->id
    ]);

    // Criar perfil do recrutador e vincular com a empresa (NÃO APROVADO)
    $profile = RecruiterProfile::create(['user_id' => $user->id]);
    $profile->companies()->attach($company->id, ['approved' => false]);

    $strategy = new ApprovedRecruiterAuthorizationStrategy();
    expect($strategy->isAuthorized($user->id, $job))->toBeFalse();
});

test('is authorized returns false when company id does not match', function () {
    $user = User::create([
        'name' => 'Carlos Recrutador',
        'email' => 'carlos@teste.com',
        'password' => bcrypt('123456'),
        'role' => 'recruiter'
    ]);

    $companyA = Companie::create([
        'name' => 'Empresa do Carlos',
        'cnpj' => '33.333.333/0001-33',
        'city' => 'Curitiba',
        'user_id' => $user->id
    ]);

    $companyB = Companie::create([
        'name' => 'Empresa Concorrente',
        'cnpj' => '44.444.444/0001-44',
        'city' => 'Florianópolis',
        'user_id' => $user->id
    ]);

    $job = Job::create([
        'title' => 'Vaga na Concorrente',
        'description' => 'Vaga de teste',
        'type' => 'clt',
        'mode' => 'remoto',
        'status' => 'aberta',
        'company_id' => $companyB->id // A VAGA É DA EMPRESA B
    ]);

    $profile = RecruiterProfile::create(['user_id' => $user->id]);
    // O recrutador está aprovado, MAS apenas para a Empresa A
    $profile->companies()->attach($companyA->id, ['approved' => true]);

    $strategy = new ApprovedRecruiterAuthorizationStrategy();
    expect($strategy->isAuthorized($user->id, $job))->toBeFalse();
});

test('is authorized returns false when recruiter profile not found', function () {
    $user = User::create([
        'name' => 'Diana Recrutadora',
        'email' => 'diana@teste.com',
        'password' => bcrypt('123456'),
        'role' => 'recruiter'
    ]);

    $company = Companie::create([
        'name' => 'Empresa Fantasma',
        'cnpj' => '55.555.555/0001-55',
        'city' => 'Belo Horizonte',
        'user_id' => $user->id
    ]);

    $job = Job::create([
        'title' => 'Vaga Fantasma',
        'description' => 'Vaga de teste',
        'type' => 'clt',
        'mode' => 'remoto',
        'status' => 'aberta',
        'company_id' => $company->id
    ]);

    // PROPOSITALMENTE NÃO CRIAMOS O RECRUITER PROFILE

    $strategy = new ApprovedRecruiterAuthorizationStrategy();
    expect($strategy->isAuthorized($user->id, $job))->toBeFalse();
});
