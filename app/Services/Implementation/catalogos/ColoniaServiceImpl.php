<?php
namespace App\Services\Implementation\catalogos;
use App\Models\colonias;
use App\Services\Interfaces\catalogos\IColoniaServiceInterface;

class ColoniaServiceImpl implements IColoniaServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new colonias();
    }

    function getColonia(){
        return $this->model->get();
    }

    function getColoniaByDistritos(int $id)
    {
        $this->model->where('distrito_id',$id)
        ->first();
    }
    
    function getColoniaBySector(int $id)
    {
        $this->model->where('sector_id',$id)
        ->first();
    }

    function getColoniaByZona(int $id)
    {
        $this->model->where('zona_id',$id)
        ->first();
    }

}