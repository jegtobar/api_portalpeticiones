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
        $query = "SELECT COUNT(a.id)AS actual, (SELECT SUM(b.meta)FROM metas_mnto_muysatisfechos b WHERE b.colonia_id = a.colonia_id)AS meta, 
        (((SELECT SUM(b.meta)FROM metas_mnto_muysatisfechos b WHERE b.colonia_id = a.colonia_id))-(SELECT COUNT(a.id)AS actual))AS por_alcanzar, c.colonia,
        (SELECT CONCAT(d.nombres,' ',d.apellidos) FROM users d INNER JOIN  metas_mnto_muysatisfechos b ON b.responsable = d.id WHERE b.colonia_id = a.colonia_id)AS responsable,
        (SELECT DATE_FORMAT(b.fecha,'%d/%m/%Y')FROM metas_mnto_muysatisfechos b WHERE b.colonia_id = a.colonia_id)AS fecha_meta, (SELECT b.fecha FROM metas_mnto_muysatisfechos b WHERE b.colonia_id = a.colonia_id)AS fecha , (SELECT b.id FROM metas_mnto_muysatisfechos b WHERE b.colonia_id = a.colonia_id)AS id
        FROM personas a
        INNER JOIN colonias c ON c.id = a.colonia_id
        WHERE a.seguimiento = 4 AND a.deleted_at IS NULL AND a.zona_id = $id
        GROUP BY a.colonia_id, c.colonia";
        $metas=DB::select($query);
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
