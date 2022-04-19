<?php
namespace App\Services\Implementation\catalogos;
use App\Models\roles;
use App\Services\Interfaces\catalogos\IRolesServiceInterface;


class RolesServiceImpl implements IRolesServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new roles();
    }

    function getRoles(){
        return $this->model->get();
    }

}
