<?php

namespace App\Services\Interfaces\catalogos;

interface IColoniaServiceInterface{
    //Función para mostrar todas las colonias
    function getColonia();
    //Función para mostrar colonias por sector
    function getColoniaBySector(int $id);
    //Función para mostrar colonias por zonas
    function getColoniaByZona(int $id);
    //Función para mostrar colonias por distritos
    function getColoniaByDistritos(int $id);
 
}