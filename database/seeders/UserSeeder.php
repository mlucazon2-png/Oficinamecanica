<?php

namespace Database\Seeders;

use App\Models\Mecanico;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'joao@autotech.com',
                'name'  => 'João Atendente',
                'role'  => 'atendente',
            ],
            [
                'email' => 'antonio@autotech.com',
                'name'  => 'Antônio Gerente',
                'role'  => 'gerente',
            ],
            [
                'email' => 'mecanico@autotech.com',
                'name'  => 'Carlos Mecânico',
                'role'  => 'mecanico',
                'password' => '1',
                'cpf'   => '000.000.000-01',
                'telefone' => '(00) 00000-0001',
            ],
            [
                'email' => 'jose@autotech.com',
                'name'  => 'José Mecânico',
                'role'  => 'mecanico',
                'password' => '2',
                'cpf'   => '000.000.000-02',
                'telefone' => '(00) 00000-0002',
            ],
            [
                'email' => 'cliente@autotech.com',
                'name'  => 'Pedro Cliente',
                'role'  => 'cliente',
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make($data['password'] ?? '12345678'),
                    'role'     => $data['role'],
                ]
            );

            if ($data['role'] === 'mecanico') {
                Mecanico::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nome'      => $data['name'],
                        'cpf'       => $data['cpf'] ?? null,
                        'telefone'  => $data['telefone'] ?? null,
                        'ativo'     => true,
                    ]
                );
            }
        }

        $this->command->info('Usuários criados/atualizados com sucesso!');
        $this->command->line('');
        $this->command->line('Credenciais de acesso:');
        $this->command->line('  Atendente: joao@autotech.com / 12345678');
        $this->command->line('  Gerente:   antonio@autotech.com / 12345678');
        $this->command->line('  Mecânico:  mecanico@autotech.com / 1');
        $this->command->line('  Mecânico:  jose@autotech.com / 2');
        $this->command->line('  Cliente:   cliente@autotech.com / 12345678');
    }
}
