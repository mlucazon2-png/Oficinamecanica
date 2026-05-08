@extends('layouts.app')
@section('title', $cliente->nome)
@section('breadcrumb', 'Clientes / ' . $cliente->nome)
@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-person me-2"></i>Dados</span>
                <a href="{{ route('clientes.edit',$cliente) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
            </div>
            <div class="card-body">
                <dl class="row small mb-0">
                    <dt class="col-4 text-muted">Nome</dt><dd class="col-8 fw-500">{{ $cliente->nome }}</dd>
                    <dt class="col-4 text-muted">CPF</dt><dd class="col-8 font-mono">{{ $cliente->cpf }}</dd>
                    <dt class="col-4 text-muted">Telefone</dt><dd class="col-8">{{ $cliente->telefone }}</dd>
                    <dt class="col-4 text-muted">E-mail</dt><dd class="col-8">{{ $cliente->email ?? '—' }}</dd>
                    <dt class="col-4 text-muted">Endereço</dt><dd class="col-8">{{ $cliente->endereco ?? '—' }}</dd>
                    <dt class="col-4 text-muted">Cidade</dt><dd class="col-8">{{ $cliente->cidade ?? '—' }} {{ $cliente->estado ?? '' }}</dd>
                </dl>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-car-front me-2"></i>Veículos</span>
                <a href="{{ route('veiculos.create', ['cliente_id'=>$cliente->id]) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i></a>
            </div>
            <div class="card-body p-0">
                @forelse($cliente->veiculos as $v)
                <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <div class="small fw-500">{{ $v->marca }} {{ $v->modelo }} {{ $v->ano }}</div>
                        <span class="font-mono badge bg-light text-dark">{{ $v->placa }}</span>
                    </div>
                    <a href="{{ route('veiculos.edit',$v) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                </div>
                @empty
                <div class="text-center text-muted py-3 small">Nenhum veículo.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clipboard2-check me-2"></i>Últimas Ordens de Serviço</span>
                <a href="{{ route('os.create', ['cliente_id'=>$cliente->id]) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Nova OS
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Número</th><th>Veículo</th><th>Status</th><th>Total</th><th>Data</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($cliente->ordens as $os)
                        <tr>
                            <td class="font-mono small">{{ $os->numero }}</td>
                            <td>{{ $os->veiculo->modelo }} <span class="badge bg-light text-dark font-mono">{{ $os->veiculo->placa }}</span></td>
                            <td><span class="badge badge-{{ $os->status }}">{{ $os->statusLabel() }}</span></td>
                            <td class="font-mono">R$ {{ number_format($os->valor_total,2,',','.') }}</td>
                            <td class="small text-muted">{{ $os->created_at->format('d/m/Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('os.show', $os->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                @if(!$os->aprovado_cliente || $os->status === 'cancelada')
                                <form method="POST" action="{{ route('os.destroy', $os->id) }}" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta OS?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Nenhuma OS ainda.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
