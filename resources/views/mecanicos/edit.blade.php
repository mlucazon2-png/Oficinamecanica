@extends('layouts.app')
@section('title', isset($mecanico) ? 'Editar Mecânico' : 'Novo Mecânico')
@section('breadcrumb', isset($mecanico) ? 'Editar Mecânico' : 'Novo Mecânico')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6">
<div class="card">
    <div class="card-header"><i class="bi bi-person-gear me-2"></i>{{ isset($mecanico) ? 'Editar' : 'Novo' }} Mecânico</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($mecanico) ? route('mecanicos.update',$mecanico) : route('mecanicos.store') }}">
            @csrf @if(isset($mecanico)) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nome *</label>
                    <input type="text" name="nome" class="form-control" value="{{ old('nome',$mecanico->nome??'') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">CPF</label>
                    <input type="text" name="cpf" class="form-control font-mono" value="{{ old('cpf',$mecanico->cpf??'') }}" placeholder="000.000.000-00">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telefone</label>
                    <input type="text" name="telefone" class="form-control" value="{{ old('telefone',$mecanico->telefone??'') }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Especialidade</label>
                    <select name="especialidade" class="form-select" required>
                        <option value="" @selected(old('especialidade',$mecanico->especialidade ?? '') === '')>Selecione a especialidade...</option>
                        @foreach(['Funilaria','Mecânica Geral','Pintura'] as $especialidade)
                            <option value="{{ $especialidade }}" @selected(old('especialidade',$mecanico->especialidade ?? '') === $especialidade)>{{ $especialidade }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                <a href="{{ route('mecanicos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
