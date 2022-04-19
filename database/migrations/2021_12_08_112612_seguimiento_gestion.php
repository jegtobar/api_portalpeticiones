<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeguimientoGestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimientos_gestion', function (Blueprint $table) {
            $table->id();
            $table->string('actividad');
            $table->string('descripcion');
            $table->date('fecha');
            $table->string('responsable');
            $table->string('usuario_creador');
            $table->unsignedBigInteger('gestion_detalle_id');
            $table->foreign('gestion_detalle_id')->references('id')->on('gestiones_detalle');
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
        Schema::dropIfExists('seguimientos_gestion');
    }
}
