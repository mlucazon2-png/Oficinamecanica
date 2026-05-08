@extends('layouts.app')
@section('title', 'Editar Veículo')
@section('breadcrumb', 'Editar Veículo')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-car-front me-2"></i>Editar Veículo</div>
    <div class="card-body">
        <form method="POST" action="{{ route('veiculos.update', $veiculo->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cliente</label>
                    <div class="form-control-plaintext fw-500">{{ $veiculo->cliente->nome }}</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Placa *</label>
                    <input type="text" name="placa" class="form-control font-mono text-uppercase @error('placa') is-invalid @enderror"
                           value="{{ old('placa', $veiculo->placa) }}" placeholder="ABC1D23" required>
                    @error('placa')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ano *</label>
                    <input type="number" name="ano" class="form-control font-mono @error('ano') is-invalid @enderror"
                           value="{{ old('ano', $veiculo->ano) }}" min="1900" max="{{ date('Y')+1 }}" required>
                    @error('ano')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Marca *</label>
                    <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror" 
                           value="{{ old('marca', $veiculo->marca) }}" required>
                    @error('marca')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Modelo *</label>
                    <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror" 
                           value="{{ old('modelo', $veiculo->modelo) }}" required>
                    @error('modelo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cor</label>
                    <input type="text" name="cor" class="form-control" value="{{ old('cor', $veiculo->cor) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Chassi</label>
                    <input type="text" name="chassi" class="form-control font-mono" value="{{ old('chassi', $veiculo->chassi) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Km atual</label>
                    <input type="number" name="km_atual" class="form-control font-mono" min="0"
                           value="{{ old('km_atual', $veiculo->km_atual) }}">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                <a href="{{ route('veiculos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
