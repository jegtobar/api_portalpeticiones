<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class roles extends Model{
    protected $fillable = [
        'rol',
        'descripcion',
    ];
}