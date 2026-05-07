@extends('layouts.app')
@section('title', isset($cliente) ? 'Editar Cliente' : 'Novo Cliente')
@section('breadcrumb', isset($cliente) ? 'Editar Cliente' : 'Novo Cliente')

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
                    <input type="text" name="estado" class="form-control" maxlength="2" placeholder="CE"
                           value="{{ old('estado', $cliente->estado ?? '') }}">
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
