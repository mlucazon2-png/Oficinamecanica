<?php

namespace App\Http\Controllers;

use App\Models\Mecanico;
use Illuminate\Http\Request;

class MecanicoController extends Controller
{
    public function index(Request $request)
    {
        $mecanicos = Mecanico::with('user')
            ->when($request->busca, fn($q, $b) =>
                $q->where('nome', 'like', "%{$b}%")
            )
            ->orderBy('nome')
            ->paginate(20)
            ->withQueryString();

        return view('mecanicos.index', compact('mecanicos'));
    }

    public function create() { return view('mecanicos.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'          => 'required|string|max:150',
            'cpf'           => 'nullable|string|max:14|unique:mecanicos,cpf',
            'telefone'      => 'nullable|string|max:20',
            'especialidade' => 'nullable|string|max:100',
        ]);

        Mecanico::create($data);
        return redirect()->route('mecanicos.index')->with('success', 'Mecânico cadastrado!');
    }

    public function show(Mecanico $mecanico)
    {
        $mecanico->load(['ordens' => fn($q) => $q->latest()->limit(10)]);
        return view('mecanicos.show', compact('mecanico'));
    }

    public function edit(Mecanico $mecanico) { return view('mecanicos.edit', compact('mecanico')); }

    public function update(Request $request, Mecanico $mecanico)
    {
        $data = $request->validate([
            'nome'          => 'required|string|max:150',
            'cpf'           => 'nullable|string|max:14|unique:mecanicos,cpf,' . $mecanico->id,
            'telefone'      => 'nullable|string|max:20',
            'especialidade' => 'nullable|string|max:100',
        ]);

        $mecanico->update($data);
        return redirect()->route('mecanicos.index')->with('success', 'Mecânico atualizado!');
    }

    public function destroy(Mecanico $mecanico)
    {
        $mecanico->delete();
        return redirect()->route('mecanicos.index')->with('success', 'Mecânico removido.');
    }

    public function toggle(Mecanico $mecanico)
    {
        $mecanico->update(['ativo' => !$mecanico->ativo]);
        $msg = $mecanico->ativo ? 'Mecânico ativado.' : 'Mecânico desativado.';
        return back()->with('success', $msg);
    }
}
