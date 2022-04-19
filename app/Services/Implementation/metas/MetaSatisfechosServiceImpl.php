<?php

namespace App\Services\Implementation\metas;
use App\Models\metas_satisfechos;
use App\Services\Interfaces\metas\IMetasSatisfechosService;
use Illuminate\Support\Facades\DB;



class MetaSatisfechosServiceImpl implements IMetasSatisfechosService {
    private $model;


    function __construct()
    {
        $this->model = new metas_satisfechos();
    }

    //Función para listar las metas
    function getMetas(int $id){
        $metas=DB::table('metas_satisfechos')
        ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS responsable"),'metas_satisfechos.id',DB::raw('DATE_FORMAT(metas_satisfechos.fecha,"%d-%m-%Y")AS f'),'metas_satisfechos.fecha','metas_satisfechos.meta','metas_satisfechos.actual','metas_satisfechos.por_alcanzar','colonias.colonia')
        ->join('colonias','colonias.id','=','metas_satisfechos.colonia_id')
        ->join('users','users.id','=','metas_satisfechos.responsable')
        ->where('metas_satisfechos.alcaldia_id', $id)
        ->get();
        return $metas;
    }

    //Función para guardar una meta

    function postMeta(array $meta){
        $colonia_id = $meta['colonia_id'];
        $response = DB::table('metas_satisfechos')
        ->select('metas_satisfechos.colonia_id')
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
        DB::select("CALL update_meta_satisfecho_meta()");


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
