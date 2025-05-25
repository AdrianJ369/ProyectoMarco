<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    use HasFactory;

    protected $table = 'conceptos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
    ];


    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }


    public function cotizaciones()
    {
        return $this->belongsToMany(Cotizacion::class, 'concepto_cotizacion')
            ->withPivot('nombre', 'descripcion', 'cantidad', 'precio_unitario', 'subtotal')
            ->withTimestamps();
    }
}