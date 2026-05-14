<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Servico;
use App\Models\Peca;
use App\Models\MarcasVeiculos;
use App\Models\ModelosVeiculos;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuários ────────────────────────────────────────────
        $this->call(UserSeeder::class);

        // ── Marcas e Modelos de Veículos ────────────────────────
        $marcas = [
            'Fiat' => ['Uno', 'Strada', 'Toro', 'Argo', 'Cronos', 'Mobi', 'Palio', 'Doblo', 'Siena', 'Torino'],
            'Volkswagen' => ['Gol', 'Polo', 'Virtus', 'Nivus', 'T-Cross', 'Golf', 'Passat', 'Jetta', 'Fox', 'Saveiro'],
            'Chevrolet' => ['Onix', 'Tracker', 'S10', 'Cruze', 'Spin', 'Cobalt', 'Prisma', 'Colorado', 'Camaro', 'Equinox'],
            'Honda' => ['Civic', 'City', 'HR-V', 'Fit', 'Accord', 'CR-V', 'WR-V', 'Insight', 'Passport', 'Pilot'],
            'Ford' => ['Ka', 'EcoSport', 'Ranger', 'Fusion', 'Focus', 'Edge', 'Fiesta', 'Mustang', 'Bronco', 'Territory'],
            'Toyota' => ['Corolla', 'Yaris', 'Hilux', 'RAV4', 'Prius', 'SW4', 'Camry', 'Etios', 'Corolla Cross', 'Land Cruiser'],
            'Renault' => ['Kwid', 'Sandero', 'Logan', 'Duster', 'Captur', 'Oroch', 'Stepway', 'Fluence', 'Megane', 'Koleos'],
            'Nissan' => ['Versa', 'Kicks', 'March', 'Sentra', 'Frontier', 'Leaf', 'X-Trail', 'Pathfinder', 'Tiida', 'Rogue'],
            'Audi' => ['A3', 'A4', 'A6', 'Q3', 'Q5', 'Q7', 'Q8', 'TT', 'RS3', 'e-tron'],
            'BMW' => ['320i', '330i', 'X1', 'X3', 'X5', 'M3', 'i3', 'Z4', 'X6', 'X7'],
            'Mercedes-Benz' => ['A200', 'C180', 'E200', 'GLA', 'GLC', 'GLE', 'S500', 'CLA', 'GLS', 'Sprinter'],
            'Hyundai' => ['HB20', 'Creta', 'Tucson', 'Santa Fe', 'i30', 'Elantra', 'Accent', 'Kona', 'Palisade', 'Hb20S'],
            'Kia' => ['Picanto', 'Rio', 'Sportage', 'Sorento', 'Soul', 'Cerato', 'Stonic', 'Seltos', 'Carnival', 'Optima'],
            'Subaru' => ['Impreza', 'Forester', 'Outback', 'WRX', 'Legacy', 'Crosstrek', 'BRZ', 'XV', 'Levorg', 'Tribeca'],
            'Peugeot' => ['208', '308', '3008', '5008', '2008', '408', '508', 'Partner', 'Expert', 'Rifter'],
            'Citroen' => ['C3', 'C4 Cactus', 'Aircross', 'C4 Lounge', 'Jumpy', 'SpaceTourer', 'C5 Aircross', 'Berlingo', 'Ami', 'DS3'],
        ];

        foreach ($marcas as $nome => $modelos) {
            $marca = MarcasVeiculos::updateOrCreate(['nome' => $nome], ['nome' => $nome]);
            foreach ($modelos as $modelo) {
                ModelosVeiculos::updateOrCreate([
                    'marca_id' => $marca->id,
                    'nome' => $modelo,
                ], [
                    'marca_id' => $marca->id,
                    'nome' => $modelo,
                ]);
            }
        }

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
            Servico::updateOrCreate(
                ['nome' => $s['nome']],
                array_merge($s, ['ativo' => true])
            );
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
            Peca::updateOrCreate(
                ['codigo' => $p['codigo']],
                array_merge($p, ['ativo' => true])
            );
        }

    }
}
