@extends('layouts.app')
@section('title', 'Meu perfil')
@section('breadcrumb', 'Meu perfil')

@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">Foto de perfil</div>
            <div class="card-body text-center">
                <div style="width:132px;height:132px;border-radius:50%;margin:0 auto 1rem;position:relative;">
                    <div style="width:132px;height:132px;border-radius:50%;background:var(--surface2);display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid var(--border2);">
                        @if($user->profilePhotoUrl())
                            <img id="profile_photo_preview" src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <img id="profile_photo_preview" src="" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;display:none;">
                            <span id="profile_photo_initial" style="font-family:'Syne',sans-serif;font-size:38px;font-weight:800;color:#fff;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                    <button type="button" id="btn-trocar-foto" class="btn btn-danger btn-sm" title="Alterar foto" style="position:absolute;right:2px;bottom:8px;width:34px;height:34px;border-radius:50%;padding:0;display:flex;align-items:center;justify-content:center;border:2px solid var(--surface);">
                        <i class="bi bi-pencil-fill"></i>
                    </button>
                </div>
                <p class="text-muted small mb-0">Envie uma imagem JPG, PNG ou WEBP de até 5 MB.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
                <span>Informações da conta</span>
                <button type="button" class="btn btn-outline-danger btn-sm" id="btn-editar-perfil">
                    <i class="bi bi-pencil-square me-1"></i>Editar informações da conta
                </button>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="row g-3" id="perfil-form">
                    @csrf
                    @method('PUT')

                    <div class="col-md-6">
                        <label class="form-label">Nome</label>
                        <input type="text" name="name" class="form-control js-profile-field @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control js-profile-field @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <input type="file" name="profile_photo" id="profile_photo" class="d-none @error('profile_photo') is-invalid @enderror" accept="image/*">
                    @error('profile_photo')
                        <div class="col-12">
                            <div class="alert alert-danger mb-0">{{ $message }}</div>
                        </div>
                    @enderror

                    @if($user->isCliente())
                        <div class="col-md-4">
                            <label class="form-label">CPF</label>
                            <input type="text" name="cpf" class="form-control js-profile-field @error('cpf') is-invalid @enderror" value="{{ old('cpf', $user->cliente?->cpf) }}" required>
                            @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control js-profile-field @error('telefone') is-invalid @enderror" value="{{ old('telefone', $user->cliente?->telefone) }}" required>
                            @error('telefone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            @php $estadoSelecionado = old('estado', $user->cliente?->estado); @endphp
                            <select name="estado" id="estado-select" class="form-select js-profile-field @error('estado') is-invalid @enderror">
                                <option value="">Selecione...</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->uf }}" @selected($estadoSelecionado === $estado->uf)>{{ $estado->nome }} / {{ $estado->uf }}</option>
                                @endforeach
                            </select>
                            @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cidade</label>
                            @php $cidadeSelecionada = old('cidade', $user->cliente?->cidade); @endphp
                            <select name="cidade" id="cidade-select" class="form-select js-profile-field" data-selected="{{ $cidadeSelecionada }}">
                                <option value="">Selecione um estado primeiro...</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Endereço</label>
                            <input type="text" name="endereco" class="form-control js-profile-field" value="{{ old('endereco', $user->cliente?->endereco) }}">
                        </div>
                    @endif

                    @if($user->isMecanico())
                        <div class="col-md-4">
                            <label class="form-label">CPF</label>
                            <input type="text" name="cpf" class="form-control js-profile-field @error('cpf') is-invalid @enderror" value="{{ old('cpf', $user->mecanico?->cpf) }}">
                            @error('cpf')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control js-profile-field" value="{{ old('telefone', $user->mecanico?->telefone) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Especialidade</label>
                            <input type="text" name="especialidade" class="form-control js-profile-field" value="{{ old('especialidade', $user->mecanico?->especialidade) }}">
                        </div>
                    @endif

                    <div class="col-12 text-end d-none" id="perfil-actions">
                        <button class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Salvar perfil
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($user->isCliente())
            <div class="card mt-3">
                <div class="card-header">Senha</div>
                <div class="card-body d-flex align-items-center justify-content-between gap-3 flex-wrap">
                    <div>
                        <div class="fw-500">Troca de senha</div>
                        <div class="text-muted small">
                            {{ $user->password_change_requested_at ? 'Solicitada em '.$user->password_change_requested_at->format('d/m/Y H:i') : 'Solicite ao gerente uma alteração de senha.' }}
                        </div>
                    </div>
                    <div class="text-end">
                        <form method="POST" action="{{ route('conta.senha.solicitar') }}">
                            @csrf
                            <button class="btn btn-outline-danger" @disabled($user->password_change_requested_at)>
                                <i class="bi bi-key me-1"></i>
                                {{ $user->password_change_requested_at ? 'Senha solicitada' : 'Solicitar troca de senha' }}
                            </button>
                        </form>
                        <button type="button" class="btn btn-link btn-sm text-danger text-decoration-none px-0 mt-1" id="btn-trocar-senha-manual">
                            ou trocar manualmente
                        </button>
                    </div>
                </div>
                <div class="border-top px-3 py-3" id="painel-trocar-senha-manual" style="{{ $errors->passwordUpdate->any() ? '' : 'display:none;' }}">
                    <form method="POST" action="{{ route('perfil.password') }}" class="row g-3">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-4">
                            <label for="current_password" class="form-label">Senha atual</label>
                            <input type="password" name="current_password" id="current_password" class="form-control @error('current_password', 'passwordUpdate') is-invalid @enderror" required autocomplete="current-password">
                            @error('current_password', 'passwordUpdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="manual_password" class="form-label">Nova senha</label>
                            <input type="password" name="password" id="manual_password" class="form-control @error('password', 'passwordUpdate') is-invalid @enderror" minlength="8" required autocomplete="new-password">
                            @error('password', 'passwordUpdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label for="manual_password_confirmation" class="form-label">Confirmar senha</label>
                            <input type="password" name="password_confirmation" id="manual_password_confirmation" class="form-control" minlength="8" required autocomplete="new-password">
                        </div>

                        <div class="col-12 d-flex align-items-center justify-content-between gap-2 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="mostrar-senha-manual">
                                <label class="form-check-label" for="mostrar-senha-manual">Mostrar senha</label>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary" id="btn-cancelar-senha-manual">Cancelar</button>
                                <button class="btn btn-danger">
                                    <i class="bi bi-check2-circle me-1"></i>Alterar senha
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const photoButton = document.getElementById('btn-trocar-foto');
    const photoInput = document.getElementById('profile_photo');
    const photoPreview = document.getElementById('profile_photo_preview');
    const photoInitial = document.getElementById('profile_photo_initial');
    const profileForm = document.getElementById('perfil-form');
    const estadoSelect = document.getElementById('estado-select');
    const cidadeSelect = document.getElementById('cidade-select');
    const editButton = document.getElementById('btn-editar-perfil');
    const profileActions = document.getElementById('perfil-actions');
    const profileFields = document.querySelectorAll('.js-profile-field');
    const shouldStartEditing = @json($errors->any());
    const manualPasswordButton = document.getElementById('btn-trocar-senha-manual');
    const manualPasswordPanel = document.getElementById('painel-trocar-senha-manual');
    const cancelManualPassword = document.getElementById('btn-cancelar-senha-manual');
    const showManualPassword = document.getElementById('mostrar-senha-manual');
    const currentPassword = document.getElementById('current_password');
    const manualPassword = document.getElementById('manual_password');
    const manualPasswordConfirmation = document.getElementById('manual_password_confirmation');
    const cidadesPorEstado = @json($estados->mapWithKeys(fn($estado) => [$estado->uf => $estado->cidades->pluck('nome')->values()]));

    function setProfileEditing(isEditing) {
        profileFields.forEach((field) => {
            if (field.tagName === 'SELECT') {
                field.classList.toggle('pe-none', !isEditing);
                field.setAttribute('aria-disabled', isEditing ? 'false' : 'true');
                field.tabIndex = isEditing ? 0 : -1;
            } else {
                field.readOnly = !isEditing;
            }
        });

        if (profileActions) {
            profileActions.classList.toggle('d-none', !isEditing);
        }

        if (editButton) {
            editButton.classList.toggle('d-none', isEditing);
        }
    }

    setProfileEditing(shouldStartEditing);

    if (editButton) {
        editButton.addEventListener('click', function () {
            setProfileEditing(true);
            const firstField = document.querySelector('.js-profile-field');
            if (firstField) {
                firstField.focus();
            }
        });
    }

    if (manualPasswordButton && manualPasswordPanel) {
        manualPasswordButton.addEventListener('click', function () {
            manualPasswordPanel.style.display = '';

            if (currentPassword) {
                currentPassword.focus();
            }
        });
    }

    if (cancelManualPassword && manualPasswordPanel) {
        cancelManualPassword.addEventListener('click', function () {
            manualPasswordPanel.style.display = 'none';

            [currentPassword, manualPassword, manualPasswordConfirmation].forEach((field) => {
                if (field) {
                    field.value = '';
                    field.type = 'password';
                }
            });

            if (showManualPassword) {
                showManualPassword.checked = false;
            }
        });
    }

    if (showManualPassword) {
        showManualPassword.addEventListener('change', function () {
            const type = this.checked ? 'text' : 'password';
            [currentPassword, manualPassword, manualPasswordConfirmation].forEach((field) => {
                if (field) {
                    field.type = type;
                }
            });
        });
    }

    if (photoButton && photoInput) {
        photoButton.addEventListener('click', function () {
            photoInput.click();
        });

        photoInput.addEventListener('change', function () {
            if (!this.files || !this.files[0]) {
                return;
            }

            if (photoPreview) {
                photoPreview.src = URL.createObjectURL(this.files[0]);
                photoPreview.style.display = 'block';
            }

            if (photoInitial) {
                photoInitial.style.display = 'none';
            }

            if (profileForm) {
                profileForm.requestSubmit();
            }
        });
    }

    function carregarCidades() {
        if (!estadoSelect || !cidadeSelect) {
            return;
        }

        const uf = estadoSelect.value;
        const selected = cidadeSelect.dataset.selected || '';
        const cidades = cidadesPorEstado[uf] || [];

        cidadeSelect.innerHTML = '<option value="">Selecione...</option>';

        cidades.forEach((cidade) => {
            const option = document.createElement('option');
            option.value = cidade;
            option.textContent = cidade;
            option.selected = cidade === selected;
            cidadeSelect.appendChild(option);
        });
    }

    if (estadoSelect && cidadeSelect) {
        carregarCidades();
        estadoSelect.addEventListener('change', function () {
            cidadeSelect.dataset.selected = '';
            carregarCidades();
        });
    }

});
</script>
@endpush
