<?php

namespace App\Http\Controllers;

use App\Models\ItemOs;
use App\Models\OrdemServico;
use App\Models\Peca;
use Illuminate\Http\Request;

class ItemOsController extends Controller
{
    public function store(Request $request, $id)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $data = $request->validate([
            'tipo'           => 'required|in:servico,peca',
            'servico_id'     => 'required_if:tipo,servico|nullable|exists:servicos,id',
            'peca_id'        => 'required_if:tipo,peca|nullable|exists:pecas,id',
            'descricao'      => 'required|string|max:255',
            'quantidade'     => 'required|numeric|min:0.001',
            'valor_unitario' => 'required|numeric|min:0',
        ]);

        if ($data['tipo'] === 'peca' && isset($data['peca_id'])) {
            $peca = Peca::findOrFail($data['peca_id']);
            if ($peca->estoque < $data['quantidade']) {
                return back()->with('error', "Estoque insuficiente. Disponível: {$peca->estoque} {$peca->unidade}.");
            }
            $peca->decrement('estoque', $data['quantidade']);
        }

        $ordemServico->itens()->create($data);
        return back()->with('success', 'Item adicionado!');
    }

    public function update(Request $request, $id, $itemId)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $item = ItemOs::findOrFail($itemId);
        $data = $request->validate([
            'descricao'      => 'required|string|max:255',
            'quantidade'     => 'required|numeric|min:0.001',
            'valor_unitario' => 'required|numeric|min:0',
        ]);
        $item->update($data);
        return back()->with('success', 'Item atualizado!');
    }

    public function destroy($id, $itemId)
    {
        $ordemServico = OrdemServico::findOrFail($id);
        $item = ItemOs::findOrFail($itemId);
        $item->delete();
        return back()->with('success', 'Item removido.');
    }
}
