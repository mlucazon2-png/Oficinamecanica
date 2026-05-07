{{-- ════ pecas/create.blade.php (usar o mesmo como edit.blade.php) ════ --}}
@extends('layouts.app')
@section('title', isset($peca) ? 'Editar Peça' : 'Nova Peça')
@section('breadcrumb', isset($peca) ? 'Editar Peça' : 'Nova Peça')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card">
    <div class="card-header"><i class="bi bi-box-seam me-2"></i>{{ isset($peca) ? 'Editar' : 'Nova' }} Peça</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($peca) ? route('pecas.update',$peca) : route('pecas.store') }}">
            @csrf @if(isset($peca)) @method('PUT') @endif
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nome *</label>
                    <input type="text" name="nome" class="form-control" value="{{ old('nome',$peca->nome??'') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Código</label>
                    <input type="text" name="codigo" class="form-control font-mono" value="{{ old('codigo',$peca->codigo??'') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Fabricante</label>
                    <input type="text" name="fabricante" class="form-control" value="{{ old('fabricante',$peca->fabricante??'') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Unidade</label>
                    <select name="unidade" class="form-select">
                        @foreach(['un','kg','l','m','par','jogo','cx'] as $u)
                        <option value="{{ $u }}" {{ old('unidade',$peca->unidade??'un') == $u ? 'selected':'' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Preço de Custo *</label>
                    <div class="input-group"><span class="input-group-text">R$</span>
                    <input type="number" name="preco_custo" class="form-control font-mono" step="0.01" min="0"
                           value="{{ old('preco_custo',$peca->preco_custo??'') }}" required></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Preço de Venda *</label>
                    <div class="input-group"><span class="input-group-text">R$</span>
                    <input type="number" name="preco_venda" class="form-control font-mono" step="0.01" min="0"
                           value="{{ old('preco_venda',$peca->preco_venda??'') }}" required></div>
                </div>
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <label class="form-label">Estoque atual *</label>
                    <input type="number" name="estoque" class="form-control font-mono" min="0"
                           value="{{ old('estoque',$peca->estoque??0) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estoque mínimo *</label>
                    <input type="number" name="estoque_minimo" class="form-control font-mono" min="0"
                           value="{{ old('estoque_minimo',$peca->estoque_minimo??5) }}" required>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                <a href="{{ route('pecas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
