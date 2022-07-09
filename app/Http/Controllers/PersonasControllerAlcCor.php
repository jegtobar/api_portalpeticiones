<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class PersonasControllerAlcCor extends Controller
{
      //Función para mostrar todos las personas (valido para promotores distritos zona 1 y 21)
      function getPersonasByZonaDistrito( int $seguimiento,int $alcaldia, int $distrito, string $vecino){
        $persona = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=',$seguimiento],
            ['personas.zona_id','=',$alcaldia],
            ['colonias.distrito_id','=',$distrito],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.pApellido)"),'LIKE',"%".$vecino."%"],
            ['personas.deleted_at','=',null]
        ])
        ->orWhere([
            ['personas.seguimiento','=',$seguimiento],
            ['personas.zona_id','=',$alcaldia],
            ['colonias.distrito_id','=',$distrito],
            [DB::raw("CONCAT(personas.sNombre,' ',personas.pApellido)"),'LIKE',"%".$vecino."%"],
            ['personas.deleted_at','=',null]
        ])
        ->orWhere([
            ['personas.seguimiento','=',$seguimiento],
            ['personas.zona_id','=',$alcaldia],
            ['colonias.distrito_id','=',$distrito],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"],
            ['personas.deleted_at','=',null]
        ])
        ->orWhere([
            ['personas.seguimiento','=',$seguimiento],
            ['personas.zona_id','=',$alcaldia],
            ['colonias.distrito_id','=',$distrito],
            [DB::raw("CONCAT(personas.sNombre,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"],
            ['personas.deleted_at','=',null]
        ])
        ->orWhere([
            ['personas.seguimiento','=',$seguimiento],
            ['personas.zona_id','=',$alcaldia],
            ['colonias.distrito_id','=',$distrito],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.sNombre)"),'LIKE',"%".$vecino."%"],
            ['personas.deleted_at','=',null]
        ])
        ->orWhere([
            ['personas.seguimiento','=',$seguimiento],
            ['personas.zona_id','=',$alcaldia],
            ['colonias.distrito_id','=',$distrito],
            [DB::raw("CONCAT(personas.pApellido,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"],
            ['personas.deleted_at','=',null]
        ])
        ->get();
        return response()->json(['persona'=>$persona]);
    }

         //Función para mostrar todos las personas (valido para promotores distritos zona 5 y 11)
         function getPersonasByZona( int $seguimiento,int $alcaldia, string $vecino){
            $persona = DB::table('personas')
            ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
            ->join('zonas','zonas.id','=','personas.zona_id')
            ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
            ->join('colonias','colonias.id','=','personas.colonia_id')
            ->where([
                ['personas.seguimiento','=',$seguimiento],
                ['personas.zona_id','=',$alcaldia],
                [DB::raw("CONCAT(personas.pNombre,' ',personas.pApellido)"),'LIKE',"%".$vecino."%"],
                ['personas.deleted_at','=',null]
            ])
            ->orWhere([
                ['personas.seguimiento','=',$seguimiento],
                ['personas.zona_id','=',$alcaldia],
                [DB::raw("CONCAT(personas.sNombre,' ',personas.pApellido)"),'LIKE',"%".$vecino."%"],
                ['personas.deleted_at','=',null]
            ])
            ->orWhere([
                ['personas.seguimiento','=',$seguimiento],
                ['personas.zona_id','=',$alcaldia],
                [DB::raw("CONCAT(personas.pNombre,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"],
                ['personas.deleted_at','=',null]
            ])
            ->orWhere([
                ['personas.seguimiento','=',$seguimiento],
                ['personas.zona_id','=',$alcaldia],
                [DB::raw("CONCAT(personas.sNombre,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"],
                ['personas.deleted_at','=',null]
            ])
            ->orWhere([
                ['personas.seguimiento','=',$seguimiento],
                ['personas.zona_id','=',$alcaldia],
                [DB::raw("CONCAT(personas.pNombre,' ',personas.sNombre)"),'LIKE',"%".$vecino."%"],
                ['personas.deleted_at','=',null]
            ])
            ->orWhere([
                ['personas.seguimiento','=',$seguimiento],
                ['personas.zona_id','=',$alcaldia],
                [DB::raw("CONCAT(personas.pApellido,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"],
                ['personas.deleted_at','=',null]
            ])
            ->get();
            return response()->json(['persona'=>$persona]);
        }
    


}
