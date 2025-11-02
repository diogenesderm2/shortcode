<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\GeneticResult;
use App\Models\Admin\Sample\Sample;

class DiagnoseReviewSystem extends Command
{
    protected $signature = 'review:diagnose {--user=}';
    protected $description = 'Diagnosticar problemas com o sistema de revisão de resultados';

    public function handle()
    {
        $this->info('🔍 DIAGNÓSTICO DO SISTEMA DE REVISÃO');
        $this->line('');

        // 1. Verificar se as permissões existem
        $this->checkPermissions();
        
        // 2. Verificar usuários e roles
        $this->checkUsersAndRoles();
        
        // 3. Verificar usuário específico se fornecido
        if ($this->option('user')) {
            $this->checkSpecificUser($this->option('user'));
        }
        
        // 4. Verificar dados para revisão
        $this->checkReviewData();
        
        // 5. Verificar rotas
        $this->checkRoutes();

        $this->line('');
        $this->info('✅ Diagnóstico concluído!');
        
        return 0;
    }

    private function checkPermissions()
    {
        $this->info('📋 VERIFICANDO PERMISSÕES...');
        
        $reviewPermissions = [
            'review results',
            'approve results', 
            'reject results'
        ];
        
        foreach ($reviewPermissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                $this->line("  ✅ {$permissionName} - OK");
            } else {
                $this->error("  ❌ {$permissionName} - NÃO ENCONTRADA");
            }
        }
        $this->line('');
    }

    private function checkUsersAndRoles()
    {
        $this->info('👥 VERIFICANDO USUÁRIOS E ROLES...');
        
        $users = User::with('roles', 'permissions')->get();
        
        foreach ($users as $user) {
            $roles = $user->getRoleNames()->implode(', ');
            $hasReviewPermission = $user->can('review results') ? '✅' : '❌';
            
            $this->line("  📧 {$user->email}");
            $this->line("     Nome: {$user->name}");
            $this->line("     Roles: {$roles}");
            $this->line("     Pode revisar: {$hasReviewPermission}");
            $this->line("     Total permissões: " . $user->getAllPermissions()->count());
            $this->line('');
        }
    }

    private function checkSpecificUser($email)
    {
        $this->info("🔍 VERIFICANDO USUÁRIO: {$email}");
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("  ❌ Usuário não encontrado!");
            return;
        }
        
        $this->line("  📧 Email: {$user->email}");
        $this->line("  👤 Nome: {$user->name}");
        $this->line("  🔐 Roles: " . $user->getRoleNames()->implode(', '));
        
        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $this->line("  📋 Permissões (" . count($permissions) . "):");
        
        foreach ($permissions as $permission) {
            $this->line("     - {$permission}");
        }
        
        $this->line('');
        $this->line("  🎯 Permissões de revisão:");
        $this->line("     - review results: " . ($user->can('review results') ? '✅' : '❌'));
        $this->line("     - approve results: " . ($user->can('approve results') ? '✅' : '❌'));
        $this->line("     - reject results: " . ($user->can('reject results') ? '✅' : '❌'));
        $this->line('');
    }

    private function checkReviewData()
    {
        $this->info('📊 VERIFICANDO DADOS PARA REVISÃO...');
        
        $totalSamples = Sample::count();
        $totalResults = GeneticResult::count();
        $pendingSamples = Sample::where('is_released', 0)->count();
        $releasedSamples = Sample::where('is_released', 1)->count();
        
        $this->line("  📦 Total de amostras: {$totalSamples}");
        $this->line("  🧬 Total de resultados genéticos: {$totalResults}");
        $this->line("  ⏳ Amostras pendentes: {$pendingSamples}");
        $this->line("  ✅ Amostras liberadas: {$releasedSamples}");
        $this->line('');
    }

    private function checkRoutes()
    {
        $this->info('🛣️  VERIFICANDO ROTAS...');
        
        try {
            $routes = [
                'admin.review.index',
                'admin.review.show',
                'admin.review.update-status'
            ];
            
            foreach ($routes as $routeName) {
                if (route($routeName, ['result' => 1], false)) {
                    $this->line("  ✅ {$routeName} - OK");
                } else {
                    $this->error("  ❌ {$routeName} - ERRO");
                }
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Erro ao verificar rotas: " . $e->getMessage());
        }
        
        $this->line('');
    }
}