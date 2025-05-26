<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Concepto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CotizacionController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $cotizaciones = Cotizacion::where('cliente_id', Auth::id())->get();
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('Cotizaciones.crearcotizacion', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string',
            'conceptos' => 'required|array'
        ]);

        // Calcular el total
        $total = collect($request->conceptos)->sum('precio');

        // Crear la cotización
        $cotizacion = Cotizacion::create([
            'cliente_id' => Auth::id(),
            'titulo' => $request->titulo,
            'total' => $total
        ]);

        // Adjuntar conceptos
        foreach ($request->conceptos as $concepto) {
            $cotizacion->conceptos()->attach($concepto['categoria_id'], [
                'nombre_concepto' => $concepto['nombre'],
                'descripcion' => $concepto['descripcion'],
                'precio' => $concepto['precio']
            ]);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('cotizaciones.index')
        ]);
    }

    public function edit(Cotizacion $cotizacion)
    {
        $this->authorize('update', $cotizacion);

        $conceptos = Concepto::all();
        return view('cotizaciones.edit', compact('cotizacion', 'conceptos'));
    }

    public function update(Request $request, Cotizacion $cotizacion)
    {
        $this->authorize('update', $cotizacion);

        $request->validate([
            'nombre' => 'required',
            'conceptos' => 'required|array',
        ]);

        $cotizacion->update([
            'nombre' => $request->nombre,
        ]);

        $cotizacion->conceptos()->sync($request->conceptos);

        return redirect()->route('cotizaciones.index');
    }

    public function destroy(Cotizacion $cotizacion)
    {
        $this->authorize('delete', $cotizacion);

        $cotizacion->delete();
        return redirect()->route('cotizaciones.index');
    }
}
