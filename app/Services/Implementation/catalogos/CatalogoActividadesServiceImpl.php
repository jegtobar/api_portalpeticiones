<?php
namespace App\Services\Implementation\catalogos;
use App\Models\cat_actividades;
use App\Services\Interfaces\catalogos\ICatalogoActividadesServiceInterface;


class CatalogoActividadesServiceImpl implements ICatalogoActividadesServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new cat_actividades();
    }

    function getCatActividades(){
        return $this->model->get();
    }

}
