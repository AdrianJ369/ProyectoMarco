<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('users')->onDelete('cascade');
            $table->string('titulo');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        Schema::create('concepto_cotizacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained()->onDelete('cascade');
            $table->string('nombre_concepto');
            $table->text('descripcion');
            $table->decimal('precio', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('concepto_cotizacion');
        Schema::dropIfExists('cotizaciones');
    }
};