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
            $table->enum('type', ['estagio', 'clt', 'freela', 'trainee'])->default('estagio');
            $table->enum('mode', ['presencial', 'hibrido', 'remoto'])->default('presencial');
            $table->string('salary_range')->nullable(); // faixa salarial/bolsa
            $table->enum('status', ['aberta', 'fechada', 'em_analise'])->default('aberta');

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
