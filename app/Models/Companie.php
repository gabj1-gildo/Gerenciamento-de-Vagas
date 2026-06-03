<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Companie extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'cnpj',
        'area',
        'city',
        'description',
        'user_id',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id');
    }

    public function recruiters()
    {
        return $this->belongsToMany(RecruiterProfile::class, 'companie_recruiter_profile')
                    ->withPivot('approved')
                    ->withTimestamps();
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
