<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;


class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rol_id',
        'alcaldia_id',
        'distrito_id',
        'sector_id',
        'nombres',
        'apellidos',
        'username',
        'password',
        'api_token',
        'nit',
        'dpi',
        'avatar',
        'usuario_creador',
        'usuario_actualiza',
        'created_at',
        'updated_at'

    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'api_token',
        'created_at',
        'updated_at'

    ];

    // public function findForPassport($username) {
    //     return self::where('username', $username)->first(); // change column name whatever you use in credentials
    //  }
}
