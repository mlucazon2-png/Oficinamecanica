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
            <div class="col-md-6">
                <div class="d-flex align-items-start gap-3">
                    <div style="flex:0 0 220px; max-width:220px;">
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

                        @if(!empty($fotoUrl))
                            <div style="width:100%;height:220px;overflow:hidden;border-radius:8px;">
                                <img src="{{ $fotoUrl }}" alt="Foto do veículo" style="width:100%;height:100%;object-fit:cover;display:block;" />
                            </div>

                        @else
                            <div class="text-muted small" style="height:220px;display:flex;align-items:center;justify-content:center;border:1px dashed var(--border2);border-radius:8px;">Sem foto</div>
                        @endif
                    </div>


                    <dl class="row mb-0" style="flex:1;">

                    <dt class="col-5 text-muted">Placa</dt>
                    <dd class="col-7 font-mono fw-500">{{ $veiculo->placa }}</dd>

                    <dt class="col-5 text-muted">Marca</dt>
                    <dd class="col-7">{{ $veiculo->marca }}</dd>

                    <dt class="col-5 text-muted">Modelo</dt>
                    <dd class="col-7">{{ $veiculo->modelo }}</dd>

                    <dt class="col-5 text-muted">Ano</dt>
                    <dd class="col-7">{{ $veiculo->ano }}</dd>
                </dl>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    @php
                        $fotoUrl = null;
                        if ($veiculo->cliente) {
                            $clienteId = $veiculo->cliente->id;
                            $dir = "veiculos/{$clienteId}";

                            // pega o primeiro arquivo de imagem da pasta do cliente
                            $files = Storage::disk('public')->files($dir);
                            $primeiro = collect($files)
                                ->first(fn($f) => preg_match('/\.(jpe?g|png|webp)$/i', $f));

                            $fotoUrl = $primeiro ? Storage::disk('public')->url($primeiro) : null;
                        }
                    @endphp

                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="Foto do veículo" class="img-fluid rounded" style="max-height:220px;object-fit:cover;" />
                    @else
                        <div class="text-muted small">Sem foto do veículo cadastrada.</div>
                    @endif
                </div>
            </div>

            <div class="col-md-8">
                <dl class="row">
                    <dt class="col-5 text-muted">Cor</dt>
                    <dd class="col-7">{{ $veiculo->cor ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Km atual</dt>
                    <dd class="col-7 font-mono">{{ $veiculo->km_atual ? number_format($veiculo->km_atual,0,',','.') : '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</div>

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
