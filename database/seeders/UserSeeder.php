<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário padrão se não existir
        User::firstOrCreate(
            ['email' => 'admin@shortcode.test'],
            [
                'name' => 'Administrador',
                'rg' => '12.345.678-9',
                'cpf' => '123.456.789-00',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('UserSeeder executado com sucesso!');
        $this->command->info('Usuário padrão criado: admin@shortcode.test');
        $this->command->info('Senha: password');
    }
}