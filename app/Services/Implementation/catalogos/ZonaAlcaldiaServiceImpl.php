<?php
namespace App\Services\Implementation\catalogos;
use App\Models\zonas;
use App\Services\Interfaces\catalogos\IZonasAlcaldiaServiceInterface;


class ZonaAlcaldiaServiceImpl implements IZonasAlcaldiaServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new zonas();
    }

    function getZonabyAlcaldia(int $id){
        return $this->model->get();
    }

}
