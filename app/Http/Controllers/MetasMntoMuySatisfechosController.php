<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Services\Implementation\metas\MetaMntoMuySatisfechosServiceImpl;
use Illuminate\Http\Request;

class MetasMntoMuySatisfechosController extends Controller{
    private $metaService;

    public function __construct(MetaMntoMuySatisfechosServiceImpl $metaService, Request $request)
    {
        $this->metaService = $metaService;
        $this->request = $request;
    }

    //Función para listar las metas
    function getMetasMntoMuySatisfechos(int $id){
        $metas=$this->metaService->getMetas($id);
        return response()->json(['metas'=>$metas, 'status'=>'Success']);
    }

    //Función para crear las metas
    function postMetaMntoMuySatisfecho(){
       return $this->metaService->postMeta($this->request->all());
    }

    //Función para actualizar una meta
    function putMetaMntoMuySatisfecho(int $id){
        return $this->metaService->putMeta($this->request->all(), $id);
    }

    //Función para eliminar una meta
    function deleteMetaMntoMuySatisfecho(int $id){
        $this->metaService->deleteMeta($id);
    }
}