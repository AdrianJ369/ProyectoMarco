<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    // Mostrar formulario de edición
    public function edit()
    {
        $user = Auth::user();
        return view('Perfil.editarperfil', compact('user'));
    }

    // Procesar actualización
    public function update(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255|unique:clientes,correo,' . Auth::id(),
            'password' => 'nullable|confirmed|min:6',
        ], [
            'password.min' => 'El campo contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ], [
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
        ]);

        $user = Auth::user();
        $user->nombre = $request->nombre;
        $user->correo = $request->correo;

        if ($request->filled('password')) {
            $user->contraseña = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('dashboard')
            ->with('success', 'Perfil actualizado correctamente');
    }

}
