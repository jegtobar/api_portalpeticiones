<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class SeguimientoByAlcaldiaController extends Controller {
   
    public function reporteSatisfechosByDistrito(int $id, int $distrito){
        $reportes = DB::table('snto_vecinos_satisfechos')
        ->select('snto_vecinos_satisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(snto_vecinos_satisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'snto_vecinos_satisfechos.descripcion', 'snto_vecinos_satisfechos.responsable','colonias.colonia','personas.created_at')
        ->leftJoin('personas','personas.id','=','snto_vecinos_satisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','snto_vecinos_satisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['snto_vecinos_satisfechos.realizado','=','1'],
            ['colonias.zona_id','=',$id],
            ['colonias.distrito_id','=',$distrito]
        ])
        ->get();
        $encabezado = [
            "Vecinos Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }
    public function reporteSatisfechosByZona(int $id){
        $reportes = DB::table('snto_vecinos_satisfechos')
        ->select('snto_vecinos_satisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(snto_vecinos_satisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'snto_vecinos_satisfechos.descripcion', 'snto_vecinos_satisfechos.responsable','colonias.colonia','personas.created_at')
        ->leftJoin('personas','personas.id','=','snto_vecinos_satisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','snto_vecinos_satisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['snto_vecinos_satisfechos.realizado','=','1'],
            ['colonias.zona_id','=',$id]
        ])
        ->get();
        $encabezado = [
            "Vecinos Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function reporteMuySatisfechosByDistrito(int $id, int $distrito){
        $reportes = DB::table('snto_vecinos_muysatisfechos')
        ->select('snto_vecinos_muysatisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(snto_vecinos_muysatisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'snto_vecinos_muysatisfechos.descripcion', 'snto_vecinos_muysatisfechos.responsable','colonias.colonia','personas.created_at')
        ->leftJoin('personas','personas.id','=','snto_vecinos_muysatisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','snto_vecinos_muysatisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['snto_vecinos_muysatisfechos.realizado','=','1'],
            ['colonias.zona_id','=',$id],
            ['colonias.distrito_id','=',$distrito]
        ])
        ->get();
        $encabezado = [
            "Vecinos Muy Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function reporteMuySatisfechosByZona(int $id){
        $reportes = DB::table('snto_vecinos_muysatisfechos')
        ->select('snto_vecinos_muysatisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(snto_vecinos_muysatisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'snto_vecinos_muysatisfechos.descripcion', 'snto_vecinos_muysatisfechos.responsable','colonias.colonia','personas.created_at')
        ->leftJoin('personas','personas.id','=','snto_vecinos_muysatisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','snto_vecinos_muysatisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['snto_vecinos_muysatisfechos.realizado','=','1'],
            ['colonias.zona_id','=',$id]
        ])
        ->get();
        $encabezado = [
            "Vecinos Muy Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function reporteMntoSatisfechosByDistrito(int $id, int $distrito){
        $reportes = DB::table('mnto_vecinos_satisfechos')
        ->select('mnto_vecinos_satisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(mnto_vecinos_satisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'mnto_vecinos_satisfechos.descripcion', 'mnto_vecinos_satisfechos.responsable','colonias.colonia','personas.created_at')
        ->leftJoin('personas','personas.id','=','mnto_vecinos_satisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','mnto_vecinos_satisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['mnto_vecinos_satisfechos.realizado','=','1'],
            ['colonias.zona_id','=',$id],
            ['colonias.distrito_id','=',$distrito]
        ])
        ->get();
        $encabezado = [
            "Mantenimiento Vecinos Satisfechos",
        ];
        return response()->json(['reportes' => $reportes,'titulo' => $encabezado]);
    }

    public function reporteMntoSatisfechosByZona(int $id){
        $reportes = DB::table('mnto_vecinos_satisfechos')
        ->select('mnto_vecinos_satisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(mnto_vecinos_satisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'mnto_vecinos_satisfechos.descripcion', 'mnto_vecinos_satisfechos.responsable','colonias.colonia','personas.created_at')
        ->leftJoin('personas','personas.id','=','mnto_vecinos_satisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','mnto_vecinos_satisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['mnto_vecinos_satisfechos.realizado','=','1'],
            ['colonias.zona_id','=',$id]
        ])
        ->get();
        $encabezado = [
            "Mantenimiento Vecinos Satisfechos",
        ];
        return response()->json(['reportes' => $reportes,'titulo' => $encabezado]);
    }

    public function reporteMntoMuySatisfechosByDistrito(int $id, int $distrito){
        $reportes = DB::table('mnto_vecinos_muysatisfechos')
        ->select('mnto_vecinos_muysatisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(mnto_vecinos_muysatisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'mnto_vecinos_muysatisfechos.descripcion', 'mnto_vecinos_muysatisfechos.responsable','colonias.colonia','personas.created_at')
        ->leftJoin('personas','personas.id','=','mnto_vecinos_muysatisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','mnto_vecinos_muysatisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['mnto_vecinos_muysatisfechos.realizado','=','1'],
            ['colonias.zona_id','=',$id],
            ['colonias.distrito_id','=',$distrito]
        ])
        ->get();
        $encabezado = [
            "Mantenimiento Vecinos Muy Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function reporteMntoMuySatisfechosByZona(int $id){
        $reportes = DB::table('mnto_vecinos_muysatisfechos')
        ->select('mnto_vecinos_muysatisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(mnto_vecinos_muysatisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'mnto_vecinos_muysatisfechos.descripcion', 'mnto_vecinos_muysatisfechos.responsable','colonias.colonia','personas.created_at')
        ->leftJoin('personas','personas.id','=','mnto_vecinos_muysatisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','mnto_vecinos_muysatisfechos.actividad_id')
        ->leftJoin('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['mnto_vecinos_muysatisfechos.realizado','=','1'],
            ['colonias.zona_id','=',$id]
        ])
        ->get();
        $encabezado = [
            "Mantenimiento Vecinos Muy Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }
}