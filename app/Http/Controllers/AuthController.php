<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Formulario de login
    public function loginForm()
    {
        return view('Autenticacion.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required|string',
        ]);

        $user = Cliente::where('correo', $request->correo)->first();

        if ($user && Hash::check($request->contraseña, $user->contraseña)) {
            Auth::login($user);
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'correo' => 'Las credenciales son incorrectas.',
        ])->withInput();
    }

    // Formulario de registro
    public function registerForm()
    {
        return view('Autenticacion.registro');
    }

    // Procesar registro
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:clientes,correo',
            'contraseña' => 'required|string|min:6|confirmed',
        ]);

        Cliente::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contraseña' => Hash::make($request->contraseña),
        ]);

        return redirect()->route('login.form')->with('success', '¡Cuenta creada exitosamente! Ahora puedes iniciar sesión.');
    }

    // Cerrar sesión
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.form');
    }
}
