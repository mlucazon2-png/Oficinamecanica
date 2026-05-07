@extends('layouts.app')
@section('title', isset($servico) ? 'Editar Serviço' : 'Novo Serviço')
@section('breadcrumb', isset($servico) ? 'Editar Serviço' : 'Novo Serviço')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card">
    <div class="card-header"><i class="bi bi-tools me-2"></i>{{ isset($servico) ? 'Editar' : 'Novo' }} Serviço</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($servico) ? route('servicos.update',$servico) : route('servicos.store') }}">
            @csrf @if(isset($servico)) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nome *</label>
                    <input type="text" name="nome" class="form-control" value="{{ old('nome',$servico->nome??'') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Categoria</label>
                    <select name="categoria" class="form-select">
                        <option value="">—</option>
                        @foreach(['mecanica','eletrica','funilaria','ar-condicionado','revisao','outros'] as $cat)
                        <option value="{{ $cat }}" {{ old('categoria',$servico->categoria??'') == $cat ? 'selected':'' }}>{{ ucfirst($cat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tempo estimado (min)</label>
                    <input type="number" name="tempo_estimado" class="form-control font-mono" min="1"
                           value="{{ old('tempo_estimado',$servico->tempo_estimado??'') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Valor da Mão de Obra *</label>
                    <div class="input-group"><span class="input-group-text">R$</span>
                    <input type="number" name="valor_mao_obra" class="form-control font-mono" step="0.01" min="0"
                           value="{{ old('valor_mao_obra',$servico->valor_mao_obra??'') }}" required></div>
                </div>
                <div class="col-12">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3">{{ old('descricao',$servico->descricao??'') }}</textarea>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                <a href="{{ route('servicos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
