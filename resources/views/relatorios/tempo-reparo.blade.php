@extends('layouts.app')
@section('title','Tempo de Reparo')
@section('breadcrumb','Relatórios / Tempo de Reparo')
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-stopwatch me-2"></i>Tempo Médio de Reparo por Mecânico</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="table-light"><tr><th>Mecânico</th><th>OS Concluídas</th><th>Média de horas</th></tr></thead>
            <tbody>
                @forelse($dados as $d)
                <tr>
                    <td>{{ $d->mecanico->nome ?? '—' }}</td>
                    <td>{{ $d->total }}</td>
                    <td class="font-mono">{{ number_format($d->media_horas,1) }}h</td>
                </tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Sem dados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
