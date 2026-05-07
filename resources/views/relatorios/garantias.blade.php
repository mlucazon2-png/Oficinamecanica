@extends('layouts.app')
@section('title','Garantias')
@section('breadcrumb','Relatórios / Garantias')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-shield-check me-2"></i>Garantias Acionadas</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>OS</th><th>Cliente</th><th>Descrição</th><th>Válida até</th><th>Situação</th></tr></thead>
            <tbody>
                @forelse($dados as $g)
                <tr>
                    <td class="font-mono small">{{ $g->ordemServico->numero }}</td>
                    <td>{{ $g->ordemServico->cliente->nome }}</td>
                    <td>{{ $g->descricao }}</td>
                    <td>{{ $g->data_fim->format('d/m/Y') }}</td>
                    <td><span class="badge {{ $g->acionada ? 'bg-warning text-dark' : ($g->expirada() ? 'bg-secondary' : 'bg-success') }}">
                        {{ $g->acionada ? 'Acionada' : ($g->expirada() ? 'Expirada' : 'Ativa') }}
                    </span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Sem garantias.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $dados->links() }}</div>
</div>
@endsection
