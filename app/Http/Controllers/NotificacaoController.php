<?php

namespace App\Http\Controllers;

use App\Models\Mecanico;
use App\Models\Notificacao;
use App\Models\OrdemServico;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    // Mostrar notificações para assistente/gerente
    public function index()
    {
        $notificacoes_pendentes = Notificacao::where('user_id', auth()->id())
            ->where('status', 'pendente')
            ->with(['os.cliente', 'os.veiculo'])
            ->orderBy('created_at', 'desc')
            ->get();

        $notificacoes_respondidas = Notificacao::where('user_id', auth()->id())
            ->whereIn('status', ['aceita', 'recusada'])
            ->with(['os.cliente', 'os.veiculo'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('notificacoes.index', compact('notificacoes_pendentes', 'notificacoes_respondidas'));
    }

    // Aceitar OS
    public function aceitar(Notificacao $notificacao)
    {
        if ($notificacao->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Sem permissão');
        }

        $notificacao->update(['status' => 'aceita', 'lida' => true]);

        $mecanico = auth()->user()->mecanico;

        if (!$mecanico) {
            $defaultEmail = env('OS_DEFAULT_MECANICO_EMAIL', 'jose@autotech.com');
            $mecanico = Mecanico::whereHas('user', fn($q) => $q->where('email', $defaultEmail))->first();

            if (!$mecanico) {
                return redirect()->back()->with('error', 'Nenhum mecânico disponível para receber esta OS.');
            }
        }

        $notificacao->os->update([
            'status' => 'em_diagnostico',
            'mecanico_id' => $mecanico->id,
        ]);

        return redirect()->route('os.show', $notificacao->os)
            ->with('success', 'OS aceita! Encaminhada para o mecânico ' . $mecanico->nome . '.');
    }

    // Recusar OS
    public function recusar(Notificacao $notificacao, Request $request)
    {
        if ($notificacao->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Sem permissão');
        }

        $request->validate(['motivo' => 'required|string|max:255']);

        $notificacao->update([
            'status' => 'recusada',
            'lida' => true,
            'mensagem' => $request->motivo
        ]);

        $notificacao->os->update(['status' => 'aberta', 'mecanico_id' => null]);

        return redirect()->route('notificacoes.index')
            ->with('success', 'OS recusada.');
    }

    // Marcar como lida (via AJAX)
    public function marcarLida(Notificacao $notificacao)
    {
        if ($notificacao->user_id !== auth()->id()) {
            return response()->json(['error' => 'Sem permissão'], 403);
        }

        $notificacao->update(['lida' => true]);

        return response()->json(['success' => true]);
    }

    // Contar não lidas (para badge)
    public function contarNaoLidas()
    {
        $count = Notificacao::where('user_id', auth()->id())
            ->where('lida', false)
            ->where('status', 'pendente')
            ->count();

        return response()->json(['count' => $count]);
    }
}
