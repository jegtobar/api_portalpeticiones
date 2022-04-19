<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class menu_usuarios extends Model{
    protected $fillable = [
        'menu_id',
        'rol_id'
    ];
}
