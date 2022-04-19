<?php
namespace App\Services\Implementation\catalogos;
use App\Models\distritos;
use App\Services\Interfaces\catalogos\IDistritosServiceInterface;


class DistritoServiceImpl implements IDistritosServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new distritos();
    }

    function getDistritos(int $id){
        return $this->model->get();
    }

}