<?php
namespace App\Services\Implementation\catalogos;
use App\Models\sectores;
use App\Services\Interfaces\catalogos\ISectoresServiceInterface;


class SectoresServiceImpl implements ISectoresServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new sectores();
    }

    function getSectores(int $id){
        return $this->model->get();
    }

}