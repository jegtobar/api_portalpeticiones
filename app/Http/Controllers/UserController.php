<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Services\Implementation\UserServiceImpl;
use Illuminate\Http\Request;
use App\Validator\UserValidator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserController extends Controller
{
    private $userService;
    private $request;

    //validador de usuario
    private $validator;

    public function __construct(UserServiceImpl $userService, Request $request, UserValidator $userValidator){
        $this->userService = $userService;
        $this->request = $request;
        $this->validator = $userValidator;
    }

        //Creacion de usuarios
    function createUser(){

        return $this->userService->postUser($this->request->all());

    }
    //Lista de usuarios activos
    function getListUser(){
        // $users=$this->userService->getUser();
        // return response()->json(['usuarios'=>$users, 'status'=>'Success']);
        $users=DB::table('users')
        ->select('users.id','users.nombres','users.apellidos','users.username','users.rol_id','users.alcaldia_id','users.nit','users.dpi','roles.rol','alcaldias.alcaldia','sectores.sector','distritos.distrito','users.usuario_actualiza')
        ->join('roles', 'roles.id','=','users.rol_id')
        ->leftjoin('sectores','sectores.id','=','users.sector_id')
        ->leftjoin('distritos','distritos.id','=','users.distrito_id')
        ->join('alcaldias', 'alcaldias.id','=','users.alcaldia_id')
        ->whereNull('users.deleted_at')
        ->get();
        return response()->json(['usuarios'=>$users, 'status'=>'Success']);
    }
    //lista de todos los usuarios (activos-inactivos)
    function getListAllUser(){
        $allusers = $this->userService->getAllUser();
        return response()->json(['listaUsuarios'=>$allusers, 'status'=>'Success']);
    }

    //usuario por id
    function getUserById(int $id){
        $user=$this->userService->getUserById($id);
        return response()->json(['user'=>$user, 'status'=>'Success']);
    }

    //Usuarios promotores
    function getUserPromotor(int $id){
        $user=$this->userService->getUserPromotor($id);
        return response()->json(['user'=>$user, 'status'=>'Success']);
    }

    //Usuarios promotores y coordinadores
    function getUserPromotorCoordinador(int $id){
        $user=$this->userService->getUserPromotorCoordinador($id);
        return response()->json(['user'=>$user, 'status'=>'Success']);
    }

    //actualizar usuario
    function putUser(int $id){
        $response = response("",202);
        $this->userService->putUser($this->request->all(), $id);
        return $response;
    }
    //Función para ubicar al usuario vista editar perfil
    function getUserEditById(int $id){
        $user = DB::table('users')
        ->select('users.id','users.nombres','users.apellidos','users.avatar')
        ->where ('users.id','=',$id)
        ->first();
        return response()->json([
            'id'=>$user->id,
            'nombres'=>$user->nombres,
            'apellidos'=>$user->apellidos,
            'password'=>"",
            'avatar'=>$user->avatar
        ]);

    }

    function postUserUpdate(Request $request){
        
        $data = json_decode($request->data);
        $user = User::find($data->id);
        $destinationPath="avatar";
        if(!empty($request->file('file'))){
            $fileName = uniqid().'_'.$request->file('file')->getClientOriginalName();
            $request->file('file')->move($destinationPath, $fileName);
            $url = "https://$_SERVER[HTTP_HOST]".'/apis/api-portalpeticiones/public/avatar/'.$fileName;
            // $url = "http://$_SERVER[HTTP_HOST]".'/PortalPeticiones/api/public/avatar/'.$fileName;
            $user->avatar = $url;
        }
        $user->nombres = $data->nombres;
        $user->apellidos = $data->apellidos;
        if($data->password!==""){
            $user->password = Hash::make($data->password);
        }
        
        $user->save(); 
    
    }
    //Eliminar(deshabilitar) usuario
    function deleteUser(int $id){
        $this->userService->delUser($id);
        return response("",204);
    }
    //Restaurar usuario
    function restoreUser(int $id){
        $this->userService->restoreUser($id);
        return response("",204);
    }

    //Funcion Login
    public function login(Request $request){
        $user = DB::table('users')
        ->select('users.id','users.rol_id','users.alcaldia_id','users.distrito_id','users.nombres','users.apellidos','users.username','users.password','users.avatar','alcaldias.alcaldia','roles.rol')
        ->join('alcaldias','alcaldias.id','=','users.alcaldia_id')
        ->join('roles','roles.id','=','users.rol_id')
        ->whereUsername($request->username)
        ->whereNull('users.deleted_at')
        ->first();
        if(!is_null($user) && Hash::check($request->password, $user->password)){
            
            return response()->json([
                'res'=>true,
                'username'=>$user->username,
                'rol_id'=>$user->rol_id,
                'alcaldia_id'=>$user->alcaldia_id,
                'distrito_id'=>$user->distrito_id,
                'nombres'=>$user->nombres,
                'apellidos'=>$user->apellidos,
                'user_id'=>$user->id,
                'avatar'=>$user->avatar,
                'alcaldia'=>$user->alcaldia,
                'rol'=>$user->rol
            ]);

        }else{
            return response()->json([
                'res'=>false,
                'message'=>'Usuario o contraseña incorrecto'

            ]);
        }
    }

    //Funcion Logout
    public function logout(){
        $user = auth()->user();
        $user->api_token=null;
        $user->save();
        return response()->json([
            'res'=>true,
            'message'=>'Hasta prónto :)'

        ]);
    }
}
