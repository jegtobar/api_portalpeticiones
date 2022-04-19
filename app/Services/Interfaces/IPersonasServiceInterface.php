<?php

namespace App\Services\Interfaces;

interface IPersonasServiceInterface{
    //Función para mostrar todos las personas
    function getPersonas();
    //Función para mostrar persona por id @param int $id, @return boolean
    function getPersonaById(int $id);
    //Función para creación de persona @param array $persona, @param int $id, @return void
    function postPersona(array $persona);
    //Función para actualización de persona @param int $id, @return boolean
    function putPersona(array $persona, int $id);
    //Función para bloquear persona @param int $id, @return boolean
    function delPersona(int $id);
    //Función para restaurar persona bloqueado @param int $id, @return boolean
    function restorePersona(int $id);
}