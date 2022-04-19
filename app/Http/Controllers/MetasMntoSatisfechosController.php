<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Services\Implementation\metas\MetaMntoSatisfechosServiceImpl;
use Illuminate\Http\Request;

class MetasMntoSatisfechosController extends Controller{
    private $metaService;

    public function __construct(MetaMntoSatisfechosServiceImpl $metaService, Request $request)
    {
        $this->metaService = $metaService;
        $this->request = $request;
    }

    //Función para listar las metas
    function getMetasMntoSatisfechos(int $id){
        $metas=$this->metaService->getMetas($id);
        return response()->json(['metas'=>$metas, 'status'=>'Success']);
    }

    //Función para crear las metas
    function postMetaMntoSatisfecho(){
       return $this->metaService->postMeta($this->request->all());
    }

    //Función para actualizar una meta
    function putMetaMntoSatisfecho(int $id){
        return $this->metaService->putMeta($this->request->all(), $id);
    }

    //Función para eliminar una meta
    function deleteMetaMntoSatisfecho(int $id){
        $this->metaService->deleteMeta($id);
    }
}