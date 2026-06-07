<?php

namespace App\Repositories;

use App\Models\Job;
use App\Patterns\Builder\JobSearchBuilder;
use Illuminate\Pagination\LengthAwarePaginator;

class JobRepository
{
    /**
     * Busca vagas com filtros e paginação
     */
    public function searchJobs(?string $search, ?string $type, ?string $mode, int $perPage = 10): LengthAwarePaginator
    {
        $builder = (new JobSearchBuilder())
            ->withKeyword($search)
            ->withType($type)
            ->withMode($mode);

        return $builder->getQuery()->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Retorna uma vaga pelo ID com a empresa carregada
     */
    public function findByIdWithCompany(int $id): ?Job
    {
        return Job::with('company')->find($id);
    }
    
    /**
     * Busca as vagas de uma empresa específica com paginação
     */
    public function getJobsByCompanyId(int $companyId, int $perPage = 10): LengthAwarePaginator
    {
        return Job::where('company_id', $companyId)->with('company')->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
