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
        $query = "SELECT a.id, 
        SUM(a.meta)AS meta, 
        (SELECT COUNT(b.id) FROM personas b WHERE b.seguimiento = 4 AND b.deleted_at IS NULL AND b.colonia_id = a.colonia_id) AS actual, 
        (SUM(a.meta)- (SELECT COUNT(b.id) FROM personas b WHERE b.seguimiento = 4 AND b.deleted_at IS NULL AND b.colonia_id = a.colonia_id))AS por_alcanzar,
        c.colonia,
        (SELECT CONCAT(d.nombres,' ',d.apellidos) FROM users d INNER JOIN metas_mnto_satisfechos b ON b.responsable = d.id WHERE b.colonia_id = a.colonia_id)AS responsable,
        DATE_FORMAT(a.fecha,'%d/%m/%Y')AS fecha_meta,
        a.fecha
        FROM metas_mnto_satisfechos a
        INNER JOIN colonias c ON c.id = a.colonia_id
        WHERE a.alcaldia_id = $id
        GROUP BY c.colonia, a.id, a.fecha, a.colonia_id";
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
