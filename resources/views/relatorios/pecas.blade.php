@extends('layouts.app')
@section('title','Peças Mais Usadas')
@section('breadcrumb','Relatórios / Peças Mais Usadas')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-box-seam me-2"></i>Peças Mais Utilizadas</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>#</th><th>Peça</th><th>Qtd. Usada</th><th>Valor Total</th></tr></thead>
            <tbody>
                @forelse($dados as $i => $d)
                <tr>
                    <td class="text-muted">{{ $i+1 }}</td>
                    <td>{{ $d->peca->nome ?? '—' }}<br><span class="font-mono small text-muted">{{ $d->peca->codigo ?? '' }}</span></td>
                    <td class="font-mono">{{ $d->total_qtd }}</td>
                    <td class="font-mono">R$ {{ number_format($d->total_valor,2,',','.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
