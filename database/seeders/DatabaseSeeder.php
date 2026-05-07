<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Servico;
use App\Models\Peca;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuários ────────────────────────────────────────────
        User::create([
            'name'     => 'Gerente AutoTech',
            'email'    => 'gerente@autotech.com',
            'password' => Hash::make('password'),
            'role'     => 'gerente',
        ]);
        User::create([
            'name'     => 'Atendente',
            'email'    => 'atendente@autotech.com',
            'password' => Hash::make('password'),
            'role'     => 'atendente',
        ]);

        // ── Serviços ────────────────────────────────────────────
        $servicos = [
            ['nome' => 'Troca de óleo e filtro',     'categoria' => 'mecanica',  'valor_mao_obra' => 80.00,  'tempo_estimado' => 30],
            ['nome' => 'Alinhamento e balanceamento', 'categoria' => 'mecanica',  'valor_mao_obra' => 120.00, 'tempo_estimado' => 60],
            ['nome' => 'Revisão de freios',           'categoria' => 'mecanica',  'valor_mao_obra' => 150.00, 'tempo_estimado' => 90],
            ['nome' => 'Diagnóstico elétrico',        'categoria' => 'eletrica',  'valor_mao_obra' => 100.00, 'tempo_estimado' => 60],
            ['nome' => 'Troca de correia dentada',    'categoria' => 'mecanica',  'valor_mao_obra' => 200.00, 'tempo_estimado' => 120],
            ['nome' => 'Funilaria e pintura',         'categoria' => 'funilaria', 'valor_mao_obra' => 500.00, 'tempo_estimado' => 480],
            ['nome' => 'Revisão completa 10.000 km',  'categoria' => 'mecanica',  'valor_mao_obra' => 350.00, 'tempo_estimado' => 180],
            ['nome' => 'Troca de velas',              'categoria' => 'mecanica',  'valor_mao_obra' => 60.00,  'tempo_estimado' => 40],
            ['nome' => 'Higienização do ar-cond.',    'categoria' => 'eletrica',  'valor_mao_obra' => 90.00,  'tempo_estimado' => 45],
            ['nome' => 'Troca de amortecedores',      'categoria' => 'mecanica',  'valor_mao_obra' => 180.00, 'tempo_estimado' => 100],
        ];
        foreach ($servicos as $s) {
            Servico::create(array_merge($s, ['ativo' => true]));
        }

        // ── Peças ───────────────────────────────────────────────
        $pecas = [
            ['nome' => 'Filtro de óleo',       'codigo' => 'FO-001', 'fabricante' => 'Fram',   'preco_custo' => 15.00, 'preco_venda' => 28.00,  'estoque' => 20, 'estoque_minimo' => 5],
            ['nome' => 'Pastilha de freio',    'codigo' => 'PF-001', 'fabricante' => 'Bosch',  'preco_custo' => 45.00, 'preco_venda' => 89.00,  'estoque' => 10, 'estoque_minimo' => 3],
            ['nome' => 'Correia dentada',      'codigo' => 'CD-001', 'fabricante' => 'Gates',  'preco_custo' => 80.00, 'preco_venda' => 150.00, 'estoque' => 8,  'estoque_minimo' => 2],
            ['nome' => 'Vela de ignição',      'codigo' => 'VI-001', 'fabricante' => 'NGK',    'preco_custo' => 12.00, 'preco_venda' => 22.00,  'estoque' => 30, 'estoque_minimo' => 8],
            ['nome' => 'Filtro de ar',         'codigo' => 'FA-001', 'fabricante' => 'Mann',   'preco_custo' => 18.00, 'preco_venda' => 35.00,  'estoque' => 15, 'estoque_minimo' => 4],
            ['nome' => 'Fluido de freio',      'codigo' => 'FF-001', 'fabricante' => 'Bosch',  'preco_custo' => 12.00, 'preco_venda' => 25.00,  'estoque' => 12, 'estoque_minimo' => 4],
            ['nome' => 'Óleo 5W30 sintético',  'codigo' => 'OL-001', 'fabricante' => 'Mobil',  'preco_custo' => 28.00, 'preco_venda' => 55.00,  'estoque' => 25, 'estoque_minimo' => 6],
            ['nome' => 'Amortecedor diant.',   'codigo' => 'AM-001', 'fabricante' => 'Monroe', 'preco_custo' => 95.00, 'preco_venda' => 180.00, 'estoque' => 6,  'estoque_minimo' => 2],
            ['nome' => 'Disco de freio',       'codigo' => 'DF-001', 'fabricante' => 'Fremax', 'preco_custo' => 60.00, 'preco_venda' => 115.00, 'estoque' => 8,  'estoque_minimo' => 2],
            ['nome' => 'Filtro combustível',   'codigo' => 'FC-001', 'fabricante' => 'Mann',   'preco_custo' => 22.00, 'preco_venda' => 42.00,  'estoque' => 10, 'estoque_minimo' => 3],
        ];
        foreach ($pecas as $p) {
            Peca::create(array_merge($p, ['ativo' => true]));
        }
    }
}
