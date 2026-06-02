<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Companie;
use App\Models\Job;
use App\Models\CandidateProfile;
use App\Models\RecruiterProfile;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Criar Administrador Geral
        $admin = User::create([
            'name' => 'Admin SyncMatch',
            'email' => 'admin@teste.com',
            'password' => bcrypt('123456'),
            'role' => 'admin'
        ]);

        // 2. Criar Recrutador Principal (Aprovado)
        $recruiterApproved = User::create([
            'name' => 'Ana Clara (Recrutadora Aprovada)',
            'email' => 'recrutador@teste.com',
            'password' => bcrypt('123456'),
            'role' => 'recruiter'
        ]);

        // 3. Criar Recrutador Pendente (Para teste de aprovação)
        $recruiterPending = User::create([
            'name' => 'Roberto Carlos (Recrutador Pendente)',
            'email' => 'pendente@teste.com',
            'password' => bcrypt('123456'),
            'role' => 'recruiter'
        ]);

        // 4. Criar Candidato
        $candidate = User::create([
            'name' => 'Carlos Silva (Candidato)',
            'email' => 'candidato@teste.com',
            'password' => bcrypt('123456'),
            'role' => 'student'
        ]);

        // 5. Criar Perfil de Candidato para o Carlos
        CandidateProfile::create([
            'user_id' => $candidate->id,
            'phone' => '(38) 99999-8888',
            'bio' => 'Estudante de Análise e Desenvolvimento de Sistemas apaixonado por desenvolvimento back-end com PHP e Laravel.',
            'skills' => 'PHP, Laravel, Git, MySQL, HTML, CSS, JavaScript',
            'education' => 'Tecnologia em Análise e Desenvolvimento de Sistemas - IFNMG (2024 - 2026)',
            'experience' => 'Desenvolvimento de projetos acadêmicos e repositórios open-source no GitHub.',
            'resume_path' => null
        ]);

        // 6. Criar Empresa vinculada ao Admin (Dono)
        $company = Companie::create([
            'name' => 'Tech Solutions Corp',
            'cnpj' => '12.345.678/0001-99',
            'area' => 'Tecnologia da Informação',
            'city' => 'Montes Claros',
            'description' => 'Empresa focada em soluções em nuvem e desenvolvimento de softwares sob demanda para o setor corporativo.',
            'user_id' => $admin->id
        ]);

        // 7. Criar Perfis dos Recrutadores na Empresa
        RecruiterProfile::create([
            'user_id' => $recruiterApproved->id,
            'company_id' => $company->id,
            'approved' => true // Aprovado
        ]);

        RecruiterProfile::create([
            'user_id' => $recruiterPending->id,
            'company_id' => $company->id,
            'approved' => false // Aguardando aprovação
        ]);

        // 8. Criar Vagas vinculadas à Empresa
        Job::create([
            'company_id' => $company->id,
            'title' => 'Estágio em Desenvolvimento PHP/Laravel',
            'description' => 'Procuramos estudantes proativos que queiram aprender e crescer com o nosso time técnico. Você atuará no desenvolvimento de APIs Rest e manutenção de sistemas corporativos em Laravel.',
            'type' => 'estagio',
            'mode' => 'remoto',
            'salary_range' => 'R$ 1.200,00',
            'status' => 'aberta'
        ]);

        Job::create([
            'company_id' => $company->id,
            'title' => 'Desenvolvedor Full Stack Júnior (CLT)',
            'description' => 'Atuação presencial focada na evolução de plataformas legadas em PHP nativo e novos projetos construídos em ecossistema moderno utilizando Laravel e Vue.js.',
            'type' => 'clt',
            'mode' => 'presencial',
            'salary_range' => 'R$ 3.500,00 a R$ 4.500,00',
            'status' => 'aberta'
        ]);
    }
}
