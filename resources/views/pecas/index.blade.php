{{-- ════ pecas/index.blade.php ════ --}}
@extends('layouts.app')
@section('title','Peças')
@section('breadcrumb','Peças / Estoque')

@section('content')
@if($criticas > 0)
<div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
    <strong>{{ $criticas }} peça(s)</strong> com estoque abaixo do mínimo!
    <a href="{{ route('pecas.index', ['estoque_baixo'=>1]) }}" class="ms-auto btn btn-sm btn-danger">Ver críticas</a>
</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-box-seam me-2"></i>Peças / Estoque</span>
        <a href="{{ route('pecas.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Nova Peça</a>
    </div>
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="busca" class="form-control" placeholder="Nome ou código…" value="{{ request('busca') }}">
            </div>
            <div class="col-auto">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="estoque_baixo" id="cb-baixo" value="1" {{ request('estoque_baixo') ? 'checked' : '' }}>
                    <label class="form-check-label small" for="cb-baixo">Só estoque crítico</label>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                <a href="{{ route('pecas.index') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Código</th><th>Nome</th><th>Fabricante</th><th>Preço venda</th><th>Estoque</th><th>Mínimo</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($pecas as $p)
                <tr>
                    <td class="font-mono small">{{ $p->codigo ?? '—' }}</td>
                    <td>{{ $p->nome }}</td>
                    <td>{{ $p->fabricante ?? '—' }}</td>
                    <td class="font-mono">R$ {{ number_format($p->preco_venda,2,',','.') }}</td>
                    <td class="{{ $p->estoqueAbaixoMinimo() ? 'estoque-critico' : '' }} font-mono">
                        {{ $p->estoque }} {{ $p->unidade }}
                        @if($p->estoqueAbaixoMinimo())<i class="bi bi-exclamation-triangle-fill ms-1"></i>@endif
                    </td>
                    <td class="font-mono text-muted">{{ $p->estoque_minimo }}</td>
                    <td class="text-end">
                        <a href="{{ route('pecas.edit',$p) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('pecas.destroy',$p) }}" class="d-inline" onsubmit="return confirm('Excluir peça?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Nenhuma peça encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $pecas->links() }}</div>
</div>
@endsection
