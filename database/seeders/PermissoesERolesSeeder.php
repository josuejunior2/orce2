<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PermissoesERolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin', 'description' => 'Acesso completo ao sistema']);
        // $colaborador = Role::create(['name' => 'Colaborador', 'guard_name' => 'admin', 'description' => 'Acesso completo ao sistema com excessões']);
        // $usuario = Role::create(['name' => 'Usuario', 'guard_name' => 'web', 'description' => 'Acesso parcial ao sistema']);

        $permissions = collect([
            ['guard_name' => 'admin', 'name' => 'CRUD basico',  'description' => 'Permite CRUD de fornecedores, orcamentos, sites e cotações(com exceção).'],
            ['guard_name' => 'admin', 'name' => 'CRUD usuarios',  'description' => 'Permite CRUD de usuários.'],
            ['guard_name' => 'admin', 'name' => 'excluir fornecedor',  'description' => 'Permite excluir fornecedores.'],
            ['guard_name' => 'admin', 'name' => 'excluir cliente',  'description' => 'Permite excluir cliente.'],
            ['guard_name' => 'admin', 'name' => 'excluir orcamento',  'description' => 'Permite excluir orcamento.'],
            ['guard_name' => 'admin', 'name' => 'excluir site',  'description' => 'Permite excluir site.'],
            ['guard_name' => 'admin', 'name' => 'excluir cotacao',  'description' => 'Permite excluir cotacao.'],

            ['guard_name' => 'admin', 'name' => 'precificar orcamento',  'description' => 'Permite precificar um orçamento.'],

            ['guard_name' => 'admin', 'name' => 'ver logs',  'description' => 'Permite ver os logs do sistema.'],
        ]);

        $permissions->each(function ($item) use ($admin) {
            $permission = Permission::firstOrCreate($item);
            $admin->givePermissionTo($permission);
            $permission->assignRole($admin);
        });

        // $permissionsColaborador = collect([
        //     ['guard_name' => 'admin', 'name' => 'CRUD basico',  'description' => 'Permite CRUD de fornecedores, orcamentos, sites e cotações(com exceção).'],
        // ]);

        // $permissionsColaborador->each(function ($item) use ($colaborador) {
        //     $permission = Permission::firstOrCreate($item);
        //     $permission->syncRoles([$colaborador]);
        //});


    }
}
