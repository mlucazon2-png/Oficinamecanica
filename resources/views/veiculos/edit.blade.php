@extends('layouts.app')
@section('title', isset($veiculo) ? 'Editar Veículo' : 'Novo Veículo')
@section('breadcrumb', isset($veiculo) ? 'Editar Veículo' : 'Novo Veículo')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-car-front me-2"></i>{{ isset($veiculo) ? 'Editar' : 'Novo' }} Veículo</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($veiculo) ? route('veiculos.update',$veiculo) : route('veiculos.store') }}">
            @csrf @if(isset($veiculo)) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Cliente *</label>
                    <select name="cliente_id" class="form-select" {{ isset($veiculo) ? 'disabled' : 'required' }}>
                        <option value="">Selecione…</option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id', $veiculo->cliente_id??'') == $c->id ? 'selected':'' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Placa *</label>
                    <input type="text" name="placa" class="form-control font-mono text-uppercase"
                           value="{{ old('placa',$veiculo->placa??'') }}" placeholder="ABC1D23" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ano *</label>
                    <input type="number" name="ano" class="form-control font-mono"
                           value="{{ old('ano',$veiculo->ano??date('Y')) }}" min="1900" max="{{ date('Y')+1 }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Marca *</label>
                    <input type="text" name="marca" class="form-control" value="{{ old('marca',$veiculo->marca??'') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Modelo *</label>
                    <input type="text" name="modelo" class="form-control" value="{{ old('modelo',$veiculo->modelo??'') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Cor</label>
                    <input type="text" name="cor" class="form-control" value="{{ old('cor',$veiculo->cor??'') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Chassi</label>
                    <input type="text" name="chassi" class="form-control font-mono" value="{{ old('chassi',$veiculo->chassi??'') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Km atual</label>
                    <input type="number" name="km_atual" class="form-control font-mono" min="0"
                           value="{{ old('km_atual',$veiculo->km_atual??0) }}">
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
