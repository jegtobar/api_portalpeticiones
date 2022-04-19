<?php

namespace App\Services\Interfaces\metas;

interface IMetasSatisfechosService{
    //Función para mostrar todas las metas
    function getMetas(int $id);
  
    //Función ingresar una meta
    function postMeta(array $meta);

    //Función para actualizar la información de una meta
    function putMeta(array $meta, int $id);

    //Función para eliminar una meta
    function deleteMeta(int $id);

   
    
}