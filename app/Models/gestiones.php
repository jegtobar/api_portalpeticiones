<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class gestiones extends Model{

    use SoftDeletes;
    
    protected $fillable =[
        'fecha',
        'canal',
        'descripcion',
        'direccion',
        'zona',
        'distrito',
        'sector',
        'colonia',
        'oficio',
        'estatus',
        'satisfaccion',
        'usuario_creador',
        'usuario_id',
        'persona_id',
        'dependencia_id',
        'peticion_id',
        'created_at',
        'updated_at'
    ];
}