<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use App\Models\Cliente;
use App\Models\MarcasVeiculos;
use App\Models\ModelosVeiculos;
use Illuminate\Http\Request;


class VeiculoController extends Controller
{
    public function index(Request $request)
    {
        // Cliente só enxerga seus próprios veículos.
        // Gerentes/atendentes podem enxergar tudo.
        $user = auth()->user();
        $isClient = $user && $user->isCliente();


        $query = Veiculo::query()->with('cliente');

        if ($isClient) {
            $cliente = auth()->user()->cliente;
            $query->where('cliente_id', $cliente?->id);
        }

        $query->when($request->busca, fn($q, $b) =>
            $q->where(function ($inner) use ($b) {
                $inner->where('placa', 'like', "%{$b}%")
                    ->orWhere('modelo', 'like', "%{$b}%")
                    ->orWhereHas('cliente', fn($c) => $c->where('nome', 'like', "%{$b}%"));
            })
        );

        $veiculos = $query->latest()->paginate(20)->withQueryString();

        return view('veiculos.index', compact('veiculos'));
    }


    public function create()
    {
        $marcas = MarcasVeiculos::orderBy('nome')->get(['id', 'nome']);
        return view('veiculos.create', compact('marcas'));
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'placa'      => 'required|string|max:10|unique:veiculos,placa',
            'marca'      => 'required|string|max:80',
            'modelo'     => 'required|string|max:80',
            'ano'        => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'cor'        => 'nullable|string|max:50',
            'km_atual'   => 'nullable|integer|min:0',
            'foto'       => 'nullable|image|mimes:jpeg,png,webp|max:5120',
        ]);

        // Obter cliente do usuário autenticado
        $cliente = auth()->user()->cliente;

        if (!$cliente) {
            // Se acontecer, vamos bloquear para evitar dados inválidos.
            return back()->withErrors(['cliente' => 'Usuário não tem perfil de cliente.']);
        }





        // Upload da foto do carro (opcional)
        // Obs: a coluna no banco/migration ainda não existe para salvar a foto.
        // Por isso, por enquanto apenas valida o arquivo e não persiste.
        // (Para persistir de fato, precisa adicionar coluna e ajustar model/migrations.)
        if ($request->hasFile('foto')) {
            $request->file('foto')->store('veiculos/' . $cliente->id, 'public');
        }

        $data['cliente_id'] = $cliente->id;
        $veiculo = Veiculo::create($data);
        return redirect()->route('veiculos.show', $veiculo->id)
               ->with('success', 'Veículo cadastrado!');

    }


    public function show($id)
    {
        $veiculo = Veiculo::with('cliente')->findOrFail($id);

        // Cliente só acessa seu próprio veículo
        if (auth()->check() && auth()->user()->isCliente() && $veiculo->cliente_id !== auth()->user()->cliente?->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $veiculo->load(['ordens' => fn($q) => $q->latest()->limit(10)]);
        $ordens = $veiculo->ordens;

        return view('veiculos.show', compact('veiculo', 'ordens'));
    }


    public function edit($id)
    {
        $veiculo = Veiculo::findOrFail($id);

        // Cliente não pode editar veículo de outro cliente
        if (auth()->check() && auth()->user()->isCliente() && $veiculo->cliente_id !== auth()->user()->cliente?->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $marcas = MarcasVeiculos::orderBy('nome')->get(['id', 'nome']);

        // Como no seu schema atual `veiculos.marca`/`veiculos.modelo` são strings,
        // tentamos resolver o ID da marca selecionada apenas para carregar os modelos.
        $marcaSelecionadaId = $marcas->firstWhere('nome', $veiculo->marca)?->id;

        $modelos = $marcaSelecionadaId
            ? ModelosVeiculos::where('marca_id', $marcaSelecionadaId)->orderBy('nome')->get(['id', 'nome'])
            : collect();

        return view('veiculos.edit', compact('veiculo', 'marcas', 'modelos', 'marcaSelecionadaId'));
    }



    public function update(Request $request, $id)
    {
        $veiculo = Veiculo::findOrFail($id);

        // Cliente só pode atualizar seu próprio veículo
        if (auth()->check() && auth()->user()->isCliente() && $veiculo->cliente_id !== auth()->user()->cliente?->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $data = $request->validate([
            'placa'    => 'required|string|max:10|unique:veiculos,placa,' . $veiculo->id,
            'marca'    => 'required|string|max:80',
            'modelo'   => 'required|string|max:80',
            'ano'      => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'cor'      => 'nullable|string|max:50',
            'km_atual' => 'nullable|integer|min:0',

        ]);

        $veiculo->update($data);
        return redirect()->route('veiculos.show', $veiculo->id)->with('success', 'Veículo atualizado!');
    }


    public function destroy($id)
    {
        $veiculo = Veiculo::findOrFail($id);

        // Cliente só pode remover seu próprio veículo
        if (auth()->check() && auth()->user()->isCliente() && $veiculo->cliente_id !== auth()->user()->cliente?->id) {
            abort(403, 'Acesso não autorizado.');
        }

        $veiculo->delete();
        return redirect()->route('veiculos.index')->with('success', 'Veículo removido.');
    }


    public function modelosPorMarca(int $marcaId)
    {
        $modelos = ModelosVeiculos::where('marca_id', $marcaId)
            ->orderBy('nome')
            ->get(['id', 'nome']);

        return response()->json($modelos);
    }

    // Retorna veículos de um cliente em JSON (para select dinâmico na OS)
    public function porCliente(Cliente $cliente)
    {
        return response()->json($cliente->veiculos()->select('id','placa','marca','modelo','ano')->get());
    }
}

