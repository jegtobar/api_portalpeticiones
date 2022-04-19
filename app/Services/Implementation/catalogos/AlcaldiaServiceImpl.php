<?php
namespace App\Services\Implementation\catalogos;
use App\Models\alcaldias;
use Illuminate\Support\Facades\DB;
use App\Services\Interfaces\catalogos\IAlcaldiasServiceInterface;


class AlcaldiaServiceImpl implements IAlcaldiasServiceInterface {

    private $model;

    function __construct()
    {
        $this->model = new alcaldias();
    }

    function getAlcaldias(){
            $alcaldias = DB::table('alcaldias')
            ->select('alcaldias.id', 'alcaldias.alcaldia','alcaldias.zona_id')
            ->limit(4)
            ->get();
        return $alcaldias;
    }

}

