<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class QuickFixReview extends Command
{
    protected $signature = 'review:quickfix';
    protected $description = 'Correção rápida para o sistema de revisão';

    public function handle()
    {
        $this->info('🚀 CORREÇÃO RÁPIDA DO SISTEMA DE REVISÃO');
        $this->line('');

        // 1. Limpar cache
        $this->info('🧹 Limpando cache...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        $this->line('✅ Cache limpo');
        $this->line('');

        // 2. Verificar e criar permissões
        $this->info('📋 Verificando permissões...');
        $permissions = ['review results', 'approve results', 'reject results'];
        
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
            $this->line("✅ {$permissionName}");
        }
        $this->line('');

        // 3. Verificar roles
        $this->info('👥 Verificando roles...');
        
        // Admin
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
        $this->line('✅ Admin role configurado');
        
        // Liberacao
        $liberacaoRole = Role::firstOrCreate(['name' => 'liberacao']);
        $liberacaoRole->givePermissionTo($permissions);
        $this->line('✅ Liberacao role configurado');
        $this->line('');

        // 4. Verificar usuários
        $this->info('👤 Verificando usuários...');
        
        $users = User::all();
        foreach ($users as $user) {
            if ($user->roles->isEmpty()) {
                $user->assignRole('admin');
                $this->line("✅ Role admin atribuído a {$user->email}");
            }
        }
        $this->line('');

        // 5. Mostrar status atual
        $this->info('📊 STATUS ATUAL:');
        $users = User::with('roles')->get();
        
        foreach ($users as $user) {
            $canReview = $user->can('review results') ? '✅' : '❌';
            $roles = $user->getRoleNames()->implode(', ');
            $this->line("📧 {$user->email} - Roles: {$roles} - Pode revisar: {$canReview}");
        }
        
        $this->line('');
        $this->info('🎉 Correção concluída!');
        $this->line('');
        $this->warn('📝 IMPORTANTE:');
        $this->line('1. Faça logout e login novamente');
        $this->line('2. O menu "Revisão de Resultados" deve aparecer agora');
        $this->line('3. Se ainda não funcionar, verifique se você está logado com um usuário que tem permissões');
        
        return 0;
    }
}