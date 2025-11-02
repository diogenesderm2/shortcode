<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class FixReviewPermissions extends Command
{
    protected $signature = 'fix:review-permissions {--user-email=}';
    protected $description = 'Corrige problemas com permissões do sistema de revisão';

    public function handle()
    {
        $this->info('🔧 Corrigindo permissões do sistema de revisão...');
        $this->line('');

        // 1. Limpar cache
        $this->info('🧹 Limpando cache...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        $this->line('✅ Cache limpo');

        // 2. Criar permissões se não existirem
        $this->info('📋 Verificando permissões...');
        $permissions = [
            'review results',
            'approve results', 
            'reject results'
        ];
        
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $this->line("✅ {$permissionName}");
        }

        // 3. Verificar/criar roles
        $this->info('👥 Verificando roles...');
        
        // Admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());
        $this->line('✅ Admin role atualizado');
        
        // Liberacao role
        $liberacaoRole = Role::firstOrCreate(['name' => 'liberacao']);
        $liberacaoRole->syncPermissions([
            'view dashboard',
            'view owners',
            'view animals',
            'view samples',
            'view tests',
            'view genetic results',
            'review results',
            'approve results',
            'reject results',
            'view reports'
        ]);
        $this->line('✅ Liberacao role atualizado');

        // 4. Corrigir usuário específico ou todos
        if ($this->option('user-email')) {
            $this->fixSpecificUser($this->option('user-email'));
        } else {
            $this->fixAllUsers();
        }

        // 5. Mostrar status final
        $this->showCurrentStatus();

        $this->line('');
        $this->info('🎉 Correção concluída!');
        $this->warn('⚠️  IMPORTANTE: Faça logout e login novamente para ver as mudanças.');
        
        return 0;
    }

    private function fixSpecificUser($email)
    {
        $this->info("🔧 Corrigindo usuário: {$email}");
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Usuário não encontrado!");
            return;
        }

        // Se não tem roles, dar admin
        if ($user->roles->isEmpty()) {
            $user->assignRole('admin');
            $this->line("✅ Role 'admin' atribuído");
        }
        
        // Verificar se pode revisar
        if (!$user->can('review results')) {
            if (!$user->hasRole('admin')) {
                $user->assignRole('liberacao');
                $this->line("✅ Role 'liberacao' atribuído");
            }
        }
    }

    private function fixAllUsers()
    {
        $this->info('👤 Verificando todos os usuários...');
        
        $users = User::all();
        
        foreach ($users as $user) {
            if ($user->roles->isEmpty()) {
                $user->assignRole('admin');
                $this->line("✅ Role 'admin' atribuído a {$user->email}");
            }
        }
    }

    private function showCurrentStatus()
    {
        $this->info('📊 Status atual dos usuários:');
        
        $users = User::with('roles')->get();
        
        foreach ($users as $user) {
            $canReview = $user->can('review results') ? '✅' : '❌';
            $roles = $user->getRoleNames()->implode(', ') ?: 'Nenhum';
            
            $this->line("📧 {$user->email}");
            $this->line("   Roles: {$roles}");
            $this->line("   Pode revisar: {$canReview}");
            $this->line('');
        }
    }
}