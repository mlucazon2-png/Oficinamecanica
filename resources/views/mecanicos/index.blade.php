@extends('layouts.app')
@section('title','Mecânicos')
@section('breadcrumb','Mecânicos')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <span><i class="bi bi-person-gear me-2"></i>Mecânicos</span>
        <a href="{{ route('mecanicos.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Novo Mecânico</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Nome</th><th>Email</th><th>CPF</th><th>Especialidade</th><th>Telefone</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($mecanicos as $m)
                <tr>
                    <td>{{ $m->nome }}</td>
                    <td>{{ $m->user->email ?? '—' }}</td>
                    <td class="font-mono small">{{ $m->cpf ?? '—' }}</td>
                    <td>{{ $m->especialidade ?? '—' }}</td>
                    <td>{{ $m->telefone ?? '—' }}</td>
                    <td><span class="badge {{ $m->ativo ? 'bg-success' : 'bg-secondary' }}">{{ $m->ativo ? 'Ativo' : 'Inativo' }}</span></td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('mecanicos.toggle',$m) }}" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-secondary">{{ $m->ativo ? 'Desativar' : 'Ativar' }}</button>
                        </form>
                        <a href="{{ route('mecanicos.edit',$m) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                        <form method="POST" action="{{ route('mecanicos.destroy',$m) }}" class="d-inline" onsubmit="return confirm('Excluir?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Nenhum mecânico cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $mecanicos->links() }}</div>
</div>
@endsection
