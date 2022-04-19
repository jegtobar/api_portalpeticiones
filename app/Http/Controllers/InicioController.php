<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class InicioController extends Controller {
    //Todas las consultas es sobre las tablas de mantenimientos de vecinos satisfechos y muy satisfechos.
    function getSatisfechos(){
        $metas = DB::table('metas_mnto_satisfechos')
        ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS responsable"),'users.avatar', 'colonias.colonia')
        ->join('colonias','colonias.id','=','metas_mnto_satisfechos.colonia_id')
        ->join('users','users.id','=','metas_mnto_satisfechos.responsable')
        ->where('metas_mnto_satisfechos.actual','>=','1')
        ->orderBy('metas_mnto_satisfechos.actual','desc')
        ->limit(5)
        ->get();
        return response()->json($metas);
    }
    function getMantenimientoSatisfechos(){
        $metas = DB::table('metas_mnto_satisfechos')
        ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS responsable"),'users.avatar', 'colonias.colonia')
        ->join('colonias','colonias.id','=','metas_mnto_satisfechos.colonia_id')
        ->join('users','users.id','=','metas_mnto_satisfechos.responsable')
        ->where('metas_mnto_satisfechos.actual','<','1')
        ->orderBy('metas_mnto_satisfechos.actual','asc')
        ->limit(5)
        ->get();

        return response()->json($metas);

    }

    function getMuySatisfechos(){
        $metas = DB::table('metas_mnto_muysatisfechos')
        ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS responsable"),'users.avatar', 'colonias.colonia')
        ->join('colonias','colonias.id','=','metas_mnto_muysatisfechos.colonia_id')
        ->join('users','users.id','=','metas_mnto_muysatisfechos.responsable')
        ->where('metas_mnto_muysatisfechos.actual','>=','1')
        ->orderBy('metas_mnto_muysatisfechos.actual','desc')
        ->limit(5)
        ->get();

        return response()->json($metas);

    }

    function getMntoMuySatisfechos(){
        $metas = DB::table('metas_mnto_muysatisfechos')
        ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS responsable"),'users.avatar', 'colonias.colonia')
        ->join('colonias','colonias.id','=','metas_mnto_muysatisfechos.colonia_id')
        ->join('users','users.id','=','metas_mnto_muysatisfechos.responsable')
        ->where('metas_mnto_muysatisfechos.actual','<','1')
        ->orderBy('metas_mnto_muysatisfechos.actual','asc')
        ->limit(5)
        ->get();

        return response()->json($metas);

    }
}