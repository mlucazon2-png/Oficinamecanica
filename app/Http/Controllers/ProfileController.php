<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user()->load(['cliente', 'mecanico']);
        $estados = Estado::with('cidades')->orderBy('nome')->get();

        return view('perfil.edit', compact('user', 'estados'));
    }

    public function update(Request $request)
    {
        $user = auth()->user()->load(['cliente', 'mecanico']);

        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ];

        if ($user->isCliente()) {
            $rules += [
                'cpf' => ['required', 'string', 'max:14', Rule::unique('clientes', 'cpf')->ignore($user->cliente?->id)],
                'telefone' => ['required', 'string', 'max:20'],
                'endereco' => ['nullable', 'string', 'max:255'],
                'cidade' => ['nullable', 'string', 'max:100'],
                'estado' => ['nullable', 'string', 'size:2'],
            ];
        }

        if ($user->isMecanico()) {
            $rules += [
                'cpf' => ['nullable', 'string', 'max:14', Rule::unique('mecanicos', 'cpf')->ignore($user->mecanico?->id)],
                'telefone' => ['nullable', 'string', 'max:20'],
                'especialidade' => ['nullable', 'string', 'max:100'],
            ];
        }

        $data = $request->validate($rules, [
            'email.unique' => 'Este e-mail já está em uso.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'profile_photo.image' => 'Envie uma imagem válida.',
            'profile_photo.max' => 'A foto deve ter no máximo 5 MB.',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $data['profile_photo_path'] = $request->file('profile_photo')->store('perfis', 'public');
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'profile_photo_path' => $data['profile_photo_path'] ?? $user->profile_photo_path,
        ]);

        if ($user->cliente) {
            $user->cliente->update([
                'nome' => $data['name'],
                'cpf' => $data['cpf'],
                'telefone' => $data['telefone'],
                'email' => $data['email'],
                'endereco' => $data['endereco'] ?? null,
                'cidade' => $data['cidade'] ?? null,
                'estado' => $data['estado'] ?? null,
            ]);
        } elseif ($user->isCliente()) {
            Cliente::create([
                'user_id' => $user->id,
                'nome' => $data['name'],
                'cpf' => $data['cpf'],
                'telefone' => $data['telefone'],
                'email' => $data['email'],
                'endereco' => $data['endereco'] ?? null,
                'cidade' => $data['cidade'] ?? null,
                'estado' => $data['estado'] ?? null,
            ]);
        }

        if ($user->mecanico) {
            $user->mecanico->update([
                'nome' => $data['name'],
                'cpf' => $data['cpf'] ?? null,
                'telefone' => $data['telefone'] ?? null,
                'especialidade' => $data['especialidade'] ?? null,
            ]);
        }

        return redirect()->route('perfil.edit')->with('success', 'Perfil atualizado com sucesso.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validateWithBag('passwordUpdate', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Informe sua senha atual.',
            'current_password.current_password' => 'A senha atual está incorreta.',
            'password.required' => 'Informe a nova senha.',
            'password.min' => 'A nova senha precisa ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'password_change_requested_at' => null,
        ]);

        return redirect()
            ->route('perfil.edit')
            ->with('success', 'Senha alterada com sucesso.');
    }
}
