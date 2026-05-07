@extends('layouts.app')
@section('title','Orçamentos')
@section('breadcrumb','Relatórios / Orçamentos')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-bar-chart-line me-2"></i>Orçamentos Aprovados vs Rejeitados</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Situação</th><th>Quantidade</th><th>Valor Total</th></tr></thead>
            <tbody>
                @forelse($dados as $d)
                <tr>
                    <td><span class="badge {{ $d->aprovado_cliente ? 'bg-success' : 'bg-danger' }}">
                        {{ $d->aprovado_cliente ? 'Aprovado' : 'Não aprovado' }}
                    </span></td>
                    <td>{{ $d->total }}</td>
                    <td class="font-mono">R$ {{ number_format($d->valor,2,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
