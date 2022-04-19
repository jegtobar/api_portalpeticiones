<?php

namespace App\Services\Implementation\metas;
use App\Models\metas_mnto_muysatisfechos;
use App\Services\Interfaces\metas\IMetasMntoMuySatisfechosService;
use Illuminate\Support\Facades\DB;



class MetaMntoMuySatisfechosServiceImpl implements IMetasMntoMuySatisfechosService {
    private $model;


    function __construct()
    {
        $this->model = new metas_mnto_muysatisfechos();
    }

    //Función para listar las metas
    function getMetas(int $id){
        $metas=DB::table('metas_mnto_muysatisfechos')
        ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS responsable"),'metas_mnto_muysatisfechos.id',DB::raw('DATE_FORMAT(metas_mnto_muysatisfechos.fecha,"%d-%m-%Y")AS fecha'),'metas_mnto_muysatisfechos.fecha','metas_mnto_muysatisfechos.meta','metas_mnto_muysatisfechos.actual','metas_mnto_muysatisfechos.por_alcanzar','colonias.colonia')
        ->join('colonias','colonias.id','=','metas_mnto_muysatisfechos.colonia_id')
        ->join('users','users.id','=','metas_mnto_muysatisfechos.responsable')
        ->where('metas_mnto_muysatisfechos.alcaldia_id', $id)
        ->get();
        return $metas;



    }

    //Función para guardar una meta

    function postMeta(array $meta){
        $colonia_id = $meta['colonia_id'];
        $response = DB::table('metas_mnto_muysatisfechos')
        ->select('metas_mnto_muysatisfechos.colonia_id')
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
        DB::select("CALL update_meta_mnto_muysatisfecho_meta()");


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
