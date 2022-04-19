<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;


class ReportesController extends Controller {

    //Reporte para seguimiento vecinos satisfechos que no se realizaron según auditoría
    public function reporteSatisfechos(int $id){
        $alcaldia = DB::table('alcaldias')
        ->select('alcaldias.alcaldia')
        ->where('alcaldias.zona_id','=',$id)
        ->get();

        $reportes = DB::table('snto_vecinos_satisfechos')
        ->select('snto_vecinos_satisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(snto_vecinos_satisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'snto_vecinos_satisfechos.descripcion', 'snto_vecinos_satisfechos.responsable', DB::raw("CONCAT(users.nombres,' ',users.apellidos) AS auditor"))
        ->Join('personas','personas.id','=','snto_vecinos_satisfechos.persona_id')
        ->Join('cat_actividades','cat_actividades.id','=','snto_vecinos_satisfechos.actividad_id')
        ->Join('users','users.id','=','snto_vecinos_satisfechos.auditor')
        ->where([
            ['snto_vecinos_satisfechos.realizado','=','0'],
            ['personas.zona_id','=',$id],
            [DB::raw('DATE(snto_vecinos_satisfechos.updated_at)'),'=',DB::raw('curdate()')]
        ])
        ->get();

        $encabezado = [
            "Vecinos Satisfechos",
        ];
    


         
        $pdf = PDF::loadView('pdf.reporte', ['reportes' => $reportes, 'alcaldia' => $alcaldia, 'titulo' => $encabezado])->setPaper('a4');

        // Guardar el pdf en una carpeta temporal
        $name = '/pdf/satisfechos/' . uniqid() . '.pdf';
        
        $path = base_path().'/public' . $name;

        $output = $pdf->save($path);

        return response()->json([
            "url_pdf" => "https://$_SERVER[HTTP_HOST]" . '/apis/api-portalpeticiones/public' . $name
        ]);

        //return $pdf->download('reporte.pdf');
    }

    public function reporteMuySatisfechos(int $id){
        $alcaldia = DB::table('alcaldias')
        ->select('alcaldias.alcaldia')
        ->where('alcaldias.zona_id','=',$id)
        ->get();
    
        $reportes = DB::table('snto_vecinos_muysatisfechos')
        ->select('snto_vecinos_muysatisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(snto_vecinos_muysatisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'snto_vecinos_muysatisfechos.descripcion', 'snto_vecinos_muysatisfechos.responsable', DB::raw("CONCAT(users.nombres,' ',users.apellidos) AS auditor"))
        ->leftJoin('personas','personas.id','=','snto_vecinos_muysatisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','snto_vecinos_muysatisfechos.actividad_id')
        ->leftJoin('users','users.id','=','snto_vecinos_muysatisfechos.auditor')
        ->where([
            ['snto_vecinos_muysatisfechos.realizado','=','0'],
            ['personas.zona_id','=',$id],
            [DB::raw('DATE(snto_vecinos_muysatisfechos.updated_at)'),'=',DB::raw('curdate()')]
        ])
        ->get();

        $encabezado = [
            "Vecinos Muy Satisfechos",
        ];
        $pdf = PDF::loadView('pdf.reporte', ['reportes' => $reportes, 'alcaldia' => $alcaldia, 'titulo' => $encabezado])->setPaper('a4');

        // Guardar el pdf en una carpeta temporal
        $name = '/pdf/muysatisfechos/' . uniqid() . '.pdf';
        
        $path = base_path().'/public' . $name;

        $output = $pdf->save($path);

        return response()->json([
            "url_pdf" => "http://$_SERVER[HTTP_HOST]" . '/apis/api-portalpeticiones/public' . $name
        ]);
       
    }

    public function reporteMntoSatisfechos(int $id){
        $alcaldia = DB::table('alcaldias')
        ->select('alcaldias.alcaldia')
        ->where('alcaldias.zona_id','=',$id)
        ->get();

        $reportes = DB::table('mnto_vecinos_satisfechos')
        ->select('mnto_vecinos_satisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(mnto_vecinos_satisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'mnto_vecinos_satisfechos.descripcion', 'mnto_vecinos_satisfechos.responsable', DB::raw("CONCAT(users.nombres,' ',users.apellidos) AS auditor"))
        ->leftJoin('personas','personas.id','=','mnto_vecinos_satisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','mnto_vecinos_satisfechos.actividad_id')
        ->leftJoin('users','users.id','=','mnto_vecinos_satisfechos.auditor')
        ->where([
            ['mnto_vecinos_satisfechos.realizado','=','0'],
            ['personas.zona_id','=',$id],
            [DB::raw('DATE(mnto_vecinos_satisfechos.updated_at)'),'=',DB::raw('curdate()')]
        ])
        ->get();

        $encabezado = [
            "Mantenimiento Vecinos Satisfechos",
        ];
        $pdf = PDF::loadView('pdf.reporte', ['reportes' => $reportes, 'alcaldia' => $alcaldia, 'titulo' => $encabezado])->setPaper('a4');

        // Guardar el pdf en una carpeta temporal
        $name = '/pdf/mntosatisfechos/' . uniqid() . '.pdf';
        
        $path = base_path().'/public' . $name;

        $output = $pdf->save($path);

        return response()->json([
            "url_pdf" => "http://$_SERVER[HTTP_HOST]" . '/apis/api-portalpeticiones/public' . $name
        ]);
    }

    public function reporteMntoMuySatisfechos(int $id){
        $alcaldia = DB::table('alcaldias')
        ->select('alcaldias.alcaldia')
        ->where('alcaldias.zona_id','=',$id)
        ->get();
        

        $reportes = DB::table('mnto_vecinos_muysatisfechos')
        ->select('mnto_vecinos_muysatisfechos.id',  'personas.pNombre', 'personas.sNombre', 'personas.tNombre', 'personas.pApellido', 'personas.sApellido', 'personas.tApellido', DB::raw('DATE_FORMAT(mnto_vecinos_muysatisfechos.fecha,"%d/%m/%Y")AS fecha'), 'cat_actividades.actividad', 'mnto_vecinos_muysatisfechos.descripcion', 'mnto_vecinos_muysatisfechos.responsable', DB::raw("CONCAT(users.nombres,' ',users.apellidos) AS auditor"))
        ->leftJoin('personas','personas.id','=','mnto_vecinos_muysatisfechos.persona_id')
        ->leftJoin('cat_actividades','cat_actividades.id','=','mnto_vecinos_muysatisfechos.actividad_id')
        ->leftJoin('users','users.id','=','mnto_vecinos_muysatisfechos.auditor')
        ->where([
            ['mnto_vecinos_muysatisfechos.realizado','=','0'],
            ['personas.zona_id','=',$id],
            [DB::raw('DATE(mnto_vecinos_muysatisfechos.updated_at)'),'=',DB::raw('curdate()')]
        ])
        ->get();

        $encabezado = [
            "Mantenimiento Vecinos Muy Satisfechos",
        ];
        $pdf = PDF::loadView('pdf.reporte', ['reportes' => $reportes, 'alcaldia' => $alcaldia, 'titulo' => $encabezado])->setPaper('a4');

        // Guardar el pdf en una carpeta temporal
        $name = '/pdf/mntomuysatisfechos/' . uniqid() . '.pdf';
        
        $path = base_path().'/public' . $name;

        $output = $pdf->save($path);

        return response()->json([
            "url_pdf" => "http://$_SERVER[HTTP_HOST]" . '/apis/api-portalpeticiones/public' . $name
        ]);
    }
}