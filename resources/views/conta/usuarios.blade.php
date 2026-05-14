@extends('layouts.app')
@section('title', 'Gestao de Cargos')
@section('breadcrumb', 'Gestao de Cargos')

@section('content')
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span><i class="bi bi-person-badge me-2"></i>Gestao de Cargos</span>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($gerente)
            <div class="mb-4">
                <h5 class="mb-3">Autorizacao do Gerente</h5>

                @if(! $accessGranted)
                    <p>Para acessar a lista de cargos, o gerente deve informar a senha de acesso.</p>

                    @if($accessError)
                        <div class="alert alert-danger">{{ $accessError }}</div>
                    @endif

                    <form method="POST" action="{{ route('conta.usuarios.autorizar') }}" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label for="senha" class="form-label">Senha de autorizacao</label>
                            <input type="password" name="senha" id="senha" class="form-control" required autocomplete="off">
                        </div>
                        <div class="col-md-6 d-flex align-items-end gap-2">
                            <button class="btn btn-primary">Autorizar acesso</button>
                        </div>
                    </form>
                @else
                    <div class="alert alert-success d-flex justify-content-between align-items-center">
                        <span>Acesso autorizado. Veja abaixo a lista de cargos.</span>
                        <form method="POST" action="{{ route('conta.usuarios.fechar') }}">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm">Fechar acesso</button>
                        </form>
                    </div>
                @endif
            </div>

            @if($accessGranted)
                <div>
                    <h5 class="mb-3">Cargos</h5>

                    <form method="GET" action="{{ route('conta.usuarios') }}" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Filtrar cargo</label>
                            <select name="role" class="form-select">
                                <option value="">Todos</option>
                                <option value="gerente" @selected(old('role', $filterRole ?? '') === 'gerente')>Gerente</option>
                                <option value="atendente" @selected(old('role', $filterRole ?? '') === 'atendente')>Atendente</option>
                                <option value="mecanico" @selected(old('role', $filterRole ?? '') === 'mecanico')>Mecanico</option>
                                <option value="cliente" @selected(old('role', $filterRole ?? '') === 'cliente')>Cliente</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pesquisar nome ou email</label>
                            <input type="text" name="search" value="{{ old('search', $filterSearch ?? '') }}" class="form-control" placeholder="Buscar...">
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button class="btn btn-primary">Filtrar</button>
                            <a href="{{ route('conta.usuarios') }}" class="btn btn-outline-secondary">Limpar</a>
                        </div>
                    </form>

                    @php
                        $groups = $users->groupBy('role');
                        $order = ['gerente' => 'Gerente', 'atendente' => 'Atendente', 'mecanico' => 'Mecanico', 'cliente' => 'Cliente'];
                    @endphp

                    @foreach($order as $roleKey => $roleLabel)
                        <div class="mb-4">
                            <h6 class="text-white mb-3">{{ $roleLabel }}</h6>

                            @if(isset($groups[$roleKey]) && $groups[$roleKey]->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groups[$roleKey] as $user)
                                                <tr>
                                                    <td>
                                                        @if($user->isCliente())
                                                            <div class="client-name-stack">
                                                                <span class="client-avatar" title="{{ $user->isOnline() ? 'Online' : 'Offline' }}">
                                                                    @if($user->profilePhotoUrl())
                                                                        <img src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->name }}">
                                                                    @else
                                                                        {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? 'X', 0, 1)) }}
                                                                    @endif
                                                                    <span class="status-dot {{ $user->isOnline() ? 'online' : 'offline' }}"></span>
                                                                </span>
                                                                <span>
                                                                    <span class="fw-semibold">{{ $user->name }}</span>
                                                                    <br>
                                                                    <span class="small text-muted">{{ $user->isOnline() ? 'Online agora' : 'Offline' }}</span>
                                                                </span>
                                                            </div>
                                                        @else
                                                            {{ $user->name }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td class="text-end">
                                                        <a
                                                            class="btn btn-sm btn-outline-primary"
                                                            href="{{ route('conta.usuarios.detalhes', $user) }}"
                                                            title="Ver detalhes"
                                                        >
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-secondary py-2">Nenhum perfil de {{ strtolower($roleLabel) }} cadastrado.</div>
                            @endif
                        </div>
                    @endforeach

                </div>
            @endif
        @else
            <div class="mb-4">
                <h5 class="mb-3">Solicitacao de Acesso</h5>
                <p>O assistente necessita de autorizacao do gerente para acessar a gestao de cargos.</p>
                <form method="POST" action="{{ route('conta.usuarios.solicitar') }}">
                    @csrf
                    <button class="btn btn-primary" @disabled($solicitado)>
                        {{ $solicitado ? 'Autorizacao ja solicitada' : 'Solicitar autorizacao' }}
                    </button>
                </form>
            </div>

            @if($solicitado)
                <div class="alert alert-info">
                    Solicitacao enviada. Aguarde a autorizacao do gerente para acessar esta area.
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
