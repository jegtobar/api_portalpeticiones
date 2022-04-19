<?php
namespace App\Services\Implementation\catalogos;
use App\Models\zonas_vecinos;
use App\Services\Interfaces\catalogos\IZonasServiceInterface;


class ZonasServiceImpl implements IZonasServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new zonas_vecinos();
    }

    function getZonas(){
        return $this->model->get();
    }

}
