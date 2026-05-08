@extends('layouts.app')
@section('title', 'Meus Veículos')
@section('breadcrumb', 'Meus Veículos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div><i class="bi bi-car-front me-2"></i>Veículos Cadastrados</div>
                <a href="{{ route('veiculos.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Novo Veículo
                </a>
            </div>
            <div class="card-body p-0">
                @if($veiculos->count() > 0)
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Placa</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Ano</th>
                            <th>Cor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($veiculos as $v)
                        <tr>
                            <td class="font-mono fw-500">{{ $v->placa }}</td>
                            <td>{{ $v->marca }}</td>
                            <td>{{ $v->modelo }}</td>
                            <td>{{ $v->ano }}</td>
                            <td>{{ $v->cor ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('veiculos.show', $v->id) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-info m-3">
                    <i class="bi bi-info-circle me-2"></i>Você ainda não possui veículos cadastrados.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
