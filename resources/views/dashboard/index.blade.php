@extends('layouts.app')
@section('title', 'Início')
@section('breadcrumb', 'Início')

@section('content')
@php
    $user = auth()->user();
    $primeiroNome = explode(' ', trim($user->name))[0] ?? $user->name;
    $ordensAtivas = $stats['os_abertas'] + $stats['os_em_execucao'] + $stats['os_aguardando'];
    $totalOperacao = max($ordensAtivas + $stats['os_finalizadas_mes'], 1);
    $percentExecucao = min(100, round(($stats['os_em_execucao'] / $totalOperacao) * 100));
    $percentAguardando = min(100, round(($stats['os_aguardando'] / $totalOperacao) * 100));
    $heroTitulo = 'SOMOS APAIXONADOS EM SERVIR, VOCÊ';
    $heroTexto = 'Acompanhe solicitações, aprovações e o andamento dos serviços com uma visão clara, rápida e feita para o ritmo da oficina.';
@endphp

<section class="home-hero">
    <div class="home-hero-copy">
        <span class="home-kicker">OFICINA MECÂNICA • AUTOTECH</span>
        <h1>{{ $heroTitulo }}</h1>
        <p>{{ $heroTexto }}</p>
        <div class="home-hero-actions">
            @if($user->isCliente())
                <a href="{{ route('os.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Solicitar OS
                </a>
                <a href="{{ route('conta.veiculos') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-car-front me-1"></i>Meus veículos
                </a>
            @else
                <a href="{{ route('os.index') }}" class="btn btn-primary">
                    <i class="bi bi-clipboard2-check me-1"></i>Ver ordens
                </a>
                @if($user->isGerente())
                    <a href="{{ route('relatorios.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-bar-chart-line me-1"></i>Relatórios
                    </a>
                @endif
            @endif
        </div>
    </div>

    <div class="home-hero-panel">
        <div class="home-panel-top">
            <span>Operação de hoje</span>
            <i class="bi bi-activity"></i>
        </div>
        <div class="home-main-number">{{ $ordensAtivas }}</div>
        <div class="home-main-label">ordens ativas</div>
        <div class="home-progress-list">
            <div>
                <span>Em diagnóstico/execução</span>
                <strong>{{ $stats['os_em_execucao'] }}</strong>
            </div>
            <div class="home-progress">
                <span style="width: {{ $percentExecucao }}%"></span>
            </div>
            <div>
                <span>Aguardando aprovação</span>
                <strong>{{ $stats['os_aguardando'] }}</strong>
            </div>
            <div class="home-progress home-progress-warning">
                <span style="width: {{ $percentAguardando }}%"></span>
            </div>
        </div>
    </div>
</section>

<section class="home-service-strip">
    <div>
        <i class="bi bi-shield-check"></i>
        <span>Diagnóstico transparente</span>
    </div>
    <div>
        <i class="bi bi-speedometer"></i>
        <span>Controle por etapa</span>
    </div>
    <div>
        <i class="bi bi-wrench-adjustable"></i>
        <span>Execução acompanhada</span>
    </div>
    <div>
        <i class="bi bi-patch-check"></i>
        <span>Histórico e garantia</span>
    </div>
</section>

<section class="home-metrics">
    <div class="stat-card sc-red">
        <i class="bi bi-clipboard-plus sc-bg-icon"></i>
        <div class="sc-top">
            <div class="sc-icon"><i class="bi bi-clipboard-plus"></i></div>
            <span class="sc-pill neu">fila</span>
        </div>
        <div class="stat-value">{{ $stats['os_abertas'] }}</div>
        <div class="stat-label">Solicitações abertas</div>
    </div>

    <div class="stat-card sc-blue">
        <i class="bi bi-tools sc-bg-icon"></i>
        <div class="sc-top">
            <div class="sc-icon"><i class="bi bi-tools"></i></div>
            <span class="sc-pill up">oficina</span>
        </div>
        <div class="stat-value">{{ $stats['os_em_execucao'] }}</div>
        <div class="stat-label">Em diagnóstico/execução</div>
    </div>

    <div class="stat-card sc-amber">
        <i class="bi bi-hourglass-split sc-bg-icon"></i>
        <div class="sc-top">
            <div class="sc-icon"><i class="bi bi-hourglass-split"></i></div>
            <span class="sc-pill neu">cliente</span>
        </div>
        <div class="stat-value">{{ $stats['os_aguardando'] }}</div>
        <div class="stat-label">Aguardando aprovação</div>
    </div>

    <div class="stat-card sc-green">
        <i class="bi bi-cash-stack sc-bg-icon"></i>
        <div class="sc-top">
            <div class="sc-icon"><i class="bi bi-cash-stack"></i></div>
            <span class="sc-pill up">mês</span>
        </div>
        <div class="stat-value stat-money">R$ {{ number_format($stats['faturamento_mes'], 2, ',', '.') }}</div>
        <div class="stat-label">Faturamento finalizado</div>
    </div>
</section>

<section class="home-grid">
    <div class="card home-os-card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <span><i class="bi bi-clipboard2-check me-2 text-warning"></i>Ordens recentes</span>
            <a href="{{ route('os.index') }}" class="btn btn-sm btn-outline-secondary">
                Ver todas
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 home-table">
                    <thead class="table-light">
                        <tr>
                            <th>OS</th>
                            <th>Cliente</th>
                            <th>Veículo</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($os_recentes as $os)
                            <tr>
                                <td>
                                    <span class="os-num">{{ $os->numero }}</span>
                                </td>
                                <td>
                                    <strong>{{ $os->cliente->nome }}</strong>
                                    <br>
                                    <span class="small text-muted">{{ $os->created_at?->format('d/m/Y H:i') }}</span>
                                </td>
                                <td>
                                    {{ $os->veiculo->marca }} {{ $os->veiculo->modelo }}
                                    <br>
                                    <span class="home-plate">{{ $os->veiculo->placa }}</span>
                                </td>
                                <td>
                                    <span class="status-badge badge-{{ $os->status }}">{{ $os->statusLabel() }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('os.show', $os) }}" class="btn btn-sm btn-outline-secondary" title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="bi bi-clipboard2 d-block mb-2" style="font-size:26px;opacity:.45"></i>
                                    Nenhuma OS encontrada.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <aside class="home-side">
        @if(! $user->isCliente())
            <div class="card">
                <div class="card-header">
                    <span><i class="bi bi-exclamation-triangle me-2 text-warning"></i>Estoque crítico</span>
                </div>
                <div class="card-body">
                    @forelse($pecas_criticas as $peca)
                        <div class="home-part-row">
                            <span>{{ $peca->nome }}</span>
                            <strong>{{ $peca->estoque }}</strong>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Nenhuma peça crítica no momento.</p>
                    @endforelse
                </div>
            </div>
        @endif
    </aside>
</section>
@endsection
