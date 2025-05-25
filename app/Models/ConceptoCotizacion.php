<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ConceptoCotizacion extends Pivot
{
    protected $table = 'concepto_cotizacion'; 

    protected $fillable = [
        'cotizacion_id',
        'concepto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];
}
