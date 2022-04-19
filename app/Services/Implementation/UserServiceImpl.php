<?php

namespace App\Services\Implementation;

use App\Services\Interfaces\IUserServiceInterface;
use App\Models\user;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserServiceImpl implements IUserServiceInterface{
    private $model;

    function __construct()
    {
        $this->model = new user();
    }

    //Funcion para mostrar todos los usuarios activos
    function getUser(){
        return $this->model->get();
    }

    //Funcion para mostrar todos los usuarios (activos-inactivos)
    function getAllUser(){
        return $this->model->withTrashed()->get();
    }

    //Funcion para mostrar usuario por id @param int $id, @return boolean
    function getUserById(int $id){
        return  $this->model->withTrashed()->find($id);
    }

    function getUserEditById(int $id){
        $user = DB::table('users')
        ->select('users.nombres','users.apellidos')
        ->where ('users.id','=',$id)
        ->first();
        return response()->json([
            'nombres'=>$user->nombres,
            'apellidos'=>$user->apellidos,
            'password'=>""
        ]);

    }

    //Funcion para mostrar usuarios del tipo promotor según alcaldía id

    function getUserPromotor(int $id){
        $promotor = DB::table('users')
        ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS promotor, id"))
        ->where([
            ['alcaldia_id','=' ,$id],
            ['rol_id','=' ,'2']
            ])
        ->get();
        return $promotor;
    }

    function getUserPromotorCoordinador(int $id){
        $promotor = DB::table('users')
        ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS promotor, id"))
        ->where([
            ['alcaldia_id','=' ,$id],
            ['rol_id','=' ,'2'],
            ])
        ->orWhere([
            ['alcaldia_id','=' ,$id],
            ['rol_id','=' ,'3'],
            ])
        ->get();
        return $promotor;
    }
    //Funcion para creacion de usuario @param array $user, @param int $id, @return void
    function postUser(array $user){
        $user['password']=Hash::make($user['password']);
        $this->model->create($user);
    }
    //Funcion para actualizacion de usuario @param int $id, @return boolean
    function putUser(array $user, int $id){
        if($user['password']){
            $user['password']=Hash::make($user['password']);
        }
        $this->model->where('id',$id)
        ->first()
        ->fill($user)
        ->save();
        DB::select("CALL bitacora_update_users()");
    }

    //Funcion para bloquear usuario @param int $id, @return boolean
    function delUser(int $id){
       $user = $this->model->find($id);
        if($user != null){
            $user->delete();
        }
    }
    //Funcion para restaurar usuario bloqueado @param int $id, @return boolean
    function restoreUser(int $id){
        $user = $this->model->withTrashed()->find($id);
        if($user != null){
            $user->restore();
        }
    }
}
