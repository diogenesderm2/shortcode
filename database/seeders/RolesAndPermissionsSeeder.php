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

        // Criar permissões
        $permissions = [
            // Usuários
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Proprietários
            'view owners',
            'create owners',
            'edit owners',
            'delete owners',
            
            // Animais
            'view animals',
            'create animals',
            'edit animals',
            'delete animals',
            
            // Amostras
            'view samples',
            'create samples',
            'edit samples',
            'delete samples',
            
            // Resultados Genéticos
            'view genetic results',
            'create genetic results',
            'edit genetic results',
            'delete genetic results',
            
            // Relatórios
            'view reports',
            'create reports',
            'edit reports',
            'delete reports',
            
            // Dashboard
            'view dashboard',
            
            // Upload DNA
            'upload dna',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Atribuir permissões aos roles
        // Admin tem todas as permissões
        $adminRole->givePermissionTo(Permission::all());

        // Manager tem permissões limitadas
        $managerRole->givePermissionTo([
            'view users',
            'view owners',
            'create owners',
            'edit owners',
            'view animals',
            'create animals',
            'edit animals',
            'view samples',
            'create samples',
            'edit samples',
            'view genetic results',
            'create genetic results',
            'edit genetic results',
            'view reports',
            'create reports',
            'view dashboard',
            'upload dna',
        ]);

        // User tem permissões básicas
        $userRole->givePermissionTo([
            'view owners',
            'view animals',
            'view samples',
            'view genetic results',
            'view reports',
            'view dashboard',
        ]);

        // Criar usuário admin padrão se não existir
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador',
                'rg' => '123456789',
                'cpf' => '12345678901',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $adminUser->assignRole('admin');
    }
}