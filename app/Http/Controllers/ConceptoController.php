<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ConceptoController extends Controller
{
    public function index()
    {
        $conceptos = Concepto::with('categoria')->get();
        return view('conceptos.index', compact('conceptos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('Conceptos.crearconceptos', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        Concepto::create($request->all());

        return redirect()->route('conceptos.index');
    }

    public function edit(Concepto $concepto)
    {
        $categorias = Categoria::all();
        return view('conceptos.edit', compact('concepto', 'categorias'));
    }

    public function update(Request $request, Concepto $concepto)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'nullable',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $concepto->update($request->all());

        return redirect()->route('conceptos.index');
    }

    public function destroy(Concepto $concepto)
    {
        $concepto->delete();
        return redirect()->route('conceptos.index');
    }
}
