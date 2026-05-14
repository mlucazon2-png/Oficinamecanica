@extends('layouts.app')
@section('title', $ordemServico->numero)
@section('breadcrumb', 'OS / ' . $ordemServico->numero)

@section('content')
<div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <h5 class="mb-0 font-mono">{{ $ordemServico->numero }}</h5>
    <span class="badge badge-{{ $ordemServico->status }} fs-6">{{ $ordemServico->statusLabel() }}</span>
    <div class="ms-auto d-flex gap-2 flex-wrap no-print">
        @if($ordemServico->aprovado_cliente)
        <a href="{{ route('os.print', $ordemServico->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-printer me-1"></i>Imprimir
        </a>
        @endif
        @if(!$ordemServico->aprovado_cliente && auth()->user()->isGerente())
        <form method="POST" action="{{ route('os.aprovar', $ordemServico->id) }}">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-success"><i class="bi bi-check2-circle me-1"></i>Aprovar Orçamento</button>
        </form>
        @endif
        @if($ordemServico->status !== 'finalizada' && $ordemServico->status !== 'cancelada')
        <form method="POST" action="{{ route('os.fechar', $ordemServico->id) }}">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-primary" onclick="return confirm('Finalizar esta OS?')">
                <i class="bi bi-flag-fill me-1"></i>Finalizar OS
            </button>
        </form>
        @endif
        @if(!$ordemServico->aprovado_cliente)
        <form method="POST" action="{{ route('os.destroy', $ordemServico->id) }}" style="display:inline;">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta OS? Esta ação não pode ser desfeita.')">
                <i class="bi bi-trash me-1"></i>Excluir
            </button>
        </form>
        @elseif($ordemServico->status === 'cancelada')
        <form method="POST" action="{{ route('os.destroy', $ordemServico->id) }}" style="display:inline;">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta OS? Esta ação não pode ser desfeita.')">
                <i class="bi bi-trash me-1"></i>Excluir
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
                    <dd class="col-7">{{ $ordemServico->veiculo->marca }} {{ $ordemServico->veiculo->modelo }}</dd>

                    <dt class="col-5 text-muted">Placa</dt>
                    <dd class="col-7 font-mono">{{ $ordemServico->veiculo->placa }}</dd>


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

                    @if($ordemServico->mecanico)
                    <dt class="col-5 text-muted">Mecânico</dt>
                    <dd class="col-7">{{ $ordemServico->mecanico->nome }}<br><small class="text-muted">{{ $ordemServico->mecanico->user->email }}</small></dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    {{-- Sintomas / Diagnóstico --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                Sintomas
                {{-- Botão do cabeçalho removido para evitar duplicidade; edição agora é feita via botão inline abaixo --}}
            </div>


            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <div>
                        <p class="small text-muted mb-1">Sintomas:</p>
                        <p class="mb-0" id="sintomas-texto">{{ $ordemServico->sintomas ?: '—' }}</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary no-print" id="btn-editar-sintomas" onclick="editarSintomas()">

                        <i class="bi bi-pencil me-1"></i>Editar
                    </button>
                </div>

                <form method="POST" action="{{ route('os.update', $ordemServico->id) }}" class="no-print" id="sintomas-form" style="display:none;">
                    @csrf
                    @method('PATCH')
                    <textarea name="sintomas" class="form-control" rows="4">{{ $ordemServico->sintomas }}</textarea>
                    <div class="mt-2 d-flex gap-2">
                        <button class="btn btn-sm btn-primary" type="submit">Salvar</button>
                        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="cancelarEdicaoSintomas()">Cancelar</button>
                    </div>
                </form>

            </div>

    </div>


    </div>


    {{-- Fotos/Vídeos (RN004) — apenas visualização --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><i class="bi bi-camera me-2"></i>Mídias (RN004)</div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    @forelse($ordemServico->fotos as $foto)
                        @php
                            $url = $foto->url();
                            $ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                            $isVideo = in_array($ext, ['mp4','webm','ogg','mov','avi']);
                        @endphp
                        <div class="col-4">
                            <div class="position-relative">
                                @if($isVideo)
                                    <video src="{{ $url }}" class="img-fluid rounded" style="height:90px;width:100%;object-fit:cover" controls></video>
                                @else
                                    <img src="{{ $url }}" class="img-fluid rounded" style="height:90px;width:100%;object-fit:cover"
                                         title="{{ $foto->tipo }} / {{ $foto->lado }}"
                                         onerror="this.onerror=null;this.src='{{ asset('images/no-photo.png') }}';">
                                @endif
                                <span class="badge bg-dark position-absolute bottom-0 start-0 m-1" style="font-size:.6rem">{{ $foto->tipo }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-3">Nenhuma mídia enviada.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>





</div>
@endsection

@push('scripts')
<script>
function editarSintomas() {
    const texto = document.getElementById('sintomas-texto');
    const form = document.getElementById('sintomas-form');
    if (!texto || !form) return;

    texto.style.display = 'none';
    form.style.display = 'block';
}

function cancelarEdicaoSintomas() {
    const texto = document.getElementById('sintomas-texto');
    const form = document.getElementById('sintomas-form');
    if (!texto || !form) return;

    form.style.display = 'none';
    texto.style.display = '';
    // opcional: resetar textarea para o valor atual
    const ta = form.querySelector('textarea[name="sintomas"]');
    if (ta) {
        ta.value = @json($ordemServico->sintomas);
    }
}

// Scripts da OS removidos (seções de Serviços e Peças foram removidas para o cliente)
</script>
@endpush

