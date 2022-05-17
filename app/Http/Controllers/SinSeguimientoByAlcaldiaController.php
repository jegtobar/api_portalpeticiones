<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class SinSeguimientoByAlcaldiaController extends Controller {
   
    public function reporteSatisfechosByDistrito(int $id, int $distrito){
        $reportes = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','1'],
            ['colonias.zona_id','=',$id],
            ['colonias.distrito_id','=',$distrito]
        ])
        ->whereNotIn('personas.id', function($query){
            $query->select('persona_id')
            ->from('snto_vecinos_satisfechos')->get();
        })
        ->get();
        $encabezado = [
            "Vecinos Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }
    public function reporteSatisfechosByZona(int $id){
        $reportes = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','1'],
            ['colonias.zona_id','=',$id]
        ])
        ->whereNotIn('personas.id', function($query){
            $query->select('persona_id')
            ->from('snto_vecinos_satisfechos')->get();
        })
        ->get();
        $encabezado = [
            "Vecinos Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function reporteMuySatisfechosByDistrito(int $id, int $distrito){
        $reportes = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','3'],
            ['colonias.zona_id','=',$id],
            ['colonias.distrito_id','=',$distrito]
        ])
        ->whereNotIn('personas.id', function($query){
            $query->select('persona_id')
            ->from('snto_vecinos_muysatisfechos')->get();
        })
        ->get();
        $encabezado = [
            "Vecinos Muy Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function reporteMuySatisfechosByZona(int $id){
        $reportes = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','3'],
            ['colonias.zona_id','=',$id]
        ])
        ->whereNotIn('personas.id', function($query){
            $query->select('persona_id')
            ->from('snto_vecinos_muysatisfechos')->get();
        })
        ->get();
        $encabezado = [
            "Vecinos Muy Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function reporteMntoSatisfechosByDistrito(int $id, int $distrito){
        $reportes = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','2'],
            ['colonias.zona_id','=',$id],
            ['colonias.distrito_id','=',$distrito]
        ])
        ->whereNotIn('personas.id', function($query){
            $query->select('persona_id')
            ->from('mnto_vecinos_satisfechos')->get();
        })
        ->get();
        $encabezado = [
            "Mantenimiento Vecinos Satisfechos",
        ];
        return response()->json(['reportes' => $reportes,'titulo' => $encabezado]);
    }

    public function reporteMntoSatisfechosByZona(int $id){
        $reportes = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','2'],
            ['colonias.zona_id','=',$id]
        ])
        ->whereNotIn('personas.id', function($query){
            $query->select('persona_id')
            ->from('mnto_vecinos_satisfechos')->get();
        })
        ->get();
        $encabezado = [
            "Mantenimiento Vecinos Satisfechos",
        ];
        return response()->json(['reportes' => $reportes,'titulo' => $encabezado]);
    }

    public function reporteMntoMuySatisfechosByDistrito(int $id, int $distrito){
        $reportes = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','4'],
            ['colonias.zona_id','=',$id],
            ['colonias.distrito_id','=',$distrito]
        ])
        ->whereNotIn('personas.id', function($query){
            $query->select('persona_id')
            ->from('mnto_vecinos_muysatisfechos')->get();
        })
        ->get();
        $encabezado = [
            "Mantenimiento Vecinos Muy Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }

    public function reporteMntoMuySatisfechosByZona(int $id){
        $reportes = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','personas.celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','4'],
            ['colonias.zona_id','=',$id]
        ])
        ->whereNotIn('personas.id', function($query){
            $query->select('persona_id')
            ->from('mnto_vecinos_muysatisfechos')->get();
        })
        ->get();
        $encabezado = [
            "Mantenimiento Vecinos Muy Satisfechos",
        ];
        return response()->json(['reportes' => $reportes, 'titulo' => $encabezado]);
    }
}