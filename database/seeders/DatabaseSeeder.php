<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Turno;
use App\Models\RegimeContratual;
use App\Models\Cargo;
use App\Models\Servidor;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa cache das permissões do Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Lista de permissões
        $permissionsList = [
            'Acessar Painel',
            'Listar Usuários',
            'Criar Usuários',
            'Editar Usuários',
            'Excluir Usuários',
            'Listar Níveis de Acesso',
            'Criar Níveis de Acesso',
            'Editar Níveis de Acesso',
            'Excluir Níveis de Acesso',
            'Listar Permissões de Execução',
            'Criar Permissões de Execução',
            'Editar Permissões de Execução',
            'Excluir Permissões de Execução',
            'Listar Dominios de Email',
            'Criar Dominios de Email',
            'Editar Dominios de Email',
            'Excluir Dominios de Email',
        ];

        $accessPainelPermissions = [
            'Acessar Painel',
        ];

        // Criação de permissões
        foreach ($permissionsList as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Criação de roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $accessPainelRole = Role::firstOrCreate(['name' => 'Acessar Painel']);

        $adminRole->syncPermissions($permissionsList);
        $accessPainelRole->syncPermissions($accessPainelPermissions);

        // Criação do usuário admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'email_verified_at' => now(),
                'email_approved' => true
            ]
        );

        $adminUser->assignRole($adminRole);

        // ========================
        // Criação de Turnos
        // ========================
        $turnos = [
            'Manhã',
            'Tarde',
            'Noite',
            'Integral',
        ];

        foreach ($turnos as $turno) {
            Turno::firstOrCreate(['nome' => $turno]);
        }

        // ========================
        // Criação de Regime Contratual
        // ========================
        $regimes = [
            'Estatutário',
            'C.L.T',
            'PSS',
        ];

        $regimeContratualIds = [];

        foreach ($regimes as $regime) {
            $regimeModel = RegimeContratual::firstOrCreate(['nome' => $regime]);
            $regimeContratualIds[$regime] = $regimeModel->id;
        }

        // ========================
        // Criação de Cargos
        // ========================
        $cargos = [
            'Professor',
            'Auxiliar Serviços Gerais',
            'Secretário Escolar',
        ];

        foreach ($cargos as $cargoNome) {
            foreach ($regimeContratualIds as $regimeNome => $regimeId) {
                Cargo::firstOrCreate(
                    [
                        'nome' => $cargoNome,
                        'regime_contratual_id' => $regimeId,
                    ],
                    [
                        'descricao' => "{$cargoNome} - {$regimeNome}",
                    ]
                );
            }
        }

        // ========================
        // Criação de X servidores aleatórios
        // ========================
        $numeroServidores = 100;

        for ($i = 1; $i <= $numeroServidores; $i++) {
            Servidor::create([
                'nome' => fake()->name(),
                'matricula' => str_pad($i, 4, '0', STR_PAD_LEFT),
                'email' => fake()->unique()->safeEmail(),
                'cargo_id' => Cargo::inRandomOrder()->first()->id,
                'turno_id' => Turno::inRandomOrder()->first()->id,
            ]);
        }
    }
}
