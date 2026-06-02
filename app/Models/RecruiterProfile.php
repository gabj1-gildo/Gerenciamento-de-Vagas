<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruiterProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'approved'
    ];

    protected $casts = [
        'approved' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Companie::class, 'company_id');
    }
}
