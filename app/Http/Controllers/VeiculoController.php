<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\Cliente;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    public function index(Request $request)
    {
        $veiculos = Veiculo::with('cliente')
            ->when($request->busca, fn($q, $b) =>
                $q->where('placa', 'like', "%{$b}%")
                  ->orWhere('modelo', 'like', "%{$b}%")
                  ->orWhereHas('cliente', fn($c) => $c->where('nome', 'like', "%{$b}%"))
            )->latest()->paginate(20)->withQueryString();

        return view('veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        return view('veiculos.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'placa'      => 'required|string|max:10|unique:veiculos,placa',
            'marca'      => 'required|string|max:80',
            'modelo'     => 'required|string|max:80',
            'ano'        => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'cor'        => 'nullable|string|max:50',
            'chassi'     => 'nullable|string|max:50',
            'km_atual'   => 'nullable|integer|min:0',
        ]);

        // Obter cliente do usuário autenticado
        $cliente = auth()->user()->cliente;
        if (!$cliente) {
            return back()->withErrors(['cliente' => 'Usuário não tem perfil de cliente.']);
        }

        $data['cliente_id'] = $cliente->id;
        $veiculo = Veiculo::create($data);
        return redirect()->route('veiculos.show', $veiculo->id)
               ->with('success', 'Veículo cadastrado!');
    }

    public function show($id)
    {
        $veiculo = Veiculo::findOrFail($id);
        $veiculo->load(['cliente', 'ordens' => fn($q) => $q->latest()->limit(10)]);
        $ordens = $veiculo->ordens;
        return view('veiculos.show', compact('veiculo', 'ordens'));
    }

    public function edit($id)
    {
        $veiculo = Veiculo::findOrFail($id);
        return view('veiculos.edit', compact('veiculo'));
    }

    public function update(Request $request, $id)
    {
        $veiculo = Veiculo::findOrFail($id);
        $data = $request->validate([
            'placa'    => 'required|string|max:10|unique:veiculos,placa,' . $veiculo->id,
            'marca'    => 'required|string|max:80',
            'modelo'   => 'required|string|max:80',
            'ano'      => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'cor'      => 'nullable|string|max:50',
            'chassi'   => 'nullable|string|max:50',
            'km_atual' => 'nullable|integer|min:0',
        ]);

        $veiculo->update($data);
        return redirect()->route('veiculos.show', $veiculo->id)->with('success', 'Veículo atualizado!');
    }

    public function destroy($id)
    {
        $veiculo = Veiculo::findOrFail($id);
        $veiculo->delete();
        return redirect()->route('veiculos.index')->with('success', 'Veículo removido.');
    }

    // Retorna veículos de um cliente em JSON (para select dinâmico na OS)
    public function porCliente(Cliente $cliente)
    {
        return response()->json($cliente->veiculos()->select('id','placa','marca','modelo','ano')->get());
    }
}
