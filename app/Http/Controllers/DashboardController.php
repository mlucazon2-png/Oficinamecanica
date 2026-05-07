<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Cliente;
use App\Models\Peca;
use App\Models\Mecanico;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'os_abertas'      => OrdemServico::where('status', 'aberta')->count(),
            'os_em_execucao'  => OrdemServico::whereIn('status', ['em_execucao','em_diagnostico'])->count(),
            'os_aguardando'   => OrdemServico::where('status', 'aguardando_aprovacao')->count(),
            'os_finalizadas_mes' => OrdemServico::where('status', 'finalizada')
                                    ->whereMonth('data_conclusao', now()->month)->count(),
            'faturamento_mes' => OrdemServico::where('status', 'finalizada')
                                    ->whereMonth('data_conclusao', now()->month)->sum('valor_total'),
            'total_clientes'  => Cliente::count(),
            'pecas_criticas'  => Peca::whereColumn('estoque', '<=', 'estoque_minimo')->count(),
            'mecanicos_ativos'=> Mecanico::where('ativo', true)->count(),
        ];

        $os_recentes = OrdemServico::with(['cliente','veiculo','mecanico'])
            ->latest()->limit(8)->get();

        $pecas_criticas = Peca::whereColumn('estoque', '<=', 'estoque_minimo')
            ->orderBy('estoque')->limit(6)->get();

        return view('dashboard.index', compact('stats', 'os_recentes', 'pecas_criticas'));
    }
}
