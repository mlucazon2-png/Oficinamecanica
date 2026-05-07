<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Cliente;
use App\Models\Veiculo;
use App\Models\Mecanico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrdemServicoController extends Controller
{
    // UC007 — Listar com filtros
    public function index(Request $request)
    {
        $query = OrdemServico::with(['cliente','veiculo','mecanico'])->latest();

        if ($request->filled('status'))     $query->where('status', $request->status);
        if ($request->filled('mecanico_id'))$query->where('mecanico_id', $request->mecanico_id);
        if ($request->filled('data_inicio'))$query->whereDate('created_at', '>=', $request->data_inicio);
        if ($request->filled('data_fim'))   $query->whereDate('created_at', '<=', $request->data_fim);

        if ($request->filled('busca')) {
            $b = $request->busca;
            $query->where(fn($q) =>
                $q->where('numero', 'like', "%{$b}%")
                  ->orWhereHas('cliente', fn($c) => $c->where('nome', 'like', "%{$b}%"))
                  ->orWhereHas('veiculo', fn($v) => $v->where('placa', 'like', "%{$b}%"))
            );
        }

        // Clientes só veem as próprias OS
        if (Auth::user()->isCliente()) {
            $query->whereHas('cliente', fn($q) => $q->where('user_id', Auth::id()));
        }

        $ordens    = $query->paginate(20)->withQueryString();
        $mecanicos = Mecanico::where('ativo', true)->orderBy('nome')->get();

        return view('ordens-servico.index', compact('ordens', 'mecanicos'));
    }

    // UC003 — Formulário de abertura
    public function create()
    {
        $clientes  = Cliente::orderBy('nome')->get();
        $mecanicos = Mecanico::where('ativo', true)->orderBy('nome')->get();
        return view('ordens-servico.create', compact('clientes', 'mecanicos'));
    }

    // UC003 — Salvar nova OS
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id'  => 'required|exists:clientes,id',
            'veiculo_id'  => 'required|exists:veiculos,id',
            'mecanico_id' => 'nullable|exists:mecanicos,id',
            'sintomas'    => 'required|string|max:2000',
            'km_entrada'  => 'nullable|integer|min:0',
        ]);

        $data['numero'] = OrdemServico::gerarNumero();
        $data['status'] = 'aberta';

        $os = OrdemServico::create($data);

        return redirect()->route('os.show', $os)
               ->with('success', "OS {$os->numero} aberta com sucesso!");
    }

    // Ver OS completa
    public function show($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $ordemServico->load([
            'cliente','veiculo','mecanico',
            'itens.servico','itens.peca',
            'fotos','garantias',
        ]);

        $servicos  = \App\Models\Servico::where('ativo', true)->orderBy('nome')->get();
        $pecas     = \App\Models\Peca::where('ativo', true)->orderBy('nome')->get();

        return view('ordens-servico.show', compact('ordemServico','servicos','pecas'));
    }

    public function edit($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $mecanicos = Mecanico::where('ativo', true)->orderBy('nome')->get();
        return view('ordens-servico.edit', compact('ordemServico','mecanicos'));
    }

    // UC004/UC005 — Atualizar diagnóstico
    public function update(Request $request, $id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $data = $request->validate([
            'mecanico_id'   => 'nullable|exists:mecanicos,id',
            'diagnostico'   => 'nullable|string|max:5000',
            'observacoes'   => 'nullable|string|max:2000',
            'data_previsao' => 'nullable|date',
            'valor_desconto'=> 'nullable|numeric|min:0',
        ]);

        $ordemServico->update($data);
        $ordemServico->recalcularTotais();

        return redirect()->route('os.show', $ordemServico->id)
               ->with('success', 'OS atualizada!');
    }

    public function destroy($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        if ($ordemServico->aprovado_cliente) {
            return back()->with('error', 'Não é possível excluir uma OS já aprovada.');
        }
        $ordemServico->delete();
        return redirect()->route('os.index')->with('success', 'OS removida.');
    }

    // UC007 — Mudar status
    public function atualizarStatus(Request $request, $id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $request->validate([
            'status' => 'required|in:aberta,em_diagnostico,aguardando_aprovacao,aprovada,em_execucao,aguardando_pecas,finalizada,cancelada',
        ]);

        $ordemServico->update(['status' => $request->status]);

        // RN001 — ao finalizar, cria garantia de 90 dias automaticamente
        if ($request->status === 'finalizada') {
            $ordemServico->update(['data_conclusao' => now()]);
            $ordemServico->garantias()->create([
                'descricao'   => 'Garantia padrão de mão de obra (90 dias)',
                'data_inicio' => today(),
                'data_fim'    => today()->addDays(90),
            ]);
        }

        return back()->with('success', 'Status atualizado para: ' . $ordemServico->statusLabel());
    }

    // UC004 — Gerente aprova orçamento
    public function aprovar($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $ordemServico->update([
            'aprovado_cliente' => true,
            'data_aprovacao'   => now(),
            'status'           => 'aprovada',
        ]);

        return back()->with('success', 'Orçamento aprovado! OS liberada para execução.');
    }

    // UC009 — Fechar OS
    public function fechar($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $ordemServico->update([
            'status'         => 'finalizada',
            'data_conclusao' => now(),
        ]);

        return back()->with('success', 'Ordem de serviço finalizada com sucesso!');
    }

    // Autorização do cliente via link/token (RF004)
    public function showAutorizacao(string $token)
    {
        $os = OrdemServico::where('numero', $token)
            ->with(['cliente','veiculo','itens.servico','itens.peca'])
            ->firstOrFail();
        return view('ordens-servico.autorizar', compact('os'));
    }

    public function autorizar(Request $request, string $token)
    {
        $os = OrdemServico::where('numero', $token)->firstOrFail();
        $os->update([
            'aprovado_cliente' => true,
            'data_aprovacao'   => now(),
            'status'           => 'aprovada',
        ]);
        return view('ordens-servico.autorizado', compact('os'));
    }

    // RN004 — Upload de fotos
    public function uploadFotos(Request $request, $id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $request->validate([
            'fotos'   => 'required|array|max:10',
            'fotos.*' => 'image|mimes:jpeg,png,webp|max:5120',
            'tipo'    => 'required|in:entrada,saida,processo',
            'lado'    => 'nullable|in:frontal,traseira,lateral_dir,lateral_esq,interior,outro',
        ]);

        foreach ($request->file('fotos') as $foto) {
            $path = $foto->store("os/{$ordemServico->id}", 'public');
            $ordemServico->fotos()->create([
                'path' => $path,
                'tipo' => $request->tipo,
                'lado' => $request->lado,
            ]);
        }

        return back()->with('success', count($request->file('fotos')) . ' foto(s) salva(s).');
    }

    public function deletarFoto($id, int $foto)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $fotoModel = $ordemServico->fotos()->findOrFail($foto);
        Storage::disk('public')->delete($fotoModel->path);
        $fotoModel->delete();
        return back()->with('success', 'Foto removida.');
    }

    // Imprimir OS (para PDF)
    public function imprimir($id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $ordemServico->load(['cliente','veiculo','mecanico','itens.servico','itens.peca','garantias']);
        return view('ordens-servico.print', compact('ordemServico'));
    }
}
