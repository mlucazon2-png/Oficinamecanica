@extends('layouts.app')
@section('title','Clientes Recorrentes')
@section('breadcrumb','Relatórios / Clientes')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-people me-2"></i>Clientes — Recorrentes vs Novos</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Cliente</th><th>Total de OS</th><th>Perfil</th></tr></thead>
            <tbody>
                @forelse($dados as $c)
                <tr>
                    <td>{{ $c->nome }}</td>
                    <td>{{ $c->ordens_count }}</td>
                    <td><span class="badge {{ $c->ordens_count > 1 ? 'bg-success' : 'bg-info text-dark' }}">
                        {{ $c->ordens_count > 1 ? 'Recorrente' : 'Novo' }}
                    </span></td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $dados->links() }}</div>
</div>
@endsection
