@extends('layouts.app')
@section('title', 'Novo Veículo')
@section('breadcrumb', 'Novo Veículo')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-car-front me-2"></i>Novo Veículo</div>
    <div class="card-body">
        <form method="POST" action="{{ route('veiculos.store') }}" enctype="multipart/form-data">

            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Placa *</label>
                    <input type="text" name="placa" class="form-control font-mono text-uppercase @error('placa') is-invalid @enderror"
                           value="{{ old('placa') }}" placeholder="ABC1D23" required>
                    @error('placa')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Ano *</label>
                    <input type="number" name="ano" class="form-control font-mono @error('ano') is-invalid @enderror"
                           value="{{ old('ano', date('Y')) }}" min="1900" max="{{ date('Y')+1 }}" required>
                    @error('ano')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                @php
                    $veiculo = (object)['marca' => null, 'modelo' => null];
                    $modelos = collect();
                    $marcaSelecionadaId = null;
                @endphp

                @include('veiculos._marca_modelo_selects', ['marcas' => $marcas, 'modelos' => $modelos, 'veiculo' => $veiculo, 'marcaSelecionadaId' => $marcaSelecionadaId])

                <div class="col-md-4">
                    <label class="form-label">Cor</label>
                    <input type="text" name="cor" class="form-control" value="{{ old('cor') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Km atual</label>
                    <input type="number" name="km_atual" class="form-control font-mono" min="0"
                           value="{{ old('km_atual', 0) }}">
                </div>



                <div class="col-12">
                    <label class="form-label">Foto do carro</label>
                    <input type="file" name="foto" class="form-control" accept="image/*" @error('foto') aria-invalid="true" @enderror>
                    @error('foto')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Selecione uma imagem do veículo (parecido com o upload da OS).</div>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                <a href="{{ route('veiculos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

