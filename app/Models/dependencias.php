<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class dependencias extends Model{
    protected $fillable = [
        'dependencia',
        'contacto',
        'telefono'
    ];
}