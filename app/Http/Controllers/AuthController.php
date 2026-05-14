<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'Credenciais inválidas.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:150',
            'email'                 => 'required|email|max:150|unique:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',

            // Dados obrigatórios para criar o perfil do cliente
            'cpf'      => ['required', 'string', 'max:14', Rule::unique('clientes', 'cpf')],
            'telefone' => 'required|string|max:20',
        ], [
            'name.required'           => 'O nome é obrigatório.',
            'name.max'                => 'O nome não pode ter mais de 150 caracteres.',
            'email.required'          => 'O e-mail é obrigatório.',
            'email.email'             => 'O e-mail deve ser válido.',
            'email.unique'            => 'Este e-mail já está registrado.',
            'password.required'       => 'A senha é obrigatória.',
            'password.min'            => 'A senha deve ter no mínimo 8 caracteres.',
            'password.confirmed'      => 'As senhas não conferem.',

            'cpf.required'           => 'O CPF é obrigatório.',
            'cpf.max'                => 'O CPF não pode ter mais de 14 caracteres.',
            'cpf.unique'             => 'Este CPF já está cadastrado.',
            'telefone.required'      => 'O telefone é obrigatório.',
            'telefone.max'           => 'O telefone não pode ter mais de 20 caracteres.',
        ]);


        try {
            $user = DB::transaction(function () use ($validated) {
                // Criar usuário com role 'cliente' automaticamente
                $user = User::create([
                    'name'     => $validated['name'],
                    'email'    => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role'     => 'cliente',
                ]);

                // Criar registro de cliente automaticamente
                \App\Models\Cliente::create([
                    'user_id'  => $user->id,
                    'nome'     => $validated['name'],
                    'cpf'      => $validated['cpf'],
                    'telefone' => $validated['telefone'],
                    'email'    => $validated['email'],
                ]);

                return $user;
            });
        } catch (QueryException $exception) {
            return back()
                ->withErrors(['cpf' => 'Este CPF já está cadastrado.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }


        // Fazer login automático
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Bem-vindo! Sua conta foi criada com sucesso.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
