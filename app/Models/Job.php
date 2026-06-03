<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['title', 'description', 'type', 'mode', 'status', 'company_id'];

    
    // opcional, mas recomendado
    public function company()
    {
        return $this->belongsTo(Companie::class, 'company_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
