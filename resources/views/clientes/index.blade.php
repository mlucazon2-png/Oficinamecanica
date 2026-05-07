{{-- ════════════════════════════════════════════════════
    resources/views/clientes/index.blade.php
════════════════════════════════════════════════════ --}}
@extends('layouts.app')
@section('title','Clientes')
@section('breadcrumb','Clientes')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-people me-2"></i>Clientes</span>
        <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Novo Cliente
        </a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="busca" class="form-control" placeholder="Buscar por nome, CPF ou telefone…" value="{{ request('busca') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Nome</th><th>CPF</th><th>Telefone</th><th>Cidade</th><th>OS</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $c)
                    <tr>
                        <td class="text-muted small">{{ $c->id }}</td>
                        <td><a href="{{ route('clientes.show',$c) }}" class="fw-500 text-decoration-none">{{ $c->nome }}</a></td>
                        <td class="font-mono small">{{ $c->cpf }}</td>
                        <td>{{ $c->telefone }}</td>
                        <td>{{ $c->cidade }} {{ $c->estado ? "/ {$c->estado}" : '' }}</td>
                        <td><span class="badge bg-secondary">{{ $c->ordens_count ?? 0 }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('clientes.show',$c) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('clientes.edit',$c) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('clientes.destroy',$c) }}" class="d-inline" onsubmit="return confirm('Excluir cliente?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Nenhum cliente encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $clientes->links() }}</div>
</div>
@endsection


{{-- ════════════════════════════════════════════════════
    resources/views/clientes/create.blade.php
    (salvar como create.blade.php e edit.blade.php — mudar @section title e action)
════════════════════════════════════════════════════ --}}
{{--
@extends('layouts.app')
@section('title', isset($cliente) ? 'Editar Cliente' : 'Novo Cliente')
@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header">
        <i class="bi bi-person-plus me-2"></i>{{ isset($cliente) ? 'Editar' : 'Novo' }} Cliente
    </div>
    <div class="card-body">
        <form method="POST" action="{{ isset($cliente) ? route('clientes.update',$cliente) : route('clientes.store') }}">
            @csrf
            @if(isset($cliente)) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nome completo *</label>
                    <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                           value="{{ old('nome', $cliente->nome ?? '') }}" required>
                    @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">CPF *</label>
                    <input type="text" name="cpf" class="form-control font-mono @error('cpf') is-invalid @enderror"
                           value="{{ old('cpf', $cliente->cpf ?? '') }}" placeholder="000.000.000-00" required>
                    @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telefone *</label>
                    <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $cliente->telefone ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">E-mail</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email ?? '') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Endereço</label>
                    <input type="text" name="endereco" class="form-control" value="{{ old('endereco', $cliente->endereco ?? '') }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Cidade</label>
                    <input type="text" name="cidade" class="form-control" value="{{ old('cidade', $cliente->cidade ?? '') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <input type="text" name="estado" class="form-control" value="{{ old('estado', $cliente->estado ?? '') }}" maxlength="2" placeholder="CE">
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Salvar
                </button>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
--}}
