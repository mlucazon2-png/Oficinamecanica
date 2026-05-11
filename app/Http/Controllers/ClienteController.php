<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::when($request->busca, fn($q, $b) =>
            $q->where('nome', 'like', "%{$b}%")->orWhere('cpf', 'like', "%{$b}%")
              ->orWhere('telefone', 'like', "%{$b}%")
        )->orderBy('nome')->paginate(20)->withQueryString();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:150',
            'cpf'      => 'required|string|max:14|unique:clientes,cpf',
            'telefone' => 'required|string|max:20',
            'email'    => 'nullable|email|max:150',
            'endereco' => 'nullable|string|max:255',
            'cidade'   => 'nullable|string|max:100',
            'estado'   => 'nullable|string|size:2',
        ]);

        $cliente = Cliente::create($data);
        return redirect()->route('clientes.show', $cliente)
               ->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function show(Cliente $cliente)
    {
        $cliente->load(['veiculos', 'ordens' => fn($q) => $q->latest()->limit(10)]);
        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:150',
            'cpf'      => 'required|string|max:14|unique:clientes,cpf,' . $cliente->id,
            'telefone' => 'required|string|max:20',
            'email'    => 'nullable|email|max:150',
            'endereco' => 'nullable|string|max:255',
            'cidade'   => 'nullable|string|max:100',
            'estado'   => 'nullable|string|size:2',
        ]);

        $cliente->update($data);
        return redirect()->route('clientes.show', $cliente)
               ->with('success', 'Cliente atualizado!');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente removido.');
    }

    public function minhasOs()
    {
        $cliente = Auth::user()->cliente;

        // Se o usuário não tem cadastro de cliente associado, evita 500.
        if (!$cliente) {
            return view('conta.os', ['ordens' => collect()]);
        }

        $ordens = $cliente->ordens()->with(['veiculo'])->latest()->paginate(10);
        return view('conta.os', compact('ordens'));
    }

    public function meusVeiculos()
    {
        $cliente = Auth::user()->cliente;

        // Se o usuário não tem cadastro de cliente associado, evita 500.
        if (!$cliente) {
            return view('conta.veiculos', ['veiculos' => collect()]);
        }

        $veiculos = $cliente->veiculos()->latest()->get();
        return view('conta.veiculos', compact('veiculos'));
    }

    public function clientesLogados()
    {
        // Busca todos os usuários com role 'cliente' que têm um cliente associado
        $clientes = Cliente::whereHas('user', fn($q) => $q->where('role', 'cliente'))
            ->with('user')
            ->orderBy('nome')
            ->get();

        return view('conta.clientes', compact('clientes'));
    }

}
