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
        $user = auth()->user();
        $cliente = $user->isCliente() ? $user->cliente : null;

        $ordensQuery = OrdemServico::query();

        if ($user->isCliente()) {
            $ordensQuery->where('cliente_id', $cliente?->id);
        }

        $recentesQuery = OrdemServico::with(['cliente','veiculo','mecanico']);

        if ($user->isCliente()) {
            $recentesQuery->where('cliente_id', $cliente?->id);
        }

        $stats = [
            'os_abertas'      => (clone $ordensQuery)->whereIn('status', ['aguardando_aceitacao', 'solicitacao_aceita', 'aberta'])->count(),
            'os_em_execucao'  => (clone $ordensQuery)->whereIn('status', ['em_execucao','em_diagnostico'])->count(),
            'os_aguardando'   => (clone $ordensQuery)->where('status', 'aguardando_aprovacao')->count(),
            'os_finalizadas_mes' => (clone $ordensQuery)->where('status', 'finalizada')
                                    ->whereMonth('data_conclusao', now()->month)->count(),
            'faturamento_mes' => (clone $ordensQuery)->where('status', 'finalizada')
                                    ->whereMonth('data_conclusao', now()->month)->sum('valor_total'),
            'total_clientes'  => Cliente::count(),
            'pecas_criticas'  => Peca::whereColumn('estoque', '<=', 'estoque_minimo')->count(),
            'mecanicos_ativos'=> Mecanico::where('ativo', true)->count(),
        ];

        $os_recentes = $recentesQuery->latest()->limit(8)->get();

        $pecas_criticas = Peca::whereColumn('estoque', '<=', 'estoque_minimo')
            ->orderBy('estoque')->limit(6)->get();

        return view('dashboard.index', compact('stats', 'os_recentes', 'pecas_criticas'));
    }
}
