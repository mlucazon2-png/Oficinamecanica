<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Mecanico;
use App\Models\ItemOs;
use App\Models\Garantia;
use App\Models\Cliente;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    public function index() { return view('relatorios.index'); }

    public function osPorStatus(Request $request)
    {
        $dados = OrdemServico::selectRaw('status, COUNT(*) as total')
            ->when($request->mes, fn($q) => $q->whereMonth('created_at', $request->mes))
            ->when($request->ano, fn($q) => $q->whereYear('created_at', $request->ano))
            ->groupBy('status')->get();
        return view('relatorios.os-status', compact('dados'));
    }

    public function faturamento(Request $request)
    {
        $ano   = $request->ano ?? date('Y');
        $dados = OrdemServico::where('status', 'finalizada')
            ->whereYear('data_conclusao', $ano)
            ->selectRaw('MONTH(data_conclusao) as mes, SUM(valor_total) as total, COUNT(*) as qtd')
            ->groupByRaw('MONTH(data_conclusao)')->orderByRaw('MONTH(data_conclusao)')->get();
        return view('relatorios.faturamento', compact('dados', 'ano'));
    }

    public function produtividade()
    {
        $dados = Mecanico::withCount(['ordens as os_total',
            'ordens as os_mes' => fn($q) => $q->whereMonth('created_at', now()->month),
        ])->withSum(['ordens as faturamento' => fn($q) => $q->where('status','finalizada')], 'valor_total')->get();
        return view('relatorios.produtividade', compact('dados'));
    }

    public function pecasMaisUsadas()
    {
        $dados = ItemOs::where('tipo', 'peca')->with('peca')
            ->selectRaw('peca_id, SUM(quantidade) as total_qtd, SUM(valor_total) as total_valor')
            ->groupBy('peca_id')->orderByDesc('total_qtd')->limit(20)->get();
        return view('relatorios.pecas', compact('dados'));
    }

    public function garantias()
    {
        $dados = Garantia::with('ordemServico.cliente')->latest()->paginate(20);
        return view('relatorios.garantias', compact('dados'));
    }

    public function tempoReparo()
    {
        $dados = OrdemServico::whereNotNull('data_conclusao')
            ->selectRaw('mecanico_id, COUNT(*) as total, AVG(TIMESTAMPDIFF(HOUR, created_at, data_conclusao)) as media_horas')
            ->groupBy('mecanico_id')->with('mecanico')->get();
        return view('relatorios.tempo-reparo', compact('dados'));
    }

    public function clientes()
    {
        $dados = Cliente::withCount('ordens')->orderByDesc('ordens_count')->paginate(20);
        return view('relatorios.clientes', compact('dados'));
    }

    public function orcamentos()
    {
        $dados = OrdemServico::selectRaw('aprovado_cliente, COUNT(*) as total, SUM(valor_total) as valor')
            ->groupBy('aprovado_cliente')->get();
        return view('relatorios.orcamentos', compact('dados'));
    }
}
