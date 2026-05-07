@extends('layouts.app')
@section('title','Relatórios')
@section('breadcrumb','Relatórios')

@section('content')
<div class="row g-3">
    @php
    $rels = [
        ['route'=>'relatorios.os-status',  'icon'=>'bi-clipboard2-data',   'titulo'=>'OS por Status',              'desc'=>'Ordens abertas, em execução, finalizadas.'],
        ['route'=>'relatorios.faturamento','icon'=>'bi-cash-stack',         'titulo'=>'Faturamento Mensal',          'desc'=>'Receita por mês e tipo de serviço.'],
        ['route'=>'relatorios.produtividade','icon'=>'bi-person-gear',      'titulo'=>'Produtividade Mecânicos',     'desc'=>'OS concluídas e tempo médio por mecânico.'],
        ['route'=>'relatorios.pecas',      'icon'=>'bi-box-seam',           'titulo'=>'Peças Mais Usadas',           'desc'=>'Estoque, giro, sugestão de compra.'],
        ['route'=>'relatorios.garantias',  'icon'=>'bi-shield-check',       'titulo'=>'Garantias Acionadas',         'desc'=>'Quantidade, custo e tipo de defeito.'],
        ['route'=>'relatorios.tempo-reparo','icon'=>'bi-stopwatch',         'titulo'=>'Tempo Médio de Reparo',       'desc'=>'Por tipo de serviço, mecânico, complexidade.'],
        ['route'=>'relatorios.clientes',   'icon'=>'bi-people',             'titulo'=>'Clientes Recorrentes vs Novos','desc'=>'Fidelização, campanhas de retorno.'],
        ['route'=>'relatorios.orcamentos', 'icon'=>'bi-bar-chart-line',     'titulo'=>'Orçamentos Aprovados vs Rejeitados','desc'=>'Taxa de conversão e motivos.'],
    ];
    @endphp

    @foreach($rels as $r)
    <div class="col-md-6 col-lg-3">
        <a href="{{ route($r['route']) }}" class="text-decoration-none">
            <div class="card h-100 hover-shadow" style="transition:box-shadow .2s">
                <div class="card-body text-center py-4">
                    <i class="bi {{ $r['icon'] }} fs-2 text-warning mb-2 d-block"></i>
                    <h6 class="fw-600 mb-1">{{ $r['titulo'] }}</h6>
                    <p class="text-muted small mb-0">{{ $r['desc'] }}</p>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
@endsection
