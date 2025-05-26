<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ConceptoController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\AuthController;
use
App\Http\Controllers\PerfilController;

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

// Rutas protegidas por login (cuando ya  se está logueado)
Route::middleware('auth')->group(function () {

    // Menú principal
    Route::get('/dashboard', function () {
        return view('menuprincipal');
    })->name('dashboard');


    //Editar perfiles
    Route::middleware('auth')->group(function () {
        Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('profile.edit');
        Route::post('/perfil/editar', [PerfilController::class, 'update'])->name('profile.update');
    });


    // Crear nuevas cotizaciones
    Route::middleware(['auth'])->group(function () {

    });

    Route::middleware('auth')->group(function () {
        // ... otras rutas ...

        Route::resource('cotizaciones', CotizacionController::class)->except(['show']);

        // Ruta adicional para crear
        Route::get('/cotizaciones/crear', [CotizacionController::class, 'create'])
            ->name('cotizaciones.create');
    });

    // Ruta para crear categorías
    Route::get('/categorias/create', [CategoriaController::class, 'create'])->name('categorias.create');

    //Eliminar categorías
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy'])->name('categorias.destroy');


    // Clientes (quizá agregue administradores)
    Route::resource('clientes', ClienteController::class);

    // Categorías
    Route::resource('categorias', CategoriaController::class);

    // Conceptos
    Route::resource('conceptos', ConceptoController::class);

    // Cotizaciones
    Route::resource('cotizaciones', CotizacionController::class);


});
