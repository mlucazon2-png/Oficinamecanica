@extends('layouts.app')
@section('title','Produtividade')
@section('breadcrumb','Relatórios / Produtividade')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-person-gear me-2"></i>Produtividade dos Mecânicos</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Mecânico</th><th>OS Total</th><th>OS esse mês</th><th>Faturamento</th></tr></thead>
            <tbody>
                @forelse($dados as $m)
                <tr>
                    <td>{{ $m->nome }}</td>
                    <td>{{ $m->os_total }}</td>
                    <td>{{ $m->os_mes }}</td>
                    <td class="font-mono">R$ {{ number_format($m->faturamento ?? 0,2,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
