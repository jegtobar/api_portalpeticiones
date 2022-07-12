<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Expression;

class SeguimientosController extends Controller
{
    //Mostrar seguimientos  de vecinos segun id
    function getSeguimientosById(int $id){
        $query = "SELECT a.id, a.persona_id, DATE_FORMAT(a.fecha,'%d/%m/%Y')AS date, a.fecha, a.descripcion, a.responsable, a.realizado, c.actividad, 1 AS mantenimiento FROM snto_vecinos_satisfechos a
        INNER JOIN cat_actividades c ON a.actividad_id = c.id
        WHERE a.persona_id = $id
        UNION
        SELECT b.id, b.persona_id, DATE_FORMAT(b.fecha,'%d/%m/%Y')AS date, b.fecha, b.responsable, b.descripcion, b.realizado, c.actividad, 2 AS mantenimiento FROM mnto_vecinos_satisfechos b
        INNER JOIN cat_actividades c ON b.actividad_id = c.id
        WHERE b.persona_id = $id
        UNION
        SELECT a.id, a.persona_id, DATE_FORMAT(a.fecha,'%d/%m/%Y')AS date, a.fecha, a.responsable, a.descripcion, a.realizado, c.actividad, 3 AS mantenimiento FROM snto_vecinos_muysatisfechos a
        INNER JOIN cat_actividades c ON a.actividad_id = c.id
        WHERE a.persona_id = $id
        UNION
        SELECT b.id, b.persona_id, DATE_FORMAT(b.fecha,'%d/%m/%Y')AS date, b.fecha, b.responsable, b.descripcion,  b.realizado, c.actividad, 4 AS mantenimiento FROM mnto_vecinos_muysatisfechos b
        INNER JOIN cat_actividades c ON b.actividad_id = c.id
        WHERE b.persona_id = $id";
        $response = DB::select($query);
        return response()->json($response);
       }
}