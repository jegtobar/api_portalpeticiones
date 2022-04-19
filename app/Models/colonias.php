<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class colonias extends Model{
    use SoftDeletes;
    protected $fillable = [
        'sector_id',
        'zona_id',
        'distrito_id',
        'colonia'
    ];
}