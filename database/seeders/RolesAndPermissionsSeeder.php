<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar permissões específicas do sistema
        $permissions = [
            // === SISTEMA ===
            'view dashboard',
            'access admin panel',
            
            // === USUÁRIOS ===
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage user permissions',
            
            // === PROPRIETÁRIOS ===
            'view owners',
            'create owners',
            'edit owners',
            'delete owners',
            'export owners',
            
            // === ANIMAIS ===
            'view animals',
            'create animals',
            'edit animals',
            'delete animals',
            'export animals',
            
            // === AMOSTRAS ===
            'view samples',
            'create samples',
            'edit samples',
            'delete samples',
            'collect samples',
            'receive samples',
            'process samples',
            'export samples',
            
            // === EXAMES/TESTES ===
            'view tests',
            'create tests',
            'edit tests',
            'delete tests',
            'schedule tests',
            'execute tests',
            'validate tests',
            
            // Resultados Genéticos
            'view genetic results',
            'create genetic results',
            'edit genetic results',
            'delete genetic results',
            'validate genetic results',
            'release genetic results',
            'export genetic results',
            'review results',
            'approve results',
            'reject results',
            
            // === RELATÓRIOS ===
            'view reports',
            'create reports',
            'edit reports',
            'delete reports',
            'export reports',
            'view financial reports',
            
            // === LABORATÓRIO ===
            'access laboratory',
            'manage equipment',
            'quality control',
            'batch processing',
            
            // === LIBERAÇÃO ===
            'review results',
            'approve results',
            'reject results',
            'release reports',
            'digital signature',
            
            // === CONFIGURAÇÕES ===
            'manage settings',
            'manage system config',
            'view logs',
            'backup data',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // === CRIAR ROLES ESPECÍFICOS ===
        
        // 1. ADMIN - Acesso total ao sistema
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // 2. GERENTE - Gestão geral, sem configurações críticas
        $gerenteRole = Role::firstOrCreate(['name' => 'gerente']);
        $gerenteRole->givePermissionTo([
            'view dashboard',
            'access admin panel',
            'view users',
            'create users',
            'edit users',
            'view owners',
            'create owners',
            'edit owners',
            'delete owners',
            'export owners',
            'view animals',
            'create animals',
            'edit animals',
            'delete animals',
            'export animals',
            'view samples',
            'create samples',
            'edit samples',
            'delete samples',
            'receive samples',
            'export samples',
            'view tests',
            'create tests',
            'edit tests',
            'schedule tests',
            'view genetic results',
            'export genetic results',
            'view reports',
            'create reports',
            'edit reports',
            'export reports',
            'view financial reports',
            'review results',
            'approve results',
            'release reports',
        ]);

        // 3. ÁREA TÉCNICA - Execução de exames e análises
        $areaTecnicaRole = Role::firstOrCreate(['name' => 'area_tecnica']);
        $areaTecnicaRole->givePermissionTo([
            'view dashboard',
            'view owners',
            'view animals',
            'view samples',
            'create samples',
            'edit samples',
            'collect samples',
            'receive samples',
            'process samples',
            'view tests',
            'create tests',
            'edit tests',
            'execute tests',
            'validate tests',
            'view genetic results',
            'create genetic results',
            'edit genetic results',
            'validate genetic results',
            'access laboratory',
            'manage equipment',
            'quality control',
            'batch processing',
            'view reports',
        ]);

        // 4. LIBERAÇÃO - Aprovação e liberação de resultados
        $liberacaoRole = Role::firstOrCreate(['name' => 'liberacao']);
        $liberacaoRole->givePermissionTo([
            'view dashboard',
            'view owners',
            'view animals',
            'view samples',
            'view tests',
            'view genetic results',
            'validate genetic results',
            'review results',
            'approve results',
            'reject results',
            'release genetic results',
            'release reports',
            'digital signature',
            'view reports',
            'create reports',
            'export reports',
        ]);

        // 5. CADASTRO - Entrada de dados e cadastros
        $cadastroRole = Role::firstOrCreate(['name' => 'cadastro']);
        $cadastroRole->givePermissionTo([
            'view dashboard',
            'view owners',
            'create owners',
            'edit owners',
            'view animals',
            'create animals',
            'edit animals',
            'view samples',
            'create samples',
            'edit samples',
            'collect samples',
            'view tests',
            'create tests',
            'schedule tests',
            'view genetic results',
            'view reports',
        ]);

        // === CRIAR USUÁRIOS PADRÃO ===
        
        // 1. Usuário Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador do Sistema',
                'rg' => '123456789',
                'cpf' => '12345678901',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');

        // 2. Usuário Gerente
        $gerenteUser = User::firstOrCreate(
            ['email' => 'gerente@shortcode.com'],
            [
                'name' => 'Gerente Geral',
                'rg' => '987654321',
                'cpf' => '98765432109',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $gerenteUser->assignRole('gerente');

        // 3. Usuário Área Técnica
        $tecnicoUser = User::firstOrCreate(
            ['email' => 'tecnico@shortcode.com'],
            [
                'name' => 'Técnico de Laboratório',
                'rg' => '456789123',
                'cpf' => '45678912345',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $tecnicoUser->assignRole('area_tecnica');

        // 4. Usuário Liberação
        $liberacaoUser = User::firstOrCreate(
            ['email' => 'liberacao@shortcode.com'],
            [
                'name' => 'Responsável pela Liberação',
                'rg' => '789123456',
                'cpf' => '78912345678',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $liberacaoUser->assignRole('liberacao');

        // 5. Usuário Cadastro
        $cadastroUser = User::firstOrCreate(
            ['email' => 'cadastro@shortcode.com'],
            [
                'name' => 'Operador de Cadastro',
                'rg' => '321654987',
                'cpf' => '32165498765',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $cadastroUser->assignRole('cadastro');

        $adminUser->assignRole('admin');
    }
}