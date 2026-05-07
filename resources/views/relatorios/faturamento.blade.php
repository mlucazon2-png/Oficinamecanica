@extends('layouts.app')
@section('title','Faturamento')
@section('breadcrumb','Relatórios / Faturamento')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-cash-stack me-2"></i>Faturamento Mensal — {{ $ano }}</span>
        <form method="GET" class="d-flex gap-2">
            <input type="number" name="ano" class="form-control form-control-sm" value="{{ $ano }}" min="2020" max="{{ date('Y') }}" style="width:100px">
            <button class="btn btn-sm btn-outline-secondary">Filtrar</button>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Mês</th><th>OS Finalizadas</th><th>Faturamento</th></tr></thead>
            <tbody>
                @php $meses = ['','Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez']; @endphp
                @forelse($dados as $d)
                <tr>
                    <td>{{ $meses[$d->mes] }}</td>
                    <td>{{ $d->qtd }}</td>
                    <td class="font-mono fw-500">R$ {{ number_format($d->total,2,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Sem dados para {{ $ano }}.</td></tr>
                @endforelse
            </tbody>
            @if($dados->count())
            <tfoot class="table-light fw-600">
                <tr>
                    <td>Total</td>
                    <td>{{ $dados->sum('qtd') }}</td>
                    <td class="font-mono">R$ {{ number_format($dados->sum('total'),2,',','.') }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection
