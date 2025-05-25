<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'titulo',
        'total'
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function conceptos()
    {
        return $this->belongsToMany(Categoria::class, 'concepto_cotizacion')
            ->withPivot([
                'nombre_concepto',
                'descripcion',
                'precio',
                'created_at',
                'updated_at'
            ]);
    }
}