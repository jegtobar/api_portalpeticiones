<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class mnto_vecinos_muysatisfechos extends Model{
    protected $fillable = [
        'persona_id',
        'actividad_id',
        'descripcion',
        'responsable',
        'realizado',
        'fecha',
        'auditor',
        'usuario_creador'
    ];
}