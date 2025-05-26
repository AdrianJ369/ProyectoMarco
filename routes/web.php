<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;

// Página principal después del Login
Route::get('/', [AuthController::class, 'loginForm'])->name('login.form');

// Procesar login
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Página de registro
Route::get('/register', [AuthController::class, 'registerForm'])->name('register.form');

// Procesar registro
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Cerrar sesión
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas por login
Route::middleware('auth')->group(function () {

    // Menú principal
    Route::get('/dashboard', function () {
        return view('menuprincipal');
    })->name('dashboard');

    // Editar perfiles
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('profile.edit');
    Route::post('/perfil/editar', [PerfilController::class, 'update'])->name('profile.update');

    // Cotizaciones (se incluye todo: index, create, store, edit, update, destroy)
    Route::resource('cotizaciones', CotizacionController::class)->except(['show']);

    // Categorías
    Route::resource('categorias', CategoriaController::class);

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Conceptos
    Route::resource('conceptos', ConceptoController::class);
});
