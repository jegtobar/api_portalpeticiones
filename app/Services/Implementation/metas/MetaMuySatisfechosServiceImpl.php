<?php

namespace App\Services\Implementation\metas;
use App\Models\metas_muysatisfechos;
use App\Services\Interfaces\metas\IMetasMuySatisfechosService;
use Illuminate\Support\Facades\DB;



class MetaMuySatisfechosServiceImpl implements IMetasMuySatisfechosService {
    private $model;


    function __construct()
    {
        $this->model = new metas_muysatisfechos();
    }

    //Función para listar las metas
    function getMetas(int $id){
        $metas=DB::table('metas_muysatisfechos')
        ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS responsable"),'metas_muysatisfechos.id',DB::raw('DATE_FORMAT(metas_muysatisfechos.fecha,"%d-%m-%Y")AS fecha'),'metas_muysatisfechos.fecha','metas_muysatisfechos.meta','metas_muysatisfechos.actual','metas_muysatisfechos.por_alcanzar','colonias.colonia')
        ->join('colonias','colonias.id','=','metas_muysatisfechos.colonia_id')
        ->join('users','users.id','=','metas_muysatisfechos.responsable')
        ->where('metas_muysatisfechos.alcaldia_id', $id)
        ->get();
        return $metas;




    }

    //Función para guardar una meta

    function postMeta(array $meta){
        $colonia_id = $meta['colonia_id'];
        $response = DB::table('metas_muysatisfechos')
        ->select('metas_muysatisfechos.colonia_id')
        ->where('colonia_id',$colonia_id)
        ->limit(1)
        ->first();
       //Valida que la meta no exista previo a guardar
        if (empty($response->colonia_id) ){
            $this->model->create($meta);
        }else
        if ($response->colonia_id=$response){
            return response()->json(['res'=>'Ya existe']);
        }

    }

    //Función para actualizar meta
    function putMeta(array $meta, int $id)
    {
        $this->model->where('id',$id)
        ->first()
        ->fill($meta)
        ->save();
        DB::select("CALL update_meta_muysatisfecho_meta()");


    }

    //Función para eliminar meta
    function deleteMeta(int $id)
    {
        $meta = $this->model->find($id);
        if($meta != null){
            $meta->delete();
        }
    }

}
