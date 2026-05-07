@extends('layouts.app')
@section('title','OS por Status')
@section('breadcrumb','Relatórios / OS por Status')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-clipboard2-data me-2"></i>Ordens de Serviço por Status</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Status</th><th>Quantidade</th></tr></thead>
            <tbody>
                @forelse($dados as $d)
                <tr>
                    <td><span class="badge badge-{{ $d->status }}">{{ ucfirst(str_replace('_',' ',$d->status)) }}</span></td>
                    <td class="font-mono fw-500">{{ $d->total }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
