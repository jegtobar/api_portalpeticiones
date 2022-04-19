<?php
namespace App\Services\Implementation\catalogos;
use App\Models\dependencias;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\catalogos\IDependenciasServiceInterface;


class DependenciasServiceImpl implements IDependenciasServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new dependencias();
    }

  
    function getDependencia(){
        $dependencia = DB::table('dependencias')
        ->select('id','dependencia')
        ->get();
        return $dependencia;
    }
}
