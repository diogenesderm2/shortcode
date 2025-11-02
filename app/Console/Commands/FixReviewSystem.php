<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class FixReviewSystem extends Command
{
    protected $signature = 'review:fix {--user=} {--force}';
    protected $description = 'Corrigir problemas comuns do sistema de revisão';

    public function handle()
    {
        $this->info('🔧 CORRIGINDO SISTEMA DE REVISÃO');
        $this->line('');

        // 1. Limpar cache
        $this->clearCache();
        
        // 2. Recriar permissões se necessário
        $this->ensurePermissions();
        
        // 3. Verificar e corrigir roles
        $this->ensureRoles();
        
        // 4. Atribuir permissões ao usuário se especificado
        if ($this->option('user')) {
            $this->fixUserPermissions($this->option('user'));
        }

        $this->line('');
        $this->info('✅ Sistema de revisão corrigido!');
        $this->line('');
        $this->info('📝 Próximos passos:');
        $this->line('1. Faça login novamente no sistema');
        $this->line('2. Verifique se o menu "Revisão de Resultados" aparece');
        $this->line('3. Se ainda não funcionar, execute: php artisan review:diagnose --user=seu@email.com');
        
        return 0;
    }

    private function clearCache()
    {
        $this->info('🧹 Limpando cache...');
        
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        $this->line('  ✅ Cache limpo');
        $this->line('');
    }

    private function ensurePermissions()
    {
        $this->info('📋 Verificando permissões...');
        
        $reviewPermissions = [
            'review results',
            'approve results', 
            'reject results'
        ];
        
        foreach ($reviewPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            $this->line("  ✅ {$permissionName} - OK");
        }
        
        $this->line('');
    }

    private function ensureRoles()
    {
        $this->info('👥 Verificando roles...');
        
        // Admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
        $this->line('  ✅ Admin role - OK');
        
        // Liberacao role
        $liberacaoRole = Role::firstOrCreate(['name' => 'liberacao']);
        $liberacaoRole->givePermissionTo([
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
        $this->line('  ✅ Liberacao role - OK');
        
        $this->line('');
    }

    private function fixUserPermissions($email)
    {
        $this->info("🔧 Corrigindo permissões do usuário: {$email}");
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("  ❌ Usuário não encontrado!");
            return;
        }
        
        // Se o usuário não tem nenhum role, atribuir admin
        if ($user->roles->isEmpty()) {
            $user->assignRole('admin');
            $this->line("  ✅ Role 'admin' atribuído ao usuário");
        }
        
        // Verificar se tem permissões de revisão
        if (!$user->can('review results')) {
            // Se não é admin, atribuir role liberacao
            if (!$user->hasRole('admin')) {
                $user->assignRole('liberacao');
                $this->line("  ✅ Role 'liberacao' atribuído ao usuário");
            }
        }
        
        $this->line("  ✅ Usuário {$email} corrigido");
        $this->line('');
    }
}