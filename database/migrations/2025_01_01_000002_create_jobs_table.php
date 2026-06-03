<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();

            // FK para a empresa
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->cascadeOnDelete(); // se a empresa for apagada, apaga as vagas

            $table->string('title'); // título da vaga
            $table->text('description'); // descrição detalhada
            $table->string('type')->default('estagio');
            $table->string('mode')->default('presencial');
            $table->string('salary_range')->nullable(); // faixa salarial/bolsa
            $table->string('status')->default('aberta');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
