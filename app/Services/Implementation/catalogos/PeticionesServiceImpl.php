<?php
namespace App\Services\Implementation\catalogos;
use App\Models\tipo_peticiones;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\catalogos\ITipoPeticionesInterface;


class PeticionesServiceImpl implements ITipoPeticionesInterface {

    private $model;

    function __construct()
    {
        $this->model = new tipo_peticiones();
    }

    function getPeticiones(){
        $peticion = DB::table('tipo_peticiones')
        ->select('id','peticion')
        ->get();
        return $peticion;
    }

}