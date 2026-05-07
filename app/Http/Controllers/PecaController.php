<?php

namespace App\Http\Controllers;

use App\Models\Peca;
use Illuminate\Http\Request;

class PecaController extends Controller
{
    public function index(Request $request)
    {
        $pecas = Peca::when($request->busca, fn($q, $b) =>
                $q->where('nome', 'like', "%{$b}%")->orWhere('codigo', 'like', "%{$b}%")
            )
            ->when($request->estoque_baixo, fn($q) => $q->whereColumn('estoque', '<=', 'estoque_minimo'))
            ->orderBy('nome')->paginate(20)->withQueryString();
        $criticas = Peca::whereColumn('estoque', '<=', 'estoque_minimo')->count();
        return view('pecas.index', compact('pecas', 'criticas'));
    }

    public function create() { return view('pecas.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:150',
            'codigo'         => 'nullable|string|max:80|unique:pecas,codigo',
            'fabricante'     => 'nullable|string|max:100',
            'preco_custo'    => 'required|numeric|min:0',
            'preco_venda'    => 'required|numeric|min:0',
            'estoque'        => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'unidade'        => 'required|string|max:20',
        ]);
        Peca::create($data);
        return redirect()->route('pecas.index')->with('success', 'Peça cadastrada!');
    }

    public function show(Peca $peca) { return view('pecas.show', compact('peca')); }
    public function edit(Peca $peca) { return view('pecas.edit', compact('peca')); }

    public function update(Request $request, Peca $peca)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:150',
            'codigo'         => 'nullable|string|max:80|unique:pecas,codigo,' . $peca->id,
            'fabricante'     => 'nullable|string|max:100',
            'preco_custo'    => 'required|numeric|min:0',
            'preco_venda'    => 'required|numeric|min:0',
            'estoque'        => 'required|integer|min:0',
            'estoque_minimo' => 'required|integer|min:0',
            'unidade'        => 'required|string|max:20',
        ]);
        $peca->update($data);
        return redirect()->route('pecas.index')->with('success', 'Peça atualizada!');
    }

    public function destroy(Peca $peca)
    {
        $peca->delete();
        return redirect()->route('pecas.index')->with('success', 'Peça removida.');
    }

    public function ajustarEstoque(Request $request, Peca $peca)
    {
        $request->validate(['quantidade' => 'required|integer', 'motivo' => 'required|string|max:255']);
        $peca->increment('estoque', $request->quantidade);
        return back()->with('success', 'Estoque ajustado!');
    }
}
