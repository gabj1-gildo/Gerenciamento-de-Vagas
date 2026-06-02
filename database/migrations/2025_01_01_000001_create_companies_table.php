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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('cnpj')->nullable(); // pode ser nullable pra facilitar testes
            $table->string('area')->nullable(); // área de atuação
            $table->string('city')->nullable();
            $table->text('description')->nullable();

            // Usuário que cadastrou a empresa (opcional)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete(); // se o usuário for apagado, seta user_id = null

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
