<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>{{ $ordemServico->numero }} — AutoTech Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Roboto+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family:'Roboto',sans-serif; font-size:13px; }
        .font-mono { font-family:'Roboto Mono',monospace; }
        .header-strip { background:#FF6B35; color:#fff; padding:12px 20px; border-radius:6px; margin-bottom:1.2rem; }
        @media print { .no-print { display:none } }
    </style>
</head>
<body class="p-4">

<div class="no-print mb-3">
    <button onclick="window.print()" class="btn btn-sm btn-primary">🖨️ Imprimir</button>
    <button onclick="window.close()" class="btn btn-sm btn-outline-secondary ms-2">Fechar</button>
</div>

<div class="header-strip d-flex justify-content-between align-items-center">
    <div>
        <h5 class="mb-0 fw-700">AutoTech Pro</h5>
        <small>Ordem de Serviço</small>
    </div>
    <div class="text-end">
        <div class="font-mono fw-700 fs-5">{{ $ordemServico->numero }}</div>
        <small>{{ $ordemServico->created_at->format('d/m/Y H:i') }}</small>
    </div>
</div>

<div class="row mb-3">
    <div class="col-6">
        <strong>Cliente:</strong> {{ $ordemServico->cliente->nome }}<br>
        <strong>CPF:</strong> <span class="font-mono">{{ $ordemServico->cliente->cpf }}</span><br>
        <strong>Telefone:</strong> {{ $ordemServico->cliente->telefone }}
    </div>
    <div class="col-6">
        <strong>Veículo:</strong> {{ $ordemServico->veiculo->marca }} {{ $ordemServico->veiculo->modelo }} {{ $ordemServico->veiculo->ano }}<br>
        <strong>Placa:</strong> <span class="font-mono">{{ $ordemServico->veiculo->placa }}</span><br>
        <strong>Cor:</strong> {{ $ordemServico->veiculo->cor ?? '—' }}<br>
        <strong>Km entrada:</strong> <span class="font-mono">{{ $ordemServico->km_entrada ? number_format($ordemServico->km_entrada,0,',','.') : '—' }}</span>
    </div>
</div>

<div class="row mb-3">
    <div class="col-6">
        <strong>Mecânico:</strong> {{ $ordemServico->mecanico->nome ?? '—' }}<br>
        <strong>Status:</strong> {{ $ordemServico->statusLabel() }}<br>
        @if($ordemServico->data_previsao)
        <strong>Previsão:</strong> {{ $ordemServico->data_previsao->format('d/m/Y') }}
        @endif
    </div>
    <div class="col-6">
        <strong>Sintomas:</strong> {{ $ordemServico->sintomas }}<br>
        @if($ordemServico->diagnostico)
        <strong>Diagnóstico:</strong> {{ $ordemServico->diagnostico }}
        @endif
    </div>
</div>

<table class="table table-bordered table-sm mb-3">
    <thead class="table-dark">
        <tr><th>Tipo</th><th>Descrição</th><th>Qtd</th><th>Unit.</th><th>Total</th></tr>
    </thead>
    <tbody>
        @foreach($ordemServico->itens as $item)
        <tr>
            <td>{{ ucfirst($item->tipo) }}</td>
            <td>{{ $item->descricao }}</td>
            <td class="font-mono text-end">{{ $item->quantidade }}</td>
            <td class="font-mono text-end">R$ {{ number_format($item->valor_unitario,2,',','.') }}</td>
            <td class="font-mono text-end">R$ {{ number_format($item->valor_total,2,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr><td colspan="4" class="text-end fw-600">Serviços</td><td class="font-mono text-end">R$ {{ number_format($ordemServico->valor_servicos,2,',','.') }}</td></tr>
        <tr><td colspan="4" class="text-end fw-600">Peças</td><td class="font-mono text-end">R$ {{ number_format($ordemServico->valor_pecas,2,',','.') }}</td></tr>
        @if($ordemServico->valor_desconto > 0)
        <tr><td colspan="4" class="text-end fw-600">Desconto</td><td class="font-mono text-end text-danger">- R$ {{ number_format($ordemServico->valor_desconto,2,',','.') }}</td></tr>
        @endif
        <tr class="table-warning">
            <td colspan="4" class="text-end fw-700 fs-6">TOTAL</td>
            <td class="font-mono text-end fw-700 fs-6">R$ {{ number_format($ordemServico->valor_total,2,',','.') }}</td>
        </tr>
    </tfoot>
</table>



<div class="row mt-5">
    <div class="col-6 text-center">
        <div style="border-top:1px solid #333;padding-top:6px">Assinatura do Cliente</div>
    </div>
    <div class="col-6 text-center">
        <div style="border-top:1px solid #333;padding-top:6px">Assinatura do Responsável</div>
    </div>
</div>

<p class="text-muted text-center mt-4" style="font-size:.7rem">
    AutoTech Pro v2.0 — Documento gerado em {{ now()->format('d/m/Y H:i') }}
</p>
</body>
</html>
