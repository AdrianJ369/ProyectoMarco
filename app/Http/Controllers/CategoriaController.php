<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        $categorias = Categoria::all();  // Obtener todas las categorías
        return view('Categorias.crearcategorias', compact('categorias'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:categorias,nombre',
        ]);

        // Crear la nueva categoría
        Categoria::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

    
        return redirect()->route('categorias.create')->with('success', 'Categoría creada con éxito.');
    }


    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('Categorias.editarcategorias', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|unique:categorias,nombre,' . $categoria->id,
        ]);

        $categoria->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('categorias.create')->with('success', 'Categoría actualizada correctamente.');
    }


    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $categoria->delete();

        return redirect()->route('categorias.create')->with('deleted', 'Categoría eliminada correctamente.');
    }
}
