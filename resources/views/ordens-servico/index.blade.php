@extends('layouts.app')
@section('title','Ordens de Serviço')
@section('breadcrumb','Ordens de Serviço')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-clipboard2-check me-2"></i>Ordens de Serviço</span>
        <a href="{{ route('os.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Abrir OS
        </a>
    </div>

    {{-- Filtros --}}
    <div class="card-body border-bottom">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="busca" class="form-control" placeholder="Número, cliente, placa…" value="{{ request('busca') }}">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Todos os status</option>
                    @foreach(['aberta','em_diagnostico','aguardando_aprovacao','aprovada','em_execucao','aguardando_pecas','finalizada','cancelada'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="mecanico_id" class="form-select">
                    <option value="">Todos mecânicos</option>
                    @foreach($mecanicos as $m)
                    <option value="{{ $m->id }}" {{ request('mecanico_id') == $m->id ? 'selected' : '' }}>{{ $m->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="data_inicio" class="form-control" value="{{ request('data_inicio') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="data_fim" class="form-control" value="{{ request('data_fim') }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                <a href="{{ route('os.index') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Número</th>
                        <th>Cliente</th>
                        <th>Veículo</th>
                        <th>Mecânico</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ordens as $os)
                    <tr>
                        <td><span class="font-mono small fw-500">{{ $os->numero }}</span></td>
                        <td>{{ $os->cliente->nome }}</td>
                        <td>
                            {{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}
                            <br><span class="badge bg-light text-dark font-mono" style="font-size:.7rem">{{ $os->veiculo->placa }}</span>
                        </td>
                        <td>{{ $os->mecanico->nome ?? '—' }}</td>
                        <td><span class="badge badge-{{ $os->status }}">{{ $os->statusLabel() }}</span></td>
                        <td>R$ {{ number_format($os->valor_total,2,',','.') }}</td>
                        <td class="small text-muted">{{ $os->created_at->format('d/m/Y') }}</td>
                        <td class="text-end">
                            <a href="{{ route('os.show',$os->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Nenhuma ordem de serviço encontrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">{{ $ordens->links() }}</div>
</div>
@endsection
