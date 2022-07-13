<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Carbon;




class ReportesByAlcaldiaController extends Controller
{

    //Reporte para seguimiento vecinos satisfechos que no se realizaron según auditoría


    public function reporteMntoSatisfechos(int $id, int $distrito)
    {
        $alcaldia = DB::table('alcaldias')
            ->select('alcaldias.alcaldia')
            ->where('alcaldias.zona_id', '=', $id)
            ->get();
        $date = Carbon::now();
        $date = $date->format('Y-m-d');
        $query = "SELECT a.id, b.pNombre, b.sNombre, b.tNombre, b.pApellido, b.sApellido, b.tApellido, DATE_FORMAT(a.fecha,'%d/%m/%Y')AS fecha, c.actividad, a.descripcion, a.responsable, CONCAT(d.nombres,' ',d.apellidos) AS auditor
            FROM snto_vecinos_satisfechos a
            INNER JOIN personas b ON b.id = a.persona_id
            INNER JOIN cat_actividades c ON a.actividad_id = c.id
            INNER JOIN users d ON d.id = a.auditor
            inner join colonias e on e.id = b.colonia_id
            WHERE a.realizado = 0 AND b.zona_id = $id AND DATE(a.updated_at) = '$date' and e.distrito_id = $distrito
            UNION
            SELECT a.id, b.pNombre, b.sNombre, b.tNombre, b.pApellido, b.sApellido, b.tApellido, DATE_FORMAT(a.fecha,'%d/%m/%Y')AS fecha, c.actividad, a.descripcion, a.responsable, CONCAT(d.nombres,' ',d.apellidos) AS auditor
            FROM mnto_vecinos_satisfechos a
            INNER JOIN personas b ON b.id = a.persona_id
            INNER JOIN cat_actividades c ON a.actividad_id = c.id
            INNER JOIN users d ON d.id = a.auditor
            inner join colonias e on e.id = b.colonia_id
            WHERE a.realizado = 0 AND b.zona_id = $id AND DATE(a.updated_at) = '$date' and e.distrito_id = $distrito
            UNION
            SELECT a.id, b.pNombre, b.sNombre, b.tNombre, b.pApellido, b.sApellido, b.tApellido, DATE_FORMAT(a.fecha,'%d/%m/%Y')AS fecha, c.actividad, a.descripcion, a.responsable, CONCAT(d.nombres,' ',d.apellidos) AS auditor
            FROM snto_vecinos_muysatisfechos a
            INNER JOIN personas b ON b.id = a.persona_id
            INNER JOIN cat_actividades c ON a.actividad_id = c.id
            INNER JOIN users d ON d.id = a.auditor 
            inner join colonias e on e.id = b.colonia_id
            WHERE a.realizado = 0 AND b.zona_id = $id AND DATE(a.updated_at) = '$date' and e.distrito_id = $distrito
            UNION
            SELECT a.id, b.pNombre, b.sNombre, b.tNombre, b.pApellido, b.sApellido, b.tApellido, DATE_FORMAT(a.fecha,'%d/%m/%Y')AS fecha, c.actividad, a.descripcion, a.responsable, CONCAT(d.nombres,' ',d.apellidos) AS auditor
            FROM mnto_vecinos_muysatisfechos a
            INNER JOIN personas b ON b.id = a.persona_id
            INNER JOIN cat_actividades c ON a.actividad_id = c.id
            INNER JOIN users d ON d.id = a.auditor
            inner join colonias e on e.id = b.colonia_id
            WHERE a.realizado = 0 AND b.zona_id = $id AND DATE(a.updated_at) = '$date' and e.distrito_id = $distrito";
        $reportes = DB::select($query);

        $encabezado = [
            "Mantenimiento Vecinos Satisfechos",
        ];
        $pdf = PDF::loadView('pdf.reporte', ['reportes' => $reportes, 'alcaldia' => $alcaldia, 'titulo' => $encabezado])->setPaper('a4');

        // Guardar el pdf en una carpeta temporal
        $name = '/pdf/mntosatisfechos/' . uniqid() . '.pdf';

        $path = base_path() . '/public' . $name;

        $output = $pdf->save($path);

        return response()->json([
            "url_pdf" => "http://$_SERVER[HTTP_HOST]" . '/PortalPeticiones/api/public' . $name
        ]);
        // return response()->json([
        //     "url_pdf" => "https://$_SERVER[HTTP_HOST]" . '/apis/api-portalpeticiones/public' . $name
        // ]);
    }

    public function reporteMntoMuySatisfechos(int $id, int $distrito)
    {
        $alcaldia = DB::table('alcaldias')
            ->select('alcaldias.alcaldia')
            ->where('alcaldias.zona_id', '=', $id)
            ->get();
        $date = Carbon::now();
        $date = $date->format('Y-m-d');

        $query = "SELECT a.id, b.pNombre, b.sNombre, b.tNombre, b.pApellido, b.sApellido, b.tApellido, DATE_FORMAT(a.fecha,'%d/%m/%Y')AS fecha, c.actividad, a.descripcion, a.responsable, CONCAT(d.nombres,' ',d.apellidos) AS auditor
            FROM snto_vecinos_satisfechos a
            INNER JOIN personas b ON b.id = a.persona_id
            INNER JOIN cat_actividades c ON a.actividad_id = c.id
            INNER JOIN users d ON d.id = a.auditor
            inner join colonias e on e.id = b.colonia_id
            WHERE a.realizado = 0 AND b.zona_id = $id AND DATE(a.updated_at) = '$date' and e.distrito_id = $distrito
            UNION
            SELECT a.id, b.pNombre, b.sNombre, b.tNombre, b.pApellido, b.sApellido, b.tApellido, DATE_FORMAT(a.fecha,'%d/%m/%Y')AS fecha, c.actividad, a.descripcion, a.responsable, CONCAT(d.nombres,' ',d.apellidos) AS auditor
            FROM mnto_vecinos_satisfechos a
            INNER JOIN personas b ON b.id = a.persona_id
            INNER JOIN cat_actividades c ON a.actividad_id = c.id
            INNER JOIN users d ON d.id = a.auditor
            inner join colonias e on e.id = b.colonia_id
            WHERE a.realizado = 0 AND b.zona_id = $id AND DATE(a.updated_at) = '$date' and e.distrito_id = $distrito
            UNION
            SELECT a.id, b.pNombre, b.sNombre, b.tNombre, b.pApellido, b.sApellido, b.tApellido, DATE_FORMAT(a.fecha,'%d/%m/%Y')AS fecha, c.actividad, a.descripcion, a.responsable, CONCAT(d.nombres,' ',d.apellidos) AS auditor
            FROM snto_vecinos_muysatisfechos a
            INNER JOIN personas b ON b.id = a.persona_id
            INNER JOIN cat_actividades c ON a.actividad_id = c.id
            INNER JOIN users d ON d.id = a.auditor 
            inner join colonias e on e.id = b.colonia_id
            WHERE a.realizado = 0 AND b.zona_id = $id AND DATE(a.updated_at) = '$date' and e.distrito_id = $distrito
            UNION
            SELECT a.id, b.pNombre, b.sNombre, b.tNombre, b.pApellido, b.sApellido, b.tApellido, DATE_FORMAT(a.fecha,'%d/%m/%Y')AS fecha, c.actividad, a.descripcion, a.responsable, CONCAT(d.nombres,' ',d.apellidos) AS auditor
            FROM mnto_vecinos_muysatisfechos a
            INNER JOIN personas b ON b.id = a.persona_id
            INNER JOIN cat_actividades c ON a.actividad_id = c.id
            INNER JOIN users d ON d.id = a.auditor
            inner join colonias e on e.id = b.colonia_id
            WHERE a.realizado = 0 AND b.zona_id = $id AND DATE(a.updated_at) = '$date' and e.distrito_id = $distrito";
        $reportes = DB::select($query);

        $encabezado = [
            "Mantenimiento Vecinos Muy Satisfechos",
        ];
        $pdf = PDF::loadView('pdf.reporte', ['reportes' => $reportes, 'alcaldia' => $alcaldia, 'titulo' => $encabezado])->setPaper('a4');

        // Guardar el pdf en una carpeta temporal
        $name = '/pdf/mntomuysatisfechos/' . uniqid() . '.pdf';

        $path = base_path() . '/public' . $name;

        $output = $pdf->save($path);

        return response()->json([
            "url_pdf" => "http://$_SERVER[HTTP_HOST]" . '/PortalPeticiones/api/public' . $name
        ]);
        // return response()->json([
        //     "url_pdf" => "https://$_SERVER[HTTP_HOST]" . '/apis/api-portalpeticiones/public' . $name
        // ]);
    }
}
