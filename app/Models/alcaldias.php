<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class alcaldias extends Model{
    protected $fillable = [
        'id',
        'alcaldia',
        'direccion',
        'telefono'
    ];
}
