<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class personas extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pNombre',
        'sNombre',
        'tNombre',
        'pApellido',
        'sApellido',
        'tApellido',
        'dpi',
        'nacimiento',
        'direccion',
        'numero_casa',
        'zona_id',
        'colonia_id',
        'telefono_casa',
        'celular',
        'correo',
        'genero',
        'liderazgo',
        'seguimiento',
        'usuario_creador',
        'usuario_actualiza',
        'created_at',
        'updated_at'
    ];
}
