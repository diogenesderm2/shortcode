<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupAdminPermissions extends Command
{
    protected $signature = 'admin:setup-permissions {email?}';
    protected $description = 'Setup admin permissions for a user';

    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@admin.com';
        
        // Encontrar o usuário
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Usuário com email {$email} não encontrado!");
            return 1;
        }

        // Verificar se o role admin existe
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->error("Role 'admin' não encontrado! Execute o seeder primeiro.");
            return 1;
        }

        // Atribuir role admin ao usuário
        $user->assignRole('admin');
        
        $this->info("Role 'admin' atribuído ao usuário {$user->name} ({$user->email})");
        
        // Listar permissões do usuário
        $permissions = $user->getAllPermissions();
        $this->info("Permissões do usuário:");
        foreach ($permissions as $permission) {
            $this->line("- {$permission->name}");
        }
        
        return 0;
    }
}