<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RoleAccountController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isGerente()) {
            $accessGranted = $request->session()->get('conta_usuarios_acesso', false);

            if (! $accessGranted) {
                return view('conta.usuarios', [
                    'gerente'        => true,
                    'accessGranted'  => false,
                    'accessError'    => session('access_error'),
                ]);
            }

            $query = User::with(['cliente', 'mecanico'])
                ->whereIn('role', ['gerente', 'atendente', 'mecanico', 'cliente']);

            if ($request->filled('role') && in_array($request->role, ['gerente', 'atendente', 'mecanico', 'cliente'])) {
                $query->where('role', $request->role);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $users = $query
                ->orderByRaw("FIELD(role, 'gerente', 'atendente', 'mecanico', 'cliente')")
                ->orderBy('name')
                ->get();

            return view('conta.usuarios', [
                'gerente'       => true,
                'accessGranted' => true,
                'users'         => $users,
                'filterRole'    => $request->role,
                'filterSearch'  => $request->search,
                'accessError'   => session('access_error'),
            ]);
        }

        // Assistente
        $solicitado = $request->session()->get('conta_usuarios_solicitado', false);

        return view('conta.usuarios', [
            'gerente'   => false,
            'solicitado'=> $solicitado,
        ]);
    }

    public function solicitar(Request $request)
    {
        if (! auth()->user()->isAtendente()) {
            abort(403);
        }

        if ($request->session()->get('conta_usuarios_solicitado', false)) {
            return redirect()->route('conta.usuarios')
                ->with('error', 'A autorização já foi solicitada. Aguarde o gerente liberar o acesso.');
        }

        $request->session()->put('conta_usuarios_solicitado', true);

        return redirect()->route('conta.usuarios')
            ->with('success', 'Solicitação de autorização enviada ao gerente.');
    }

    public function solicitarTrocaSenha(Request $request)
    {
        $user = auth()->user();

        if (! $user->isCliente()) {
            abort(403);
        }

        if ($user->password_change_requested_at) {
            return back()->with('error', 'Você já solicitou a troca de senha. Aguarde o gerente alterar.');
        }

        $user->update([
            'password_change_requested_at' => now(),
        ]);

        return back()->with('success', 'Solicitação de troca de senha enviada ao gerente.');
    }

    public function autorizar(Request $request)
    {
        if (! auth()->user()->isGerente()) {
            abort(403);
        }

        $validated = $request->validate([
            'senha' => 'required|string',
        ]);

        if ($validated['senha'] !== '12345678') {
            return redirect()->route('conta.usuarios')
                ->with('access_error', 'Senha incorreta. Tente novamente.');
        }

        $request->session()->put('conta_usuarios_acesso', true);

        return redirect()->route('conta.usuarios');
    }

    public function fechar(Request $request)
    {
        if (! auth()->user()->isGerente()) {
            abort(403);
        }

        $request->session()->forget('conta_usuarios_acesso');

        return redirect()->route('conta.usuarios')
            ->with('success', 'Acesso encerrado. Informe a senha novamente para reabrir.');
    }

    public function detalhes(Request $request, User $user)
    {
        if (! auth()->user()->isGerente() || ! $request->session()->get('conta_usuarios_acesso', false)) {
            abort(403);
        }

        $user->load([
            'cliente.veiculos.ordens.mecanico',
            'cliente.ordens.veiculo',
            'cliente.ordens.itens',
            'cliente.ordens.garantias',
            'mecanico.ordens.cliente',
            'mecanico.ordens.veiculo',
            'mecanico.ordens.itens',
        ]);

        return view('conta.usuario-detalhes', compact('user'));
    }

    public function atualizarSenha(Request $request, User $user)
    {
        if (! auth()->user()->isGerente() || ! $request->session()->get('conta_usuarios_acesso', false)) {
            abort(403);
        }

        if ($user->isCliente() && ! $user->password_change_requested_at) {
            return redirect()
                ->route('conta.usuarios.detalhes', $user)
                ->with('error', 'Este cliente ainda não solicitou troca de senha.');
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'A confirmação da senha não confere.',
            'password.min' => 'A senha precisa ter pelo menos 8 caracteres.',
        ]);

        $user->update([
            'password' => $validated['password'],
            'password_change_requested_at' => null,
        ]);

        return redirect()
            ->route('conta.usuarios.detalhes', $user)
            ->with('success', 'Senha atualizada com sucesso.');
    }
}
