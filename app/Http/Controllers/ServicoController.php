<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function index(Request $request)
    {
        $servicos = Servico::when($request->busca, fn($q, $b) =>
            $q->where('nome', 'like', "%{$b}%")->orWhere('categoria', 'like', "%{$b}%")
        )->orderBy('nome')->paginate(20)->withQueryString();
        return view('servicos.index', compact('servicos'));
    }

    public function create() { return view('servicos.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:150',
            'descricao'      => 'nullable|string',
            'categoria'      => 'nullable|string|max:80',
            'valor_mao_obra' => 'required|numeric|min:0',
            'tempo_estimado' => 'nullable|integer|min:1',
        ]);
        Servico::create($data);
        return redirect()->route('servicos.index')->with('success', 'Serviço cadastrado!');
    }

    public function show(Servico $servico) { return view('servicos.show', compact('servico')); }
    public function edit(Servico $servico) { return view('servicos.edit', compact('servico')); }

    public function update(Request $request, Servico $servico)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:150',
            'descricao'      => 'nullable|string',
            'categoria'      => 'nullable|string|max:80',
            'valor_mao_obra' => 'required|numeric|min:0',
            'tempo_estimado' => 'nullable|integer|min:1',
        ]);
        $servico->update($data);
        return redirect()->route('servicos.index')->with('success', 'Serviço atualizado!');
    }

    public function destroy(Servico $servico)
    {
        $servico->delete();
        return redirect()->route('servicos.index')->with('success', 'Serviço removido.');
    }
}
