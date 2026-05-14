@extends('layouts.app')
@section('title', 'Editar Veículo')
@section('breadcrumb', 'Editar Veículo')
@section('content')
<div class="row justify-content-center"><div class="col-lg-7">
<div class="card">
    <div class="card-header"><i class="bi bi-car-front me-2"></i>Editar Veículo</div>
    <div class="card-body">
        <form method="POST" action="{{ route('veiculos.update', $veiculo->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Proprietário</label>
                    <div class="form-control-plaintext fw-500">{{ $veiculo->cliente->nome }}</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Placa *</label>
                    <input type="text" name="placa" class="form-control font-mono text-uppercase @error('placa') is-invalid @enderror"
                           value="{{ old('placa', $veiculo->placa) }}" placeholder="ABC1D23" required>
                    @error('placa')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ano *</label>
                    <input type="number" name="ano" class="form-control font-mono @error('ano') is-invalid @enderror"
                           value="{{ old('ano', $veiculo->ano) }}" min="1900" max="{{ date('Y')+1 }}" required>
                    @error('ano')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Marca/Modelo dependentes --}}
                <div class="col-md-6">
                    <label class="form-label">Marca *</label>
                    <select name="marca" id="sel-marca" class="form-select @error('marca') is-invalid @enderror" required>
                        <option value="" @selected(old('marca', $veiculo->marca) === null || old('marca', $veiculo->marca) === '')>Selecione...</option>
                        @foreach($marcas as $m)
                            <option value="{{ $m->nome }}" data-marca-id="{{ $m->id }}" @selected(old('marca', $veiculo->marca) === $m->nome)>
                                {{ $m->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('marca')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Modelo *</label>
                    <select name="modelo" id="sel-modelo" class="form-select @error('modelo') is-invalid @enderror" required>
                        <option value="" @selected(old('modelo', $veiculo->modelo) === null || old('modelo', $veiculo->modelo) === '')>Selecione...</option>
                        @foreach($modelos as $md)
                            <option value="{{ $md->nome }}" @selected(old('modelo', $veiculo->modelo) === $md->nome)>
                                {{ $md->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('modelo')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

            @push('scripts')
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const selMarca = document.getElementById('sel-marca');
                const selModelo = document.getElementById('sel-modelo');
                if (!selMarca || !selModelo) return;

                async function carregarModelos(marcaId, selecionarModelo = null) {
                    if (!marcaId) {
                        if (!selModelo.value) {
                            selModelo.innerHTML = '<option value="">Selecione...</option>';
                        }
                        return;
                    }

                    const url = '{{ url('/modelos-por-marca') }}/' + marcaId;
                    try {
                        const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
                        if (!resp.ok) throw new Error('HTTP ' + resp.status);
                        const modelos = await resp.json();

                        const atualSelecionado = selecionarModelo || selModelo.value;

                        selModelo.innerHTML = '<option value="">Selecione...</option>';
                        for (const m of modelos) {
                            const opt = document.createElement('option');
                            opt.value = m.nome;
                            opt.textContent = m.nome;
                            if (atualSelecionado && atualSelecionado === m.nome) {
                                opt.selected = true;
                            }
                            selModelo.appendChild(opt);
                        }
                    } catch (err) {
                        console.warn('Falha ao carregar modelos por marca:', err);
                    }
                }

                const oldModelo = @json(old('modelo', $veiculo->modelo));

                function marcaAtualId() {
                    const opt = selMarca.options[selMarca.selectedIndex];
                    return opt ? opt.getAttribute('data-marca-id') : null;
                }

                selMarca.addEventListener('change', function () {
                    carregarModelos(marcaAtualId());
                });

                // Ao abrir a página, se a marca estiver selecionada, garante que os modelos condizem.
                carregarModelos(marcaAtualId(), oldModelo);
            });
            </script>
            @endpush

                <div class="col-md-4">

                    <label class="form-label">Cor</label>
                    <select name="cor" class="form-select" required>
                        <option value="" @selected(old('cor', $veiculo->cor) === null || old('cor', $veiculo->cor) === '')>Selecione a cor...</option>
                        @foreach(['Branco','Preto','Prata','Cinza','Vermelho','Azul','Verde','Amarelo','Marrom','Bege','Dourado','Roxo'] as $cor)
                            <option value="{{ $cor }}" @selected(old('cor', $veiculo->cor) === $cor)>{{ $cor }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Km atual</label>
                    <input type="number" name="km_atual" class="form-control font-mono" min="0"
                           value="{{ old('km_atual', $veiculo->km_atual) }}">
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                <a href="{{ route('veiculos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
