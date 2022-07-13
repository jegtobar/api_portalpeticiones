<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class ListaPersonasController extends Controller
{

    public function listaVecinos(){
        $reportes = DB::table('personas')
        ->select('personas.id', DB::raw('(CASE 
        WHEN personas.seguimiento=1 THEN "Satisfecho"
        WHEN personas.seguimiento=2 THEN "Mantenimiento satisfecho"
        WHEN personas.seguimiento=3 THEN "Muy Satisfecho"
        ELSE "Mantenimiento muy satisfecho" END) AS seguimiento'), 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->whereNull('personas.deleted_at')
        ->get();
        $encabezado = [
            "Listado de Vecinos Todas las Alcaldias",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function listaVecinosAlcaldiaByDistrito(int $id, int $distrito){
        if($id==1){
            $alcaldia = 1;
        }else if($id==4){
            $alcaldia = 21;
        }
        $reportes = DB::table('personas')
        ->select('personas.id', DB::raw('(CASE 
        WHEN personas.seguimiento=1 THEN "Satisfecho"
        WHEN personas.seguimiento=2 THEN "Mantenimiento satisfecho"
        WHEN personas.seguimiento=3 THEN "Muy Satisfecho"
        ELSE "Mantenimiento muy satisfecho" END) AS seguimiento'), 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['colonias.zona_id','=',$id],
            ['colonias.distrito_id','=',$distrito],
            ['personas.deleted_at','=',null]
        ])
        ->get();
        $encabezado = [
            "Listado de Vecinos Alcaldía Z. $alcaldia distrito $distrito",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function listaVecinosAlcaldia(int $id){
        if($id==2){
            $alcaldia = 5;
        }else if($id==3){
            $alcaldia = 11;
        }
        $reportes = DB::table('personas')
        ->select('personas.id', DB::raw('(CASE 
        WHEN personas.seguimiento=1 THEN "Satisfecho"
        WHEN personas.seguimiento=2 THEN "Mantenimiento satisfecho"
        WHEN personas.seguimiento=3 THEN "Muy Satisfecho"
        ELSE "Mantenimiento muy satisfecho" END) AS seguimiento'), 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['colonias.zona_id','=',$id],
            ['personas.deleted_at','=',null]
        ])
        ->get();
        $encabezado = [
            "Listado de Vecinos Alcaldía Z. $alcaldia",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

}
