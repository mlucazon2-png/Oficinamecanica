@extends('layouts.app')
@section('title', "$veiculo->marca $veiculo->modelo")
@section('breadcrumb', "Veículos / $veiculo->marca $veiculo->modelo")

@section('content')
<div class="row justify-content-center"><div class="col-lg-8">
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-car-front me-2"></i>{{ $veiculo->marca }} {{ $veiculo->modelo }}
            <span class="font-mono text-muted ms-2">{{ $veiculo->placa }}</span>
        </div>
        <div class="gap-2 d-flex">
            <a href="{{ route('veiculos.edit', $veiculo->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil me-1"></i>Editar
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-12">
                <div class="d-flex align-items-start gap-3" style="flex-wrap:nowrap;">
                    {{-- foto (não depende de banco, vem do storage) --}}
                    @php
                        $fotoUrl = null;
                        if ($veiculo?->cliente) {
                            $clienteId = $veiculo->cliente->id;
                            $dir = "veiculos/{$clienteId}";

                            $files = Storage::disk('public')->files($dir);
                            $primeiro = collect($files)
                                ->first(fn($f) => preg_match('/\.(jpe?g|png|webp)$/i', $f));

                            $fotoUrl = $primeiro ? Storage::disk('public')->url($primeiro) : null;
                        }
                    @endphp

                    <div style="flex:0 0 220px; max-width:220px; position:relative;">
                        @if(!empty($fotoUrl))
                            <div style="width:100%;height:220px;overflow:hidden;border-radius:8px; position:relative;">
                                <img id="veiculo-foto" src="{{ $fotoUrl }}" alt="Foto do veículo" style="width:100%;height:100%;object-fit:cover;display:block;" />
                                <button id="zoom-foto-btn" type="button" class="btn btn-sm btn-light" style="position:absolute; bottom:10px; right:10px; background:rgba(255,255,255,0.85); border:1px solid rgba(0,0,0,0.1);">
                                    <i class="bi bi-zoom-in"></i> Zoom
                                </button>
                            </div>
                        @else
                            <div class="text-muted small" style="height:220px;display:flex;align-items:center;justify-content:center;border:1px dashed var(--border2);border-radius:8px;">Sem foto</div>
                        @endif
                    </div>

                    {{-- bloco à direita (placa/marca/modelo/ano) --}}
                    <div style="flex:1; min-width: 250px;">
                        <dl class="row mb-0">
                            <dt class="col-5">Placa</dt>
                            <dd class="col-7 font-mono fw-800" style="color:#b00020;font-weight:900;">{{ $veiculo->placa }}</dd>

                            <dt class="col-5">Marca</dt>
                            <dd class="col-7 fw-800" style="color:#b00020;font-weight:900;">{{ $veiculo->marca }}</dd>

                            <dt class="col-5">Modelo</dt>
                            <dd class="col-7 fw-800" style="color:#b00020;font-weight:900;">{{ $veiculo->modelo }}</dd>

                            <dt class="col-5">Ano</dt>
                            <dd class="col-7 fw-800" style="color:#b00020;font-weight:900;">{{ $veiculo->ano }}</dd>
                        </dl>
                    </div>

                    {{-- bloco à direita (cor/km) --}}
                    <div style="flex:1; min-width: 200px;">
                        <dl class="row mb-0">
                            <dt class="col-5">Cor</dt>
                            <dd class="col-7 fw-900" style="color:#b00020;font-weight:900;">{{ $veiculo->cor ?? '—' }}</dd>

                            <dt class="col-5">Km atual</dt>
                            <dd class="col-7 font-mono fw-900" style="color:#b00020;font-weight:900;">{{ $veiculo->km_atual ? number_format($veiculo->km_atual,0,',','.') : '—' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($fotoUrl))
<div id="zoom-modal" style="display:none;position:fixed;inset:0;z-index:1050;background:rgba(0,0,0,.75);align-items:center;justify-content:center;padding:20px;">
    <div style="position:relative;max-width:90%;max-height:90%;width:auto;">
        <button id="zoom-modal-close" type="button" style="position:absolute;top:-12px;right:-12px;width:36px;height:36px;border-radius:50%;border:none;background:#fff;color:#000;cursor:pointer;font-size:18px;box-shadow:0 4px 14px rgba(0,0,0,.25);">×</button>
        <img src="{{ $fotoUrl }}" alt="Foto do veículo ampliada" style="width:100%;height:auto;max-height:90vh;display:block;border-radius:12px;" />
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const zoomBtn = document.getElementById('zoom-foto-btn');
        const modal = document.getElementById('zoom-modal');
        const closeBtn = document.getElementById('zoom-modal-close');

        if (zoomBtn && modal && closeBtn) {
            zoomBtn.addEventListener('click', function () {
                modal.style.display = 'flex';
            });
            closeBtn.addEventListener('click', function () {
                modal.style.display = 'none';
            });
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }
    });
</script>
@endif

@if($ordens->count() > 0)
<div class="card mt-3">
    <div class="card-header">
        <i class="bi bi-list-check me-2"></i>Ordens de Serviço Recentes
    </div>
    <div class="card-body p-0">
        <table class="table mb-0 table-sm">
            <thead class="table-light">
                <tr>
                    <th>OS</th>
                    <th>Mecânico</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($ordens as $os)
                <tr>
                    <td class="font-mono fw-500">{{ $os->numero }}</td>
                    <td>{{ $os->mecanico->nome ?? '—' }}</td>
                    <td>
                        <span class="badge bg-{{ $os->statusCor() }}">{{ $os->statusLabel() }}</span>
                    </td>
                    <td class="text-muted small">{{ $os->created_at->format('d/m/Y') }}</td>
                    <td class="text-end">
                        <a href="{{ route('os.show', $os->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="mt-4 d-flex gap-2">
    <a href="{{ route('veiculos.index') }}" class="btn btn-outline-secondary">Voltar</a>
</div>
</div></div>
@endsection
