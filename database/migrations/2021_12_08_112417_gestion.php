<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Gestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gestiones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('descripcion');
            $table->string('canal');
            $table->string('direccion');
            $table->unsignedBigInteger('zona');
            $table->unsignedBigInteger('distrito');
            $table->unsignedBigInteger('sector');
            $table->unsignedBigInteger('colonia');
            $table->string('oficio')->nullable();
            $table->string('estatus');
            $table->string('satisfaccion')->nullable();
            $table->string('usuario_creador');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('persona_id');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('persona_id')->references('id')->on('personas');
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
        Schema::dropIfExists('gestiones');
    }
}
