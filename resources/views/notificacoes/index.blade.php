@extends('layouts.app')
@section('title', 'Notificações')
@section('breadcrumb', 'Notificações')

@section('content')
<div class="row g-3">
    {{-- Notificações Pendentes --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span>
                    <i class="bi bi-bell-fill me-2 text-warning"></i>
                    Solicitações Aguardando Resposta
                    @if($notificacoes_pendentes->count() > 0)
                        <span class="badge bg-danger">{{ $notificacoes_pendentes->count() }}</span>
                    @endif
                </span>
            </div>
            <div class="card-body p-0">
                @if($notificacoes_pendentes->isEmpty())
                    <p class="text-center text-muted py-4">Nenhuma solicitação pendente no momento.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>OS</th>
                                    <th>Cliente</th>
                                    <th>Veículo</th>

                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notificacoes_pendentes as $notif)
                                <tr>
                                    <td>
                                        <span class="font-mono small">{{ $notif->os->numero }}</span>
                                    </td>
                                    <td>{{ $notif->os->cliente->nome }}</td>
                                    <td>
                                        <small>{{ $notif->os->veiculo->marca }} {{ $notif->os->veiculo->modelo }}</small>
                                        <br>
                                        <span class="badge bg-light text-dark font-mono">{{ $notif->os->veiculo->placa }}</span>
                                    </td>
                                    <td>
                                        <small>{{ $notif->created_at->format('d/m H:i') }}</small>
                                    </td>

                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('os.show', $notif->os) }}" class="btn btn-outline-secondary" title="Ver detalhes">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <form action="{{ route('notificacoes.aceitar', $notif) }}" method="POST" style="display:inline;" 
                                                  onsubmit="return confirm('Aceitar esta OS? Você será definido como o mecânico responsável.')">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Aceitar">
                                                    <i class="bi bi-check-circle"></i> Aceitar
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#recusarModal{{ $notif->id }}" title="Recusar">
                                                <i class="bi bi-x-circle"></i> Recusar
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Recusar --}}
                                <div class="modal fade" id="recusarModal{{ $notif->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Recusar OS #{{ $notif->os->numero }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('notificacoes.recusar', $notif) }}" method="POST">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="motivo{{ $notif->id }}" class="form-label">Motivo da recusa:</label>
                                                        <textarea class="form-control" id="motivo{{ $notif->id }}" name="motivo" rows="3" required 
                                                                  placeholder="Ex: Falta de peça específica, oficina lotada, etc."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-danger">Recusar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Histórico de Respostas --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Histórico (Últimas Respostas)
            </div>
            <div class="card-body p-0">
                @if($notificacoes_respondidas->isEmpty())
                    <p class="text-center text-muted py-4">Nenhum histórico para exibir.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>OS</th>
                                    <th>Cliente</th>
                                    <th>Resposta</th>
                                    <th>Data</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notificacoes_respondidas as $notif)
                                <tr>
                                    <td>
                                        <span class="font-mono small">{{ $notif->os->numero }}</span>
                                    </td>
                                    <td>{{ $notif->os->cliente->nome }}</td>
                                    <td>
                                        @if($notif->status === 'aceita')
                                            <span class="badge bg-success">Aceita</span>
                                        @else
                                            <span class="badge bg-danger">Recusada</span>
                                            @if($notif->mensagem)
                                                <br>
                                                <small class="text-muted">{{ $notif->mensagem }}</small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $notif->updated_at->format('d/m H:i') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('os.show', $notif->os) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
