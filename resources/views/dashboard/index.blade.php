@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-value">{{ $stats['os_abertas'] }}</div>
            <div class="stat-label">OS Abertas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-color:#0d6efd">
            <div class="stat-value">{{ $stats['os_em_execucao'] }}</div>
            <div class="stat-label">Em Execução</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-color:#FFD700">
            <div class="stat-value">{{ $stats['os_aguardando'] }}</div>
            <div class="stat-label">Aguard. Aprovação</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-color:#228B22">
            <div class="stat-value">R$ {{ number_format($stats['faturamento_mes'],2,',','.') }}</div>
            <div class="stat-label">Faturamento do Mês</div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-color:#708090">
            <div class="stat-value">{{ $stats['total_clientes'] }}</div>
            <div class="stat-label">Clientes</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-color:#708090">
            <div class="stat-value">{{ $stats['mecanicos_ativos'] }}</div>
            <div class="stat-label">Mecânicos Ativos</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-color:{{ $stats['pecas_criticas'] > 0 ? '#dc3545' : '#228B22' }}">
            <div class="stat-value {{ $stats['pecas_criticas'] > 0 ? 'text-danger' : '' }}">{{ $stats['pecas_criticas'] }}</div>
            <div class="stat-label">Peças Críticas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="border-color:#228B22">
            <div class="stat-value">{{ $stats['os_finalizadas_mes'] }}</div>
            <div class="stat-label">OS Finalizadas/Mês</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- OS Recentes --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clipboard2-check me-2 text-warning"></i>Ordens de Serviço Recentes</span>
                <a href="{{ route('os.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Nova OS
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Número</th>
                                <th>Cliente</th>
                                <th>Veículo</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($os_recentes as $os)
                            <tr>
                                <td><span class="font-mono small">{{ $os->numero }}</span></td>
                                <td>{{ $os->cliente->nome }}</td>
                                <td>{{ $os->veiculo->modelo }} <span class="badge bg-light text-dark font-mono">{{ $os->veiculo->placa }}</span></td>
                                <td>
                                    <span class="badge badge-{{ $os->status }}">{{ $os->statusLabel() }}</span>
                                </td>
                                <td>R$ {{ number_format($os->valor_total,2,',','.') }}</td>
                                <td>
                                    <a href="{{ route('os.show', $os) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Nenhuma OS encontrada.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Peças críticas --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-exclamation-triangle me-2 text-danger"></i>Estoque Crítico
            </div>
            <div class="card-body p-0">
                @forelse($pecas_criticas as $peca)
                <div class="d-flex align-items-center px-3 py-2 border-bottom">
                    <div class="flex-grow-1">
                        <div class="small fw-500">{{ $peca->nome }}</div>
                        <div class="font-mono" style="font-size:.72rem;color:#708090">{{ $peca->codigo }}</div>
                    </div>
                    <span class="badge bg-danger ms-2">{{ $peca->estoque }} {{ $peca->unidade }}</span>
                </div>
                @empty
                <div class="text-center text-muted py-4 small">
                    <i class="bi bi-check-circle text-success fs-4 d-block mb-1"></i>
                    Estoque OK
                </div>
                @endforelse
                @if($pecas_criticas->count())
                <div class="p-2 text-center">
                    <a href="{{ route('pecas.index', ['estoque_baixo'=>1]) }}" class="btn btn-sm btn-outline-danger">
                        Ver todos
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
