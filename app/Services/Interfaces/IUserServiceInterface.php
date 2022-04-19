<?php

namespace App\Services\Interfaces;

interface IUserServiceInterface{
    //Función para mostrar todos los usuarios
    function getUser();
    //Función para mostrar usuario por id @param int $id, @return boolean
    function getUserById(int $id);

    //Función para mostrar usuarios del tipo promotor según la alcaldía seleccionada
    function getUserPromotor(int $alcaldia_id);
    //Función para creación de usuario @param array $user, @param int $id, @return void
    function postUser(array $user);
    //Función para actualización de usuario @param int $id, @return boolean
    function putUser(array $user, int $id);
    //Función para bloquear usuario @param int $id, @return boolean
    function delUser(int $id);
    //Función para restaurar usuario bloqueado @param int $id, @return boolean
    function restoreUser(int $id);
}
