@extends('layouts.app')
@section('title', $ordemServico->numero)
@section('breadcrumb', 'OS / ' . $ordemServico->numero)

@section('content')
<div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <h5 class="mb-0 font-mono">{{ $ordemServico->numero }}</h5>
    <span class="badge badge-{{ $ordemServico->status }} fs-6">{{ $ordemServico->statusLabel() }}</span>
    <div class="ms-auto d-flex gap-2 flex-wrap no-print">
        <a href="{{ route('os.print', $ordemServico) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-printer me-1"></i>Imprimir
        </a>
        @if(!$ordemServico->aprovado_cliente && auth()->user()->isGerente())
        <form method="POST" action="{{ route('os.aprovar', $ordemServico) }}">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-success"><i class="bi bi-check2-circle me-1"></i>Aprovar Orçamento</button>
        </form>
        @endif
        @if($ordemServico->status !== 'finalizada' && $ordemServico->status !== 'cancelada')
        <form method="POST" action="{{ route('os.fechar', $ordemServico) }}">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-primary" onclick="return confirm('Finalizar esta OS?')">
                <i class="bi bi-flag-fill me-1"></i>Finalizar OS
            </button>
        </form>
        @endif
    </div>
</div>

<div class="row g-3">

    {{-- Info geral --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">Informações</div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">Cliente</dt>
                    <dd class="col-7 fw-500">{{ $ordemServico->cliente->nome }}</dd>

                    <dt class="col-5 text-muted">Veículo</dt>
                    <dd class="col-7">{{ $ordemServico->veiculo->marca }} {{ $ordemServico->veiculo->modelo }}
                        <br><span class="font-mono">{{ $ordemServico->veiculo->placa }}</span></dd>

                    <dt class="col-5 text-muted">Mecânico</dt>
                    <dd class="col-7">{{ $ordemServico->mecanico->nome ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Km entrada</dt>
                    <dd class="col-7 font-mono">{{ $ordemServico->km_entrada ? number_format($ordemServico->km_entrada,0,',','.') : '—' }}</dd>

                    <dt class="col-5 text-muted">Abertura</dt>
                    <dd class="col-7">{{ $ordemServico->created_at->format('d/m/Y H:i') }}</dd>

                    @if($ordemServico->data_previsao)
                    <dt class="col-5 text-muted">Previsão</dt>
                    <dd class="col-7">{{ $ordemServico->data_previsao->format('d/m/Y') }}</dd>
                    @endif

                    @if($ordemServico->data_conclusao)
                    <dt class="col-5 text-muted">Concluída</dt>
                    <dd class="col-7">{{ $ordemServico->data_conclusao->format('d/m/Y H:i') }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    {{-- Sintomas / Diagnóstico --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                Sintomas & Diagnóstico
                <a href="{{ route('os.edit', $ordemServico) }}" class="btn btn-sm btn-outline-primary no-print">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
            </div>
            <div class="card-body">
                <p class="small text-muted mb-1">Sintomas:</p>
                <p class="mb-3">{{ $ordemServico->sintomas ?: '—' }}</p>
                <p class="small text-muted mb-1">Diagnóstico:</p>
                <p class="mb-3">{{ $ordemServico->diagnostico ?: '—' }}</p>
                @if($ordemServico->observacoes)
                <p class="small text-muted mb-1">Observações:</p>
                <p class="mb-0">{{ $ordemServico->observacoes }}</p>
                @endif
            </div>
        </div>

        {{-- Atualizar status --}}
        @if(!in_array($ordemServico->status, ['finalizada','cancelada']))
        <div class="card mt-3 no-print">
            <div class="card-body py-2">
                <form method="POST" action="{{ route('os.status', $ordemServico) }}" class="d-flex gap-2 align-items-center">
                    @csrf @method('PATCH')
                    <select name="status" class="form-select form-select-sm" style="max-width:220px">
                        @foreach(['aberta','em_diagnostico','aguardando_aprovacao','aprovada','em_execucao','aguardando_pecas','finalizada','cancelada'] as $s)
                        <option value="{{ $s }}" {{ $ordemServico->status == $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-outline-primary">Atualizar Status</button>
                </form>
            </div>
        </div>
        @endif
    </div>

    {{-- Itens (serviços e peças) --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-check me-2"></i>Serviços e Peças
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tipo</th><th>Descrição</th><th>Qtd</th><th>Unit.</th><th>Total</th><th class="no-print"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ordemServico->itens as $item)
                        <tr>
                            <td><span class="badge {{ $item->tipo == 'servico' ? 'bg-info text-dark' : 'bg-warning text-dark' }}">{{ ucfirst($item->tipo) }}</span></td>
                            <td>{{ $item->descricao }}</td>
                            <td class="font-mono">{{ $item->quantidade }}</td>
                            <td class="font-mono">R$ {{ number_format($item->valor_unitario,2,',','.') }}</td>
                            <td class="font-mono fw-500">R$ {{ number_format($item->valor_total,2,',','.') }}</td>
                            <td class="no-print">
                                <form method="POST" action="{{ route('os.itens.destroy',[$ordemServico,$item]) }}" onsubmit="return confirm('Remover item?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">Nenhum item adicionado.</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light fw-600">
                        <tr>
                            <td colspan="4" class="text-end">Serviços:</td>
                            <td class="font-mono">R$ {{ number_format($ordemServico->valor_servicos,2,',','.') }}</td>
                            <td class="no-print"></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Peças:</td>
                            <td class="font-mono">R$ {{ number_format($ordemServico->valor_pecas,2,',','.') }}</td>
                            <td class="no-print"></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end fs-6">TOTAL:</td>
                            <td class="font-mono fs-6 text-primary">R$ {{ number_format($ordemServico->valor_total,2,',','.') }}</td>
                            <td class="no-print"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Adicionar item --}}
            @if(!in_array($ordemServico->status,['finalizada','cancelada']))
            <div class="card-footer no-print">
                <form method="POST" action="{{ route('os.itens.store', $ordemServico) }}" class="row g-2 align-items-end">
                    @csrf
                    <div class="col-md-2">
                        <select name="tipo" id="tipo-item" class="form-select form-select-sm">
                            <option value="servico">Serviço</option>
                            <option value="peca">Peça</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="wrap-servico">
                        <select name="servico_id" class="form-select form-select-sm" id="sel-servico">
                            <option value="">Selecionar serviço…</option>
                            @foreach($servicos as $s)
                            <option value="{{ $s->id }}" data-valor="{{ $s->valor_mao_obra }}" data-desc="{{ $s->nome }}">{{ $s->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-none" id="wrap-peca">
                        <select name="peca_id" class="form-select form-select-sm" id="sel-peca">
                            <option value="">Selecionar peça…</option>
                            @foreach($pecas as $p)
                            <option value="{{ $p->id }}" data-valor="{{ $p->preco_venda }}" data-desc="{{ $p->nome }}">{{ $p->nome }} (est: {{ $p->estoque }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="descricao" id="inp-desc" class="form-control form-control-sm" placeholder="Descrição" required>
                    </div>
                    <div class="col-md-1">
                        <input type="number" name="quantidade" class="form-control form-control-sm font-mono" value="1" min="0.001" step="0.001" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="valor_unitario" id="inp-valor" class="form-control form-control-sm font-mono" placeholder="R$ 0,00" min="0" step="0.01" required>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-primary"><i class="bi bi-plus-lg"></i> Adicionar</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    {{-- Fotos --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-camera me-2"></i>Fotos (RN004)</div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    @foreach($ordemServico->fotos as $foto)
                    <div class="col-4">
                        <div class="position-relative">
                            <img src="{{ $foto->url() }}" class="img-fluid rounded" style="height:90px;width:100%;object-fit:cover"
                                 title="{{ $foto->tipo }} / {{ $foto->lado }}">
                            <span class="badge bg-dark position-absolute bottom-0 start-0 m-1" style="font-size:.6rem">{{ $foto->tipo }}</span>
                            <form method="POST" action="{{ route('os.fotos.destroy',[$ordemServico,$foto->id]) }}" class="position-absolute top-0 end-0 m-1 no-print">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm py-0 px-1"><i class="bi bi-x"></i></button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                <form method="POST" action="{{ route('os.fotos.store', $ordemServico) }}" enctype="multipart/form-data" class="no-print">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-5">
                            <select name="tipo" class="form-select form-select-sm">
                                <option value="entrada">Entrada</option>
                                <option value="saida">Saída</option>
                                <option value="processo">Processo</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <select name="lado" class="form-select form-select-sm">
                                <option value="">Lado (opc.)</option>
                                <option value="frontal">Frontal</option>
                                <option value="traseira">Traseira</option>
                                <option value="lateral_dir">Lateral Direita</option>
                                <option value="lateral_esq">Lateral Esquerda</option>
                                <option value="interior">Interior</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <input type="file" name="fotos[]" class="form-control form-control-sm" multiple accept="image/*" required>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-upload me-1"></i>Enviar Fotos</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Garantias --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-shield-check me-2"></i>Garantias</div>
            <div class="card-body p-0">
                @forelse($ordemServico->garantias as $g)
                <div class="px-3 py-2 border-bottom d-flex align-items-start justify-content-between">
                    <div>
                        <div class="small fw-500">{{ $g->descricao }}</div>
                        <div class="small text-muted">{{ $g->data_inicio->format('d/m/Y') }} – {{ $g->data_fim->format('d/m/Y') }}</div>
                    </div>
                    <span class="badge {{ $g->ativa() ? 'bg-success' : ($g->expirada() ? 'bg-secondary' : 'bg-warning text-dark') }}">
                        {{ $g->acionada ? 'Acionada' : ($g->expirada() ? 'Expirada' : 'Ativa') }}
                    </span>
                </div>
                @empty
                <div class="text-center text-muted py-3 small">Nenhuma garantia gerada ainda.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const tipoSel = document.getElementById('tipo-item');
const wrapSvc = document.getElementById('wrap-servico');
const wrapPca = document.getElementById('wrap-peca');
const selSvc  = document.getElementById('sel-servico');
const selPca  = document.getElementById('sel-peca');
const inpDesc = document.getElementById('inp-desc');
const inpVal  = document.getElementById('inp-valor');

tipoSel?.addEventListener('change', function () {
    if (this.value === 'servico') {
        wrapSvc.classList.remove('d-none');
        wrapPca.classList.add('d-none');
    } else {
        wrapSvc.classList.add('d-none');
        wrapPca.classList.remove('d-none');
    }
});

selSvc?.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    if (opt.value) { inpDesc.value = opt.dataset.desc; inpVal.value = opt.dataset.valor; }
});

selPca?.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    if (opt.value) { inpDesc.value = opt.dataset.desc; inpVal.value = opt.dataset.valor; }
});
</script>
@endpush
