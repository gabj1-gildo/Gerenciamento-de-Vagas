<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companie_recruiter_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('companie_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('recruiter_profile_id')->constrained('recruiter_profiles')->cascadeOnDelete();
            $table->boolean('approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companie_recruiter_profile');
    }
};
