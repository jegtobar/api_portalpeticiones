<?php
namespace App\Services\Implementation\catalogos;
use App\Models\liderazgo;
use App\Services\Interfaces\catalogos\ILiderazgoServiceInterface;


class LiderazgoServiceImpl implements ILiderazgoServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new liderazgo();
    }

    function getLiderazgo(){
        return $this->model->get();
    }

}
