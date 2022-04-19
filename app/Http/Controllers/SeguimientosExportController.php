<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class SeguimientosExportController extends Controller {
   
    public function reporteSatisfechos(){
        $reportes = DB::table('snto_vecinos_satisfechos')
        ->select('snto_vecinos_satisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(snto_vecinos_satisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'snto_vecinos_satisfechos.descripcion', 'snto_vecinos_satisfechos.responsable','colonias.colonia','zonas.zona', 'distritos.distrito','sectores.sector','personas.created_at')
        ->leftJoin('personas','personas.id','=','snto_vecinos_satisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','snto_vecinos_satisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->leftJoin('distritos','distritos.id','=','colonias.distrito_id')
        ->leftJoin('sectores','sectores.id','=','colonias.sector_id')
        ->leftJoin('zonas','zonas.id','=','personas.zona_id')
        ->where([
            ['snto_vecinos_satisfechos.realizado','=','1'],
        ])
        ->get();
        $encabezado = [
            "Vecinos Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function reporteMuySatisfechos(){
        $reportes = DB::table('snto_vecinos_muysatisfechos')
        ->select('snto_vecinos_muysatisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(snto_vecinos_muysatisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'snto_vecinos_muysatisfechos.descripcion', 'snto_vecinos_muysatisfechos.responsable','colonias.colonia','zonas.zona','distritos.distrito','sectores.sector','personas.created_at')
        ->leftJoin('personas','personas.id','=','snto_vecinos_muysatisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','snto_vecinos_muysatisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->leftJoin('distritos','distritos.id','=','colonias.distrito_id')
        ->leftJoin('sectores','sectores.id','=','colonias.sector_id')
        ->leftJoin('zonas','zonas.id','=','personas.zona_id')
        ->where([
            ['snto_vecinos_muysatisfechos.realizado','=','1'],
        ])
        ->get();
        $encabezado = [
            "Vecinos Muy Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function reporteMntoSatisfechos(){
        $reportes = DB::table('mnto_vecinos_satisfechos')
        ->select('mnto_vecinos_satisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(mnto_vecinos_satisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'mnto_vecinos_satisfechos.descripcion', 'mnto_vecinos_satisfechos.responsable','colonias.colonia','zonas.zona','distritos.distrito','sectores.sector','personas.created_at')
        ->leftJoin('personas','personas.id','=','mnto_vecinos_satisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','mnto_vecinos_satisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->leftJoin('distritos','distritos.id','=','colonias.distrito_id')
        ->leftJoin('sectores','sectores.id','=','colonias.sector_id')
        ->leftJoin('zonas','zonas.id','=','personas.zona_id')
        ->where([
            ['mnto_vecinos_satisfechos.realizado','=','1'],
        ])
        ->get();
        $encabezado = [
            "Mantenimiento Vecinos Satisfechos",
        ];
        return response()->json(['reportes' => $reportes,'titulo' => $encabezado]);
    }

    public function reporteMntoMuySatisfechos(){
        $reportes = DB::table('mnto_vecinos_muysatisfechos')
        ->select('mnto_vecinos_muysatisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(mnto_vecinos_muysatisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'mnto_vecinos_muysatisfechos.descripcion', 'mnto_vecinos_muysatisfechos.responsable','colonias.colonia','zonas.zona','distritos.distrito','sectores.sector','personas.created_at')
        ->leftJoin('personas','personas.id','=','mnto_vecinos_muysatisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','mnto_vecinos_muysatisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->leftJoin('distritos','distritos.id','=','colonias.distrito_id')
        ->leftJoin('sectores','sectores.id','=','colonias.sector_id')
        ->leftJoin('zonas','zonas.id','=','personas.zona_id')
        ->where([
            ['mnto_vecinos_muysatisfechos.realizado','=','1'],
        ])
        ->get();
        $encabezado = [
            "Mantenimiento Vecinos Muy Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }
}