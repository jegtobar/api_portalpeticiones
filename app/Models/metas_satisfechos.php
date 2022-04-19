<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class metas_satisfechos extends Model{
    protected $fillable = [
        'alcaldia_id',
        'colonia_id',
        'fecha',
        'meta',
        'por_alcanzar',
        'responsable',
        'usuario_creador',
        'created_at',
        'updated_at'
    ];
}