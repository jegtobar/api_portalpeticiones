<?php

namespace App\Services\Interfaces;

interface IGestionesServiceInterface{
    //Función para mostrar todos las gestiones
    function getGestiones();
  
    //Función para creación de gestion @param array $persona, @param int $id, @return void
    function postGestion(array $gestion);
    //Función para actualización de gestion @param int $id, @return boolean
    function putGestion(array $gestion, int $id);
    //Función para bloquear gestion @param int $id, @return boolean
    function deleteGestion(int $id);
    //Función para restaurar persona bloqueado @param int $id, @return boolean
    function restoreGestion(int $id);
    //Funcion para buscar gestiónes por dpi del vecino
    function getGestionbyDpi(int $id, int $alcaldia);
    //Funcion para seguimiento de gestiones según número de gestión
    function getGestionSeguimientobyId(int $id);

    
}
