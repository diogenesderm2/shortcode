<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CheckCurrentUser extends Command
{
    protected $signature = 'user:check';
    protected $description = 'Verificar usuários e permissões do sistema';

    public function handle()
    {
        $this->info('=== VERIFICAÇÃO DE USUÁRIOS E PERMISSÕES ===');
        
        // Listar todos os usuários
        $this->info("\n👥 USUÁRIOS DO SISTEMA:");
        $users = User::with('roles')->get();
        
        foreach ($users as $user) {
            $roles = $user->getRoleNames()->implode(', ');
            $this->line("📧 {$user->email} - {$user->name} - Roles: {$roles}");
        }
        
        // Verificar se existem roles
        $this->info("\n🔐 ROLES DISPONÍVEIS:");
        $roles = Role::all();
        foreach ($roles as $role) {
            $this->line("- {$role->name}");
        }
        
        // Verificar se existem permissões
        $this->info("\n📋 TOTAL DE PERMISSÕES: " . Permission::count());
        
        // Verificar permissão específica
        $viewSamplesPermission = Permission::where('name', 'view samples')->first();
        if ($viewSamplesPermission) {
            $this->info("✅ Permissão 'view samples' existe");
        } else {
            $this->error("❌ Permissão 'view samples' NÃO existe");
        }
        
        // Verificar usuário admin
        $adminUser = User::where('email', 'admin@admin.com')->first();
        if ($adminUser) {
            $this->info("\n🔍 USUÁRIO ADMIN:");
            $this->line("Nome: {$adminUser->name}");
            $this->line("Email: {$adminUser->email}");
            $this->line("Roles: " . $adminUser->getRoleNames()->implode(', '));
            $this->line("Tem permissão 'view samples': " . ($adminUser->can('view samples') ? 'SIM' : 'NÃO'));
            $this->line("Total de permissões: " . $adminUser->getAllPermissions()->count());
        }
        
        return 0;
    }
}