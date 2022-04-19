<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class zonas extends Model{
    protected $fillable = [
        'id',
        'zona',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
