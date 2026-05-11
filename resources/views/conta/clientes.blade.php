@extends('layouts.app')
@section('title', 'Clientes Logados')
@section('breadcrumb', 'Clientes Logados')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-people me-2"></i>Clientes Logados no Sistema</span>
    </div>
    <div class="card-body p-0">
        @if($clientes->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Cidade</th>
                        <th>E-mail (Login)</th>
                        <th>Veículos</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clientes as $c)
                    <tr>
                        <td class="text-muted small">{{ $c->id }}</td>
                        <td><strong>{{ $c->nome }}</strong></td>
                        <td class="font-mono small">{{ $c->cpf }}</td>
                        <td>{{ $c->telefone }}</td>
                        <td>{{ $c->cidade }} {{ $c->estado ? "/ {$c->estado}" : '' }}</td>
                        <td class="small"><span class="badge bg-info text-dark">{{ $c->user?->email }}</span></td>
                        <td><span class="badge bg-secondary">{{ $c->veiculos->count() }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('clientes.show', $c->id) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info m-3">
            <i class="bi bi-info-circle me-2"></i>Nenhum cliente logado no momento.
        </div>
        @endif
    </div>
</div>
@endsection
