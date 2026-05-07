@extends('layouts.app')
@section('title','Abrir Ordem de Serviço')
@section('breadcrumb','Nova OS')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card">
    <div class="card-header">
        <i class="bi bi-clipboard2-plus me-2"></i>Abrir Ordem de Serviço (UC003)
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('os.store') }}">
            @csrf
            <div class="row g-3">
                {{-- Cliente --}}
                <div class="col-md-6">
                    <label class="form-label">Cliente *</label>
                    <select name="cliente_id" id="sel-cliente" class="form-select @error('cliente_id') is-invalid @enderror" required>
                        <option value="">Selecione o cliente…</option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id }}" {{ old('cliente_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->nome }} — {{ $c->cpf }}
                        </option>
                        @endforeach
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Veículo (dinâmico via JS) --}}
                <div class="col-md-6">
                    <label class="form-label">Veículo *</label>
                    <select name="veiculo_id" id="sel-veiculo" class="form-select @error('veiculo_id') is-invalid @enderror" required>
                        <option value="">Selecione o cliente primeiro…</option>
                    </select>
                    @error('veiculo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Mecânico --}}
                <div class="col-md-6">
                    <label class="form-label">Mecânico responsável</label>
                    <select name="mecanico_id" class="form-select">
                        <option value="">A definir</option>
                        @foreach($mecanicos as $m)
                        <option value="{{ $m->id }}" {{ old('mecanico_id') == $m->id ? 'selected' : '' }}>{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- KM --}}
                <div class="col-md-6">
                    <label class="form-label">Km na entrada</label>
                    <input type="number" name="km_entrada" class="form-control font-mono"
                           value="{{ old('km_entrada') }}" placeholder="ex: 45000" min="0">
                </div>

                {{-- Sintomas --}}
                <div class="col-12">
                    <label class="form-label">Sintomas / Queixa do cliente *</label>
                    <textarea name="sintomas" class="form-control @error('sintomas') is-invalid @enderror"
                              rows="4" required placeholder="Descreva o que o cliente relatou…">{{ old('sintomas') }}</textarea>
                    @error('sintomas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-clipboard2-check me-1"></i>Abrir OS
                </button>
                <a href="{{ route('os.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection

@push('scripts')
<script>
document.getElementById('sel-cliente').addEventListener('change', function () {
    const clienteId = this.value;
    const sel = document.getElementById('sel-veiculo');
    sel.innerHTML = '<option value="">Carregando…</option>';

    if (!clienteId) {
        sel.innerHTML = '<option value="">Selecione o cliente primeiro…</option>';
        return;
    }

    fetch(`/clientes/${clienteId}/veiculos`)
        .then(r => r.json())
        .then(veiculos => {
            if (veiculos.length === 0) {
                sel.innerHTML = '<option value="">Nenhum veículo cadastrado</option>';
                return;
            }
            sel.innerHTML = '<option value="">Selecione o veículo…</option>';
            veiculos.forEach(v => {
                sel.innerHTML += `<option value="${v.id}">${v.marca} ${v.modelo} ${v.ano} — ${v.placa}</option>`;
            });
        });
});
</script>
@endpush
