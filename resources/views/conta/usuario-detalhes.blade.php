@extends('layouts.app')
@section('title', 'Conta de ' . $user->name)
@section('breadcrumb', 'Gestao de Cargos / Conta')

@section('content')
@php
    $cliente = $user->cliente;
    $mecanico = $user->mecanico;
    $veiculos = $cliente?->veiculos ?? collect();
    $ordensCliente = $cliente?->ordens ?? collect();
    $ordensMecanico = $mecanico?->ordens ?? collect();
    $totalCliente = $ordensCliente->sum('valor_total');
    $totalMecanico = $ordensMecanico->sum('valor_total');
    $precisaSolicitacaoSenha = $user->isCliente();
    $podeAlterarSenha = ! $precisaSolicitacaoSenha || $user->password_change_requested_at;
@endphp

<div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <a href="{{ route('conta.usuarios') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar
    </a>
    @if($user->isCliente())
        <span class="client-avatar" title="{{ $user->isOnline() ? 'Online' : 'Offline' }}">
            @if($user->profilePhotoUrl())
                <img src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->name }}">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? 'X', 0, 1)) }}
            @endif
            <span class="status-dot {{ $user->isOnline() ? 'online' : 'offline' }}"></span>
        </span>
    @endif
    <h5 class="mb-0">{{ $user->name }}</h5>
    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
    @if($user->isCliente())
        <span class="badge {{ $user->isOnline() ? 'bg-success' : 'bg-secondary' }}">
            {{ $user->isOnline() ? 'Online' : 'Offline' }}
        </span>
    @endif
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">Acesso ao site</div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">Nome</dt>
                    <dd class="col-7">{{ $user->name }}</dd>

                    <dt class="col-5 text-muted">Email/login</dt>
                    <dd class="col-7">{{ $user->email }}</dd>

                    <dt class="col-5 text-muted">Cargo</dt>
                    <dd class="col-7">{{ ucfirst($user->role) }}</dd>

                    @if($user->isCliente())
                        <dt class="col-5 text-muted">Status</dt>
                        <dd class="col-7">
                            <span class="status-dot {{ $user->isOnline() ? 'online' : 'offline' }} me-1"></span>
                            {{ $user->isOnline() ? 'Online agora' : 'Offline' }}
                            @if($user->last_seen_at)
                                <br><span class="small text-muted">Visto por ultimo em {{ $user->last_seen_at->format('d/m/Y H:i') }}</span>
                            @endif
                        </dd>
                    @endif

                    <dt class="col-5 text-muted">Senha</dt>
                    <dd class="col-7">
                        Protegida por criptografia
                        @if($user->password_change_requested_at)
                            <br><span class="badge bg-warning text-dark mt-2">Troca solicitada em {{ $user->password_change_requested_at->format('d/m/Y H:i') }}</span>
                        @elseif($precisaSolicitacaoSenha)
                            <br><span class="small text-muted">Disponivel apenas quando o cliente solicitar</span>
                        @endif
                        <br>
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="btn-alterar-senha" @disabled(! $podeAlterarSenha)>
                            <i class="bi bi-key me-1"></i>
                            Alterar senha
                        </button>
                    </dd>

                    <dt class="col-5 text-muted">Criada em</dt>
                    <dd class="col-7">{{ $user->created_at?->format('d/m/Y H:i') ?? '-' }}</dd>

                    <dt class="col-5 text-muted">Atualizada em</dt>
                    <dd class="col-7">{{ $user->updated_at?->format('d/m/Y H:i') ?? '-' }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">Dados do cliente</div>
            <div class="card-body">
                @if($cliente)
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">Nome</dt>
                        <dd class="col-7">{{ $cliente->nome }}</dd>

                        <dt class="col-5 text-muted">CPF</dt>
                        <dd class="col-7">{{ $cliente->cpf }}</dd>

                        <dt class="col-5 text-muted">Telefone</dt>
                        <dd class="col-7">{{ $cliente->telefone }}</dd>

                        <dt class="col-5 text-muted">Email</dt>
                        <dd class="col-7">{{ $cliente->email ?? '-' }}</dd>

                        <dt class="col-5 text-muted">Endereco</dt>
                        <dd class="col-7">{{ $cliente->endereco ?? '-' }}</dd>

                        <dt class="col-5 text-muted">Cidade/UF</dt>
                        <dd class="col-7">{{ $cliente->cidade ?? '-' }}{{ $cliente->estado ? ' / '.$cliente->estado : '' }}</dd>
                    </dl>
                @else
                    <p class="text-muted mb-0">Esta conta nao possui cadastro de cliente vinculado.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12" id="painel-alterar-senha" style="{{ $errors->has('password') && $podeAlterarSenha ? '' : 'display:none;' }}">
        <div class="card">
            <div class="card-body py-3">
                <form method="POST" action="{{ route('conta.usuarios.senha', $user) }}" class="row g-2 align-items-end">
                    @csrf
                    @method('PATCH')

                    <div class="col-md-4">
                        <label for="password" class="form-label small mb-1">Nova senha</label>
                        <input type="password" name="password" id="password" class="form-control form-control-sm @error('password') is-invalid @enderror" minlength="8" required autocomplete="new-password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="password_confirmation" class="form-label small mb-1">Confirmar senha</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-sm" minlength="8" required autocomplete="new-password">
                    </div>

                    <div class="col-md-2">
                        <div class="form-check small mb-2">
                            <input class="form-check-input" type="checkbox" id="mostrar-senha-conta">
                            <label class="form-check-label" for="mostrar-senha-conta">Mostrar</label>
                        </div>
                    </div>

                    <div class="col-md-2 d-flex gap-2 justify-content-md-end">
                        <button class="btn btn-sm btn-primary">
                            Salvar
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-cancelar-senha">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">Dados do mecanico</div>
            <div class="card-body">
                @if($mecanico)
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted">Nome</dt>
                        <dd class="col-7">{{ $mecanico->nome }}</dd>

                        <dt class="col-5 text-muted">CPF</dt>
                        <dd class="col-7">{{ $mecanico->cpf ?? '-' }}</dd>

                        <dt class="col-5 text-muted">Telefone</dt>
                        <dd class="col-7">{{ $mecanico->telefone ?? '-' }}</dd>

                        <dt class="col-5 text-muted">Especialidade</dt>
                        <dd class="col-7">{{ $mecanico->especialidade ?? '-' }}</dd>

                        <dt class="col-5 text-muted">Status</dt>
                        <dd class="col-7">{{ $mecanico->ativo ? 'Ativo' : 'Inativo' }}</dd>
                    </dl>
                @else
                    <p class="text-muted mb-0">Esta conta nao possui cadastro de mecanico vinculado.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>Veiculos</span>
                <span class="badge bg-secondary">{{ $veiculos->count() }}</span>
            </div>
            <div class="card-body">
                @if($veiculos->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Placa</th>
                                    <th>Marca/modelo</th>
                                    <th>Ano</th>
                                    <th>Cor</th>
                                    <th>KM</th>
                                    <th>OS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($veiculos as $veiculo)
                                    <tr>
                                        <td class="font-mono">{{ $veiculo->placa }}</td>
                                        <td>{{ $veiculo->marca }} {{ $veiculo->modelo }}</td>
                                        <td>{{ $veiculo->ano }}</td>
                                        <td>{{ $veiculo->cor ?? '-' }}</td>
                                        <td>{{ number_format($veiculo->km_atual ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $veiculo->ordens->count() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Nenhum veiculo vinculado a esta conta.</p>
                @endif
            </div>
        </div>
    </div>

    @if($cliente)
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>Ordens de servico como cliente</span>
                    <span class="badge bg-secondary">{{ $ordensCliente->count() }}</span>
                </div>
                <div class="card-body">
                    @if($ordensCliente->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Numero</th>
                                        <th>Veiculo</th>
                                        <th>Status</th>
                                        <th>Abertura</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordensCliente->sortByDesc('created_at') as $ordem)
                                        <tr>
                                            <td class="font-mono">{{ $ordem->numero }}</td>
                                            <td>{{ $ordem->veiculo?->marca }} {{ $ordem->veiculo?->modelo }} - {{ $ordem->veiculo?->placa }}</td>
                                            <td><span class="badge bg-{{ $ordem->statusCor() }}">{{ $ordem->statusLabel() }}</span></td>
                                            <td>{{ $ordem->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td>R$ {{ number_format($ordem->valor_total ?? 0, 2, ',', '.') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('os.show', $ordem->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Total geral</th>
                                        <th colspan="2">R$ {{ number_format($totalCliente, 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Nenhuma OS encontrada para este cliente.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($mecanico)
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span>Ordens de servico como mecanico</span>
                    <span class="badge bg-secondary">{{ $ordensMecanico->count() }}</span>
                </div>
                <div class="card-body">
                    @if($ordensMecanico->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Numero</th>
                                        <th>Cliente</th>
                                        <th>Veiculo</th>
                                        <th>Status</th>
                                        <th>Abertura</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ordensMecanico->sortByDesc('created_at') as $ordem)
                                        <tr>
                                            <td class="font-mono">{{ $ordem->numero }}</td>
                                            <td>{{ $ordem->cliente?->nome ?? '-' }}</td>
                                            <td>{{ $ordem->veiculo?->marca }} {{ $ordem->veiculo?->modelo }} - {{ $ordem->veiculo?->placa }}</td>
                                            <td><span class="badge bg-{{ $ordem->statusCor() }}">{{ $ordem->statusLabel() }}</span></td>
                                            <td>{{ $ordem->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                            <td>R$ {{ number_format($ordem->valor_total ?? 0, 2, ',', '.') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('os.show', $ordem->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Total geral</th>
                                        <th colspan="2">R$ {{ number_format($totalMecanico, 2, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Nenhuma OS encontrada para este mecanico.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('mostrar-senha-conta');
    const password = document.getElementById('password');
    const confirmation = document.getElementById('password_confirmation');
    const openButton = document.getElementById('btn-alterar-senha');
    const closeButton = document.getElementById('btn-cancelar-senha');
    const panel = document.getElementById('painel-alterar-senha');

    if (!toggle || !password || !confirmation) {
        return;
    }

    if (openButton && panel) {
        openButton.addEventListener('click', function () {
            panel.style.display = '';
            password.focus();
        });
    }

    if (closeButton && panel) {
        closeButton.addEventListener('click', function () {
            panel.style.display = 'none';
            password.value = '';
            confirmation.value = '';
            toggle.checked = false;
            password.type = 'password';
            confirmation.type = 'password';
        });
    }

    toggle.addEventListener('change', function () {
        const type = this.checked ? 'text' : 'password';
        password.type = type;
        confirmation.type = type;
    });
});
</script>
@endpush
