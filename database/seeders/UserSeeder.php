<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Atendente
        User::updateOrCreate(
            ['email' => 'joao@autotech.com'],
            [
                'name'     => 'João Atendente',
                'password' => Hash::make('12345678'),
                'role'     => 'atendente',
            ]
        );

        // Gerente
        User::updateOrCreate(
            ['email' => 'gerente@autotech.com'],
            [
                'name'     => 'Maria Gerente',
                'password' => Hash::make('12345678'),
                'role'     => 'gerente',
            ]
        );

        // Mecânico
        User::updateOrCreate(
            ['email' => 'mecanico@autotech.com'],
            [
                'name'     => 'Carlos Mecânico',
                'password' => Hash::make('12345678'),
                'role'     => 'mecanico',
            ]
        );

        // Cliente
        User::updateOrCreate(
            ['email' => 'cliente@autotech.com'],
            [
                'name'     => 'Pedro Cliente',
                'password' => Hash::make('12345678'),
                'role'     => 'cliente',
            ]
        );

        $this->command->info('Usuários criados/atualizados com sucesso!');
        $this->command->line('');
        $this->command->line('Credenciais de acesso:');
        $this->command->line('  Atendente: joao@autotech.com / 12345678');
        $this->command->line('  Gerente:   gerente@autotech.com / 12345678');
        $this->command->line('  Mecânico:  mecanico@autotech.com / 12345678');
        $this->command->line('  Cliente:   cliente@autotech.com / 12345678');
    }
}
