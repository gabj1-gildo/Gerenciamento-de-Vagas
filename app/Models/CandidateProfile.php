<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateProfile extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'bio',
        'skills',
        'education',
        'experience',
        'resume_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
