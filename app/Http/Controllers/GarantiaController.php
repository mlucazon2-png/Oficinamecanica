<?php

namespace App\Http\Controllers;

use App\Models\Garantia;
use Illuminate\Http\Request;

class GarantiaController extends Controller
{
    public function index()
    {
        $garantias = Garantia::with('ordemServico.cliente')->orderByDesc('data_fim')->paginate(20);
        return view('garantias.index', compact('garantias'));
    }

    public function show(Garantia $garantia)
    {
        $garantia->load('ordemServico.cliente');
        return view('garantias.show', compact('garantia'));
    }

    public function edit(Garantia $garantia)
    {
        return view('garantias.edit', compact('garantia'));
    }

    public function update(Request $request, Garantia $garantia)
    {
        $data = $request->validate([
            'descricao'  => 'required|string',
            'data_fim'   => 'required|date',
            'observacao' => 'nullable|string',
        ]);
        $garantia->update($data);
        return redirect()->route('garantias.show', $garantia)->with('success', 'Garantia atualizada!');
    }

    public function acionar(Request $request, Garantia $garantia)
    {
        $request->validate(['observacao' => 'required|string|max:1000']);
        if ($garantia->expirada()) {
            return back()->with('error', 'Garantia expirada em ' . $garantia->data_fim->format('d/m/Y') . '.');
        }
        $garantia->update([
            'acionada'         => true,
            'data_acionamento' => now(),
            'observacao'       => $request->observacao,
        ]);
        return back()->with('success', 'Garantia acionada!');
    }
}
