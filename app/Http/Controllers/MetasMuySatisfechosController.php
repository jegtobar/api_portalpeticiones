<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Services\Implementation\metas\MetaMuySatisfechosServiceImpl;
use Illuminate\Http\Request;

class MetasMuySatisfechosController extends Controller{
    private $metaService;

    public function __construct(MetaMuySatisfechosServiceImpl $metaService, Request $request)
    {
        $this->metaService = $metaService;
        $this->request = $request;
    }

    //Función para listar las metas
    function getMetasMuySatisfechos(int $id){
        $metas=$this->metaService->getMetas($id);
        return response()->json(['metas'=>$metas, 'status'=>'Success']);
    }

    //Función para crear las metas
    function postMetaMuySatisfecho(){
       return $this->metaService->postMeta($this->request->all());
    }

    //Función para actualizar una meta
    function putMetaMuySatisfecho(int $id){
        return $this->metaService->putMeta($this->request->all(), $id);
    }

    //Función para eliminar una meta
    function deleteMetaMuySatisfecho(int $id){
        $this->metaService->deleteMeta($id);
    }
}