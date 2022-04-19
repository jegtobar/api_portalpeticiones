<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class seguimientos_gestiones extends Model{
    protected $fillable = [
        'gestion_id',
        'actividad_id',
        'descripcion',
        'fecha',
        'usuario_id'
    ];
}