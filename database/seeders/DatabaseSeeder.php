<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Cliente;
use App\Models\Cidade;
use App\Models\Empresa;
use App\Models\Fornecedor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(EstadoSeeder::class);
        $this->call(CidadeSeeder::class);
        Empresa::create([
            'nome' => "Norte Conexão",
            'cnpj' => "39.983.219/0001-93",
            'email' => "corporativo@norteconexao.br",
            'gear_noc' => 205,
            'custo_fixo_percent' => 6
        ]);
        $admin = Admin::create([
            'nome' => "Josué | Suporte",
            'email' => "jardel.adm2024@gmail.com",
            'telefone' => "(38) 99953-6273",
            'password' => Hash::make('admin123'),
            'empresa_id' => 1,
        ]);

        $this->call(
            PermissoesERolesSeeder::class,
        );
        $admin->assignRole('Admin', 'admin');
    }
}
