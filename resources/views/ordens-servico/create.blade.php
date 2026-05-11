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
        <form method="POST" action="{{ route('os.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                {{-- Cliente (oculto) --}}
                <input type="hidden" name="cliente_id" value="{{ optional(Auth::user()->cliente)->id }}">


                {{-- Veículo (dinâmico via JS) --}}
                <div class="col-md-6">
                    <label class="form-label">Veículo *</label>
                    <select name="veiculo_id" id="sel-veiculo" class="form-select @error('veiculo_id') is-invalid @enderror" required>
                        <option value="">Selecione o veículo…</option>
                    </select>
                    @error('veiculo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- KM --}}
                <div class="col-md-6">
                    <label class="form-label">Km na estrada</label>
                    <div class="input-group">
                        <input type="number" name="km_entrada" id="km_entrada" class="form-control font-mono"
                               value="{{ old('km_entrada') }}" placeholder="ex: 45000" min="0" aria-label="Km na entrada" step="1">
                        <span class="input-group-text">km</span>
                    </div>
                    <div class="form-text">Ao selecionar o veículo, preenche automaticamente o km atual.</div>
                </div>

                {{-- Sintomas --}}
                <div class="col-12">
                    <label class="form-label">Sintomas/Sua queixa </label>
                    <textarea name="sintomas" class="form-control @error('sintomas') is-invalid @enderror"
                              rows="4" required placeholder="Descreva seu relato…">{{ old('sintomas') }}</textarea>

                    @error('sintomas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                {{-- Mídia para diagnóstico (na hora de abrir) --}}
                <div class="col-md-6">
                    <label class="form-label">Foto do defeito (obrigatória) </label>
                    <input type="file" name="foto_defeito" id="foto_defeito" class="form-control @error('foto_defeito') is-invalid @enderror" accept="image/*" required>

                    @error('foto_defeito')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Envie uma foto para ajudar no diagnóstico.</div>

                    <div class="mt-2" id="preview-foto" style="display:none;">
                        <img id="img-preview" class="img-fluid rounded" style="max-height:160px;object-fit:cover;" alt="Prévia da foto" />
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Vídeo do defeito (Opcional)  </label>
                    <input type="file" name="video_defeito" id="video_defeito" class="form-control @error('video_defeito') is-invalid @enderror" accept="video/*">
                    @error('video_defeito')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Se o defeito for perceptível/audível, envie vídeo também.</div>

                    <div class="mt-2" id="preview-video" style="display:none;">
                        <video id="video-preview" class="w-100 rounded" style="max-height:160px;object-fit:cover;" controls></video>
                    </div>
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
(function () {
    const clienteId = document.querySelector('input[name="cliente_id"]').value;
    const sel = document.getElementById('sel-veiculo');
    const kmEntrada = document.getElementById('km_entrada');

    if (!clienteId) return;

    sel.innerHTML = '<option value="">Carregando…</option>';

    fetch(`/clientes/${clienteId}/veiculos`)
        .then(r => r.json())
        .then(veiculos => {
            if (veiculos.length === 0) {
                sel.innerHTML = '<option value="">Nenhum veículo cadastrado</option>';
                return;
            }
            sel.innerHTML = '<option value="">Selecione o veículo…</option>';
            veiculos.forEach(v => {
                sel.innerHTML += `<option value="${v.id}" data-km-atual="${v.km_atual ?? ''}">${v.marca} ${v.modelo} ${v.ano} — ${v.placa}</option>`;
            });

            // Pré-preencher km (km atual do veículo)
            const optInicial = sel.options[sel.selectedIndex];
            if (optInicial) {
                const km = optInicial.getAttribute('data-km-atual');
                if (km !== null && km !== '') {
                    kmEntrada.value = km;
                }
            }

            sel.addEventListener('change', function () {
                const opt = sel.options[sel.selectedIndex];
                if (!opt) return;
                const km = opt.getAttribute('data-km-atual');
                if (km !== null && km !== '') {
                    kmEntrada.value = km;
                }
            });
        });
})();

(function () {
    const fotoInput = document.getElementById('foto_defeito');
    const videoInput = document.getElementById('video_defeito');

    const previewFoto = document.getElementById('preview-foto');
    const imgPreview = document.getElementById('img-preview');

    const previewVideo = document.getElementById('preview-video');
    const videoPreview = document.getElementById('video-preview');

    if (fotoInput && previewFoto && imgPreview) {
        fotoInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) {
                previewFoto.style.display = 'none';
                imgPreview.removeAttribute('src');
                return;
            }

            const url = URL.createObjectURL(file);
            imgPreview.src = url;
            previewFoto.style.display = '';
        });
    }

    if (videoInput && previewVideo && videoPreview) {
        videoInput.addEventListener('change', function () {
            const file = this.files && this.files[0];
            if (!file) {
                previewVideo.style.display = 'none';
                videoPreview.removeAttribute('src');
                videoPreview.load();
                return;
            }

            const url = URL.createObjectURL(file);
            videoPreview.src = url;
            previewVideo.style.display = '';
        });
    }
})();
</script>
@endpush

