<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupAdminPermissions extends Command
{
    protected $signature = 'permissions:manage {action} {--email=} {--role=} {--permission=}';
    protected $description = 'Gerenciar permissões e roles do sistema';

    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list-roles':
                $this->listRoles();
                break;
            case 'list-permissions':
                $this->listPermissions();
                break;
            case 'assign-role':
                $this->assignRole();
                break;
            case 'user-permissions':
                $this->showUserPermissions();
                break;
            case 'list-users':
                $this->listUsers();
                break;
            default:
                $this->error('Ação inválida. Use: list-roles, list-permissions, assign-role, user-permissions, list-users');
                return 1;
        }

        return 0;
    }

    private function listRoles()
    {
        $this->info('=== ROLES DO SISTEMA ===');
        $roles = Role::with('permissions')->get();
        
        foreach ($roles as $role) {
            $this->line("📋 {$role->name} ({$role->permissions->count()} permissões)");
        }
    }

    private function listPermissions()
    {
        $this->info('=== PERMISSÕES DO SISTEMA ===');
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode(' ', $permission->name)[1] ?? 'outros';
        });

        foreach ($permissions as $group => $perms) {
            $this->line("\n📁 " . strtoupper($group));
            foreach ($perms as $permission) {
                $this->line("  - {$permission->name}");
            }
        }
    }

    private function assignRole()
    {
        $email = $this->option('email');
        $roleName = $this->option('role');

        if (!$email || !$roleName) {
            $this->error('Use: --email=usuario@email.com --role=nome_do_role');
            return;
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("Usuário com email {$email} não encontrado!");
            return;
        }

        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            $this->error("Role {$roleName} não encontrado!");
            $this->info('Roles disponíveis: ' . Role::pluck('name')->implode(', '));
            return;
        }

        $user->assignRole($roleName);
        $this->info("Role '{$roleName}' atribuído ao usuário {$user->name} ({$user->email})");
    }

    private function showUserPermissions()
    {
        $email = $this->option('email') ?? 'admin@admin.com';
        
        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error("Usuário com email {$email} não encontrado!");
            return;
        }

        $this->info("=== PERMISSÕES DO USUÁRIO: {$user->name} ===");
        
        // Mostrar roles
        $roles = $user->getRoleNames();
        $this->line("👤 Roles: " . $roles->implode(', '));
        
        // Mostrar permissões
        $permissions = $user->getAllPermissions();
        $this->line("🔐 Total de permissões: {$permissions->count()}");
        
        $groupedPermissions = $permissions->groupBy(function($permission) {
            return explode(' ', $permission->name)[1] ?? 'outros';
        });

        foreach ($groupedPermissions as $group => $perms) {
            $this->line("\n📁 " . strtoupper($group));
            foreach ($perms as $permission) {
                $this->line("  ✓ {$permission->name}");
            }
        }
    }

    private function listUsers()
    {
        $this->info('=== USUÁRIOS DO SISTEMA ===');
        $users = User::with('roles')->get();
        
        foreach ($users as $user) {
            $roles = $user->getRoleNames()->implode(', ');
            $this->line("👤 {$user->name} ({$user->email}) - Roles: {$roles}");
        }
    }
}