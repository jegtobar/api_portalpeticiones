<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rol_id');
            $table->unsignedBigInteger('alcaldia_id')->nullable();
            $table->unsignedBigInteger('distrito_id')->nullable();
            $table->unsignedBigInteger('sector_id')->nullable();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('api_token')->nullable();
            $table->string('nit');
            $table->unsignedBigInteger('dpi')->unique();
            $table->string('usuario_creador');
            $table->foreign('rol_id')->references('id')->on('roles');
            $table->foreign('distrito_id')->references('id')->on('distritos');
            $table->foreign('alcaldia_id')->references('id')->on('alcaldias');
            $table->foreign('sector_id')->references('id')->on('sectores');
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
        Schema::dropIfExists('usuarios');
    }
}
