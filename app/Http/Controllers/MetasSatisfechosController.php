<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Services\Implementation\metas\MetaSatisfechosServiceImpl;
use Illuminate\Http\Request;

class MetasSatisfechosController extends Controller{
    private $metaService;

    public function __construct(MetaSatisfechosServiceImpl $metaService, Request $request)
    {
        $this->metaService = $metaService;
        $this->request = $request;
    }

    //Función para listar las metas
    function getMetasSatisfechos(int $id){
        $metas=$this->metaService->getMetas($id);
        return response()->json($metas);
    }

    //Función para crear las metas
    function postMetaSatisfecho(){
       return $this->metaService->postMeta($this->request->all());
    }

    //Función para actualizar una meta
    function putMetaSatisfecho(int $id){
        return $this->metaService->putMeta($this->request->all(), $id);
    }

    //Función para eliminar una meta
    function deleteMetaSatisfecho(int $id){
        $this->metaService->deleteMeta($id);
    }
}