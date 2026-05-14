<?php

namespace Database\Seeders;

use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Database\Seeder;

class EstadoCidadeSeeder extends Seeder
{
    public function run(): void
    {
        $estados = [
            'AC' => ['Acre', ['Rio Branco', 'Cruzeiro do Sul']],
            'AL' => ['Alagoas', ['Maceio', 'Arapiraca']],
            'AP' => ['Amapa', ['Macapa', 'Santana']],
            'AM' => ['Amazonas', ['Manaus', 'Parintins']],
            'BA' => ['Bahia', ['Salvador', 'Feira de Santana', 'Vitoria da Conquista']],
            'CE' => ['Ceara', ['Fortaleza', 'Caucaia', 'Juazeiro do Norte', 'Maracanau', 'Sobral', 'Crato', 'Itapipoca', 'Maranguape']],
            'DF' => ['Distrito Federal', ['Brasilia']],
            'ES' => ['Espirito Santo', ['Vitoria', 'Vila Velha', 'Serra']],
            'GO' => ['Goias', ['Goiania', 'Aparecida de Goiania', 'Anapolis']],
            'MA' => ['Maranhao', ['Sao Luis', 'Imperatriz']],
            'MT' => ['Mato Grosso', ['Cuiaba', 'Varzea Grande']],
            'MS' => ['Mato Grosso do Sul', ['Campo Grande', 'Dourados']],
            'MG' => ['Minas Gerais', ['Belo Horizonte', 'Uberlandia', 'Contagem']],
            'PA' => ['Para', ['Belem', 'Ananindeua', 'Santarem']],
            'PB' => ['Paraiba', ['Joao Pessoa', 'Campina Grande']],
            'PR' => ['Parana', ['Curitiba', 'Londrina', 'Maringa']],
            'PE' => ['Pernambuco', ['Recife', 'Jaboatao dos Guararapes', 'Olinda']],
            'PI' => ['Piaui', ['Teresina', 'Parnaiba']],
            'RJ' => ['Rio de Janeiro', ['Rio de Janeiro', 'Niteroi', 'Sao Goncalo']],
            'RN' => ['Rio Grande do Norte', ['Natal', 'Mossoro']],
            'RS' => ['Rio Grande do Sul', ['Porto Alegre', 'Caxias do Sul', 'Pelotas']],
            'RO' => ['Rondonia', ['Porto Velho', 'Ji-Parana']],
            'RR' => ['Roraima', ['Boa Vista']],
            'SC' => ['Santa Catarina', ['Florianopolis', 'Joinville', 'Blumenau']],
            'SP' => ['Sao Paulo', ['Sao Paulo', 'Campinas', 'Santos', 'Guarulhos']],
            'SE' => ['Sergipe', ['Aracaju', 'Nossa Senhora do Socorro']],
            'TO' => ['Tocantins', ['Palmas', 'Araguaina']],
        ];

        foreach ($estados as $uf => [$nome, $cidades]) {
            $estado = Estado::updateOrCreate(['uf' => $uf], ['nome' => $nome]);

            foreach ($cidades as $cidade) {
                Cidade::updateOrCreate(
                    ['estado_id' => $estado->id, 'nome' => $cidade],
                    ['estado_id' => $estado->id, 'nome' => $cidade]
                );
            }
        }
    }
}
