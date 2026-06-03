<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

/**
 * MasterUserSeeder — Cria o Super Admin (Master) inicial do sistema.
 *
 * Uso:
 *   php artisan db:seed --class=MasterUserSeeder
 *
 * Ou para promover um usuário existente pelo e-mail:
 *   php artisan tinker
 *   >>> User::where('email', 'email@exemplo.com')->update(['role' => 'master']);
 */
class MasterUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('MASTER_EMAIL', 'master@syncmatch.com');
        $senha = env('MASTER_PASSWORD', 'Master@123');

        // Evita duplicar o master se já existir
        $existente = User::where('email', $email)->first();

        if ($existente) {
            // Promove para master caso já exista com outro role
            if ($existente->role !== 'master') {
                $existente->update(['role' => 'master']);
                $this->command->info("Usuário [{$email}] promovido para master com sucesso.");
            } else {
                $this->command->warn("Usuário master [{$email}] já existe. Nenhuma ação realizada.");
            }
            return;
        }

        User::create([
            'name'       => 'Super Admin',
            'email'      => $email,
            'password'   => bcrypt($senha),
            'role'       => 'master',
            'birth_date' => '1990-01-01',
            'gender'     => 'outro',
            'created_at' => now(),
        ]);

        $this->command->info("✅ Usuário master criado com sucesso!");
        $this->command->info("   E-mail : {$email}");
        $this->command->info("   Senha  : {$senha}");
        $this->command->warn("⚠️  Altere a senha após o primeiro login!");
    }
}
