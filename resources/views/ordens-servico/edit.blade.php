{{-- ════ ordens-servico/edit.blade.php ════ --}}
@extends('layouts.app')
@section('title','Editar OS')
@section('breadcrumb','Editar OS')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header">
        <i class="bi bi-pencil me-2"></i>Editar OS — <span class="font-mono">{{ $ordemServico->numero }}</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('os.update',$ordemServico->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Mecânico</label>
                    <select name="mecanico_id" class="form-select">
                        <option value="">A definir</option>
                        @foreach($mecanicos as $m)
                        <option value="{{ $m->id }}" {{ $ordemServico->mecanico_id == $m->id ? 'selected':'' }}>{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Previsão de entrega</label>
                    <input type="date" name="data_previsao" class="form-control"
                           value="{{ old('data_previsao', $ordemServico->data_previsao?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Desconto (R$)</label>
                    <input type="number" name="valor_desconto" class="form-control font-mono" step="0.01" min="0"
                           value="{{ old('valor_desconto',$ordemServico->valor_desconto) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Diagnóstico</label>
                    <textarea name="diagnostico" class="form-control" rows="4">{{ old('diagnostico',$ordemServico->diagnostico) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Observações</label>
                    <textarea name="observacoes" class="form-control" rows="3">{{ old('observacoes',$ordemServico->observacoes) }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                <a href="{{ route('os.show',$ordemServico->id) }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
