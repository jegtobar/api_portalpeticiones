<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GestionDetalle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestiones_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gestion_id');
            $table->unsignedBigInteger('dependencia_id');
            $table->unsignedBigInteger('peticion_id');
            $table->string('descripcion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->string('responsable');
            $table->foreign('gestion_id')->references('id')->on('gestiones');
            $table->foreign('dependencia_id')->references('id')->on('dependencias');
            $table->foreign('peticion_id')->references('id')->on('tipo_peticiones');
            $table->string('usuario_creador');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gestiones_detalle');
    }
}
