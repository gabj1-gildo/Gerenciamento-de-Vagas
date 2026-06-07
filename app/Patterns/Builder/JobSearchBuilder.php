<?php

namespace App\Patterns\Builder;

use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;

class JobSearchBuilder
{
    private Builder $query;

    public function __construct()
    {
        $this->query = Job::with('company');
    }

    public function withKeyword(?string $search): self
    {
        if (!empty($search)) {
            $this->query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('company', function($compQuery) use ($search) {
                      $compQuery->where('name', 'like', '%' . $search . '%');
                  });
            });
        }
        return $this;
    }

    public function withType(?string $type): self
    {
        if (!empty($type)) {
            $this->query->where('type', $type);
        }
        return $this;
    }

    public function withMode(?string $mode): self
    {
        if (!empty($mode)) {
            $this->query->where('mode', $mode);
        }
        return $this;
    }

    public function getQuery(): Builder
    {
        return $this->query;
    }
}
