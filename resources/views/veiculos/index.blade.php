@extends('layouts.app')
@section('title','Veículos')
@section('breadcrumb','Veículos')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-car-front me-2"></i>Veículos</span>
        <a href="{{ route('veiculos.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Novo Veículo</a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="busca" class="form-control" placeholder="Placa, modelo ou cliente…" value="{{ request('busca') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                <a href="{{ route('veiculos.index') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Placa</th><th>Veículo</th><th>Ano</th><th>Cor</th><th>Cliente</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($veiculos as $v)
                <tr>
                    <td class="font-mono fw-500">{{ $v->placa }}</td>
                    <td>{{ $v->marca }} {{ $v->modelo }}</td>
                    <td>{{ $v->ano }}</td>
                    <td>{{ $v->cor ?? '—' }}</td>
                    <td><a href="{{ route('clientes.show',$v->cliente) }}" class="text-decoration-none">{{ $v->cliente->nome }}</a></td>
                    <td class="text-end">
                        <a href="{{ route('veiculos.edit',$v->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('veiculos.destroy',$v->id) }}" class="d-inline" onsubmit="return confirm('Excluir veículo?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhum veículo encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $veiculos->links() }}</div>
</div>
@endsection
