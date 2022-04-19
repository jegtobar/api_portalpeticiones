<?php
namespace App\Services\Implementation\catalogos;
use App\Models\canales;
use App\Services\Interfaces\catalogos\ICatalogoCanalServiceInterface;


class CatalogoCanalServiceImpl implements ICatalogoCanalServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new canales();
    }

    function getCanal(){
        return $this->model->get('canal');
    }

}