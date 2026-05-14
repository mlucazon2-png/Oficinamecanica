@php
    // Partial para Marca/Modelo dependentes
    /** @var \Illuminate\Support\Collection $marcas */
    /** @var \Illuminate\Support\Collection $modelos */
    /** @var ?int $marcaSelecionadaId */

    $marcaSelecionadaId = $marcaSelecionadaId ?? null;
@endphp

<div class="col-md-3">
    <label class="form-label">Marca *</label>
    <div class="mb-2">
        <input type="search" id="marca-search" class="form-control" placeholder="Buscar marca..." autocomplete="off">
    </div>
    <select name="marca" id="sel-marca" class="form-select @error('marca') is-invalid @enderror" required>
        <option value="" @selected(old('marca') === null || old('marca') === '')>Selecione...</option>
        @foreach($marcas as $m)
            <option value="{{ $m->nome }}" data-marca-id="{{ $m->id }}" @selected(old('marca', $veiculo->marca ?? null) === $m->nome)>
                {{ $m->nome }}
            </option>
        @endforeach
    </select>
    @error('marca')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-3">
    <label class="form-label">Modelo *</label>
    <select name="modelo" id="sel-modelo" class="form-select @error('modelo') is-invalid @enderror" required>
        <option value="" @selected(old('modelo') === null || old('modelo') === '')>Selecione...</option>
        @foreach(($modelos ?? collect()) as $md)
            <option value="{{ $md->nome }}" @selected(old('modelo', $veiculo->modelo ?? null) === $md->nome)>
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
(function() {
    const selMarca = document.getElementById('sel-marca');
    const selModelo = document.getElementById('sel-modelo');
    if (!selMarca || !selModelo) return;

    async function carregarModelos(marcaId, preservarValor = true) {
        if (!marcaId) return;

        const url = '{{ url('/modelos-por-marca') }}/' + marcaId;
        const preservar = preservarValor ? selModelo.value : '';

        try {
            const resp = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!resp.ok) throw new Error('HTTP ' + resp.status);
            const modelos = await resp.json();

            selModelo.innerHTML = '<option value="">Selecione...</option>';
            for (const m of modelos) {
                const o = document.createElement('option');
                o.value = m.nome;
                o.textContent = m.nome;
                if (preservar && preservar === m.nome) {
                    o.selected = true;
                }
                selModelo.appendChild(o);
            }
        } catch (err) {
            console.warn('Falha ao carregar modelos por marca:', err);
            // Em caso de erro, mantém a seleção atual para não travar o submit.
        }
    }

    selMarca.addEventListener('change', function() {
        const opt = selMarca.options[selMarca.selectedIndex];
        const marcaId = opt ? opt.getAttribute('data-marca-id') : null;
        carregarModelos(marcaId, true);
    });

    // Para create: se já houver marca selecionada por old(), já carrega os modelos.
    const optInicial = selMarca.options[selMarca.selectedIndex];
    const marcaIdInicial = optInicial ? optInicial.getAttribute('data-marca-id') : null;
    if (marcaIdInicial) {
        carregarModelos(marcaIdInicial, true);
    }

    const searchInput = document.getElementById('marca-search');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const text = this.value.toLowerCase().trim();
            for (const option of selMarca.options) {
                if (!option.value) {
                    option.hidden = false;
                    option.style.display = '';
                    continue;
                }
                const matches = option.value.toLowerCase().includes(text);
                option.hidden = !matches;
                option.style.display = matches ? '' : 'none';
            }
        });
    }
})();
</script>
@endpush



