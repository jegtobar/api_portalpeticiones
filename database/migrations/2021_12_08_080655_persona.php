<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Persona extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('pNombre');
            $table->string('sNombre')->nullable();
            $table->string('tNombre')->nullable();
            $table->string('pApellido');
            $table->string('sApellido')->nullable();
            $table->string('tApellido')->nullable();
            $table->unsignedBigInteger('dpi')->unique();
            $table->date('nacimiento');
            $table->string('direccion');
            $table->string('numero_casa');
            $table->integer('zona');
            $table->integer('telefono_casa')->nullable();
            $table->integer('celular');
            $table->string('correo');
            $table->string('genero');
            $table->string('liderazgo');
            $table->string('usuario_creador');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
