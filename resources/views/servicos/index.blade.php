{{-- ════ servicos/index.blade.php ════ --}}
@extends('layouts.app')
@section('title','Serviços')
@section('breadcrumb','Serviços')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-tools me-2"></i>Catálogo de Serviços</span>
        <a href="{{ route('servicos.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Novo Serviço</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Nome</th><th>Categoria</th><th>Mão de Obra</th><th>Tempo (min)</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($servicos as $s)
                <tr>
                    <td>{{ $s->nome }}</td>
                    <td><span class="badge bg-secondary">{{ $s->categoria ?? '—' }}</span></td>
                    <td class="font-mono">R$ {{ number_format($s->valor_mao_obra,2,',','.') }}</td>
                    <td class="font-mono">{{ $s->tempo_estimado ?? '—' }}</td>
                    <td><span class="badge {{ $s->ativo ? 'bg-success' : 'bg-secondary' }}">{{ $s->ativo ? 'Ativo' : 'Inativo' }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('servicos.edit',$s) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('servicos.destroy',$s) }}" class="d-inline" onsubmit="return confirm('Excluir?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhum serviço cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $servicos->links() }}</div>
</div>
@endsection
