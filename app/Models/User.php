<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'birth_date',
        'gender',
        'social_name',
        'verification_token',
        'email_verified_at',
        'created_at',
    ];

    
    public function companies()
    {
        return $this->hasMany(Companie::class, 'user_id');
    }

    public function candidateProfile()
    {
        return $this->hasOne(CandidateProfile::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function recruiterProfile()
    {
        return $this->hasOne(RecruiterProfile::class);
    }

    public function isMaster()
    {
        return $this->role === 'master';
    }
}
