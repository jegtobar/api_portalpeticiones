<?php

namespace App\Services\Implementation;
use App\Services\Interfaces\IPersonasServiceInterface;
use Illuminate\Support\Facades\DB;
use App\Models\personas;


class PersonaServiceImpl implements IPersonasServiceInterface{
    private $model;

    function __construct()
    {
        $this->model = new personas();
    }
      //Función para mostrar todos las personas (valido para promotores distritos zona 1 y 21)
      function getPersonasByZonaDistritoPromotor(int $id, int $distrito, string $vecino){
        $persona = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza','personas.seguimiento')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.zona_id','=',$id],
            ['colonias.distrito_id','=',$distrito],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.pApellido)"),'LIKE',"%".$vecino."%"]
        ])
        ->orWhere([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.sNombre,' ',personas.pApellido)"),'LIKE',"%".$vecino."%"]
        ])
        ->orWhere([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"]
        ])
        ->orWhere([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.sNombre,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"]
        ])
        ->orWhere([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.sNombre)"),'LIKE',"%".$vecino."%"]
        ])
        ->orWhere([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.pApellido,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"]
        ])
        
        ->whereNull('personas.deleted_at')
        ->get();
        return $persona;
        // return $this->model->get();
    }
    //Función para mostrar todos las personas (Válido promotores)
    function getPersonasByZonaPromotores(int $id, string $vecino){
        $persona = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','personas.zona_id','personas.seguimiento')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.pApellido)"),'LIKE',"%".$vecino."%"]
        ])
        ->orWhere([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.sNombre,' ',personas.pApellido)"),'LIKE',"%".$vecino."%"]
        ])
        ->orWhere([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"]
        ])
        ->orWhere([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.sNombre,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"]
        ])
        ->orWhere([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.sNombre)"),'LIKE',"%".$vecino."%"]
        ])
        ->orWhere([
            ['personas.zona_id','=',$id],
            [DB::raw("CONCAT(personas.pApellido,' ',personas.sApellido)"),'LIKE',"%".$vecino."%"]
        ])
        ->whereNull('personas.deleted_at')
        ->orderBy('personas.id','ASC')
        ->get();
        return $persona;
        // return $this->model->get();
    }

        /*
        SECCIÓN VECINOS SATISFECHOS - MANTENIMIENTO VECINOS SATISFECHOS 
        */

    //Función para mostrar todos las personas en fase de vecinos satisfechos (Válido para administradores y auditores)
    function getPersonas(){
        $persona = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where('personas.seguimiento','=','1')
        ->whereNull('personas.deleted_at')
        ->get();
        return $persona;
        // return $this->model->get();
    }

    function getPersonasByName(int $seguimiento, string $vecino){
        $persona = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=',$seguimiento],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.pApellido)"),'LIKE','%'.$vecino.'%']
            ])
        ->orWhere([
            ['personas.seguimiento','=',$seguimiento],
            [DB::raw("CONCAT(personas.pNombre,' ',personas.sApellido)"),'LIKE','%'.$vecino.'%']
            ])
        ->whereNull('personas.deleted_at')
        ->get();
        return $persona;
        // return $this->model->get();
    }
    //Función para mostrar a todas las personas
    function getTodasLasPersonas(){
        $persona = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.seguimiento','personas.usuario_actualiza')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->whereNull('personas.deleted_at')
        ->get();
        return $persona;
        // return $this->model->get();
    }

    //Función para mostrar todos las personas en fase de vecinos satisfechos (Válido para alcaldes auxiliares y coordinadores)
    function getPersonasByZona(int $id){
        $persona = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','1'],
            ['personas.zona_id','=',$id],
        ])
        ->whereNull('personas.deleted_at')
        ->get();
        return $persona;
        // return $this->model->get();
    }

   //Función para mostrar todos las personas insatisfechos (Válido para alcaldes auxiliares y coordinadores)
   function getPersonasInsatisfechasByZona(int $id){
    $persona = DB::table('personas')
    ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
    ->join('zonas','zonas.id','=','personas.zona_id')
    ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
    ->join('colonias','colonias.id','=','personas.colonia_id')
    ->where([
        ['personas.seguimiento','=','5'],
        ['personas.zona_id','=',$id],
    ])
    ->whereNull('personas.deleted_at')
    ->get();
    return $persona;
    // return $this->model->get();
    }

       //Función para mostrar todos las personas insatisfechos (Válido para administradores y auditores)
   function getPersonasInsatisfechas(){
    $persona = DB::table('personas')
    ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
    ->join('zonas','zonas.id','=','personas.zona_id')
    ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
    ->join('colonias','colonias.id','=','personas.colonia_id')
    ->where([
        ['personas.seguimiento','=','5']
    ])
    ->whereNull('personas.deleted_at')
    ->get();
    return $persona;
    // return $this->model->get();
    }
     //Función para mostrar todos las personas en fase de  Mantenimiento Vecinos Satisfechos (Administradores y Auditores)
     function getPersonasMantenimientoSatisfechos(){
        $persona = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where('personas.seguimiento','=','2')
        ->whereNull('personas.deleted_at')
        ->get();
        return $persona;
        // return $this->model->get();
    }


     //Función para mostrar todos las personas en fase de  Mantenimiento Vecinos Satisfechos (Alcaldes Auxiliares y coordinadores)
     function getPersonasMantenimientoSatisfechosByZona(int $id){
        $persona = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','2'],
            ['personas.zona_id','=',$id],
        ])
        ->whereNull('personas.deleted_at')
        ->get();
        return $persona;
        // return $this->model->get();
    }

    //Función para mostrar todos las personas en fase de  Mantenimiento Vecinos Satisfechos (Administradores y Auditores)
    function getPersonasMntoSatisfechosAuditoria(int $id){
        
        $persona = DB::table('personas')
        ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where([
            ['personas.seguimiento','=','2'],
            ['personas.zona_id','=',$id],
        ])
        ->whereIn('personas.id', function($query){
            $query->select('persona_id')
            ->from('mnto_vecinos_satisfechos')->get();
        })
        ->whereNull('personas.deleted_at')
        ->get();
        return $persona;
        // return $this->model->get();
    }


    //BUSCAR VECINOS SATISFECHOS POR ALCALDIA Y DISTRITO (APLICA PARA LAS ZONAS 1 Y 21)
        //Función para mostrar todos las personas en fase de vecinos satisfechos (Válido para alcaldes auxiliares y coordinadores)
        function getPersonasByZonaDistrito(int $id, int $distrito){
            $persona = DB::table('personas')
            ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
            ->join('zonas','zonas.id','=','personas.zona_id')
            ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
            ->join('colonias','colonias.id','=','personas.colonia_id')
            ->where([
                ['personas.seguimiento','=','1'],
                ['personas.zona_id','=',$id],
                ['colonias.distrito_id','=',$distrito],
            ])
            ->whereNull('personas.deleted_at')
            ->get();
            return $persona;
            // return $this->model->get();
        }

        function getPersonasByZonaDistritoMntoSatisfechos(int $id, int $distrito){
            $persona = DB::table('personas')
            ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
            ->join('zonas','zonas.id','=','personas.zona_id')
            ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
            ->join('colonias','colonias.id','=','personas.colonia_id')
            ->where([
                ['personas.seguimiento','=','2'],
                ['personas.zona_id','=',$id],
                ['colonias.distrito_id','=',$distrito],
            ])
            ->whereNull('personas.deleted_at')
            ->get();
            return $persona;
            // return $this->model->get();
        }


        /*
        FIN DE SECCIÓN VECINOS SATISFECHOS - MANTENIMIENTO VECINOS SATISFECHOS xD

        */


        /*
        SECCIÓN VECINOS MUY SATISFECHOS - MANTENIMIENTO VECINOS MUY SATISFECHOS xD

        */

        //Función para mostrar todos las personas en fase de vecinos Muy satisfechos (Válido para administradores y auditores)
        function getPersonasMuySatisfechas(){
            $persona = DB::table('personas')
            ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo', 'personas.zona_id', 'personas.usuario_actualiza')
            ->join('zonas','zonas.id','=','personas.zona_id')
            ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
            ->join('colonias','colonias.id','=','personas.colonia_id')
            ->where('personas.seguimiento','=','3')
            ->whereNull('personas.deleted_at')
            ->get();
            return $persona;
            // return $this->model->get();
        }

        //Función para mostrar todas las personas muy satisfechas (válido para alcaldes auxiliares y coordinadores)
        function getPersonasMuySatisfechasByZona(int $id){
            $persona = DB::table('personas')
            ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
            ->join('zonas','zonas.id','=','personas.zona_id')
            ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
            ->join('colonias','colonias.id','=','personas.colonia_id')
            ->where([
                ['personas.seguimiento','=','3'],
                ['personas.zona_id','=',$id],
            ])
            ->whereNull('personas.deleted_at')
            ->get();
            return $persona;
            // return $this->model->get();
        }

        function getPersonasByZonaDistritoMSatisfecho(int $id, int $distrito){
            $persona = DB::table('personas')
            ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
            ->join('zonas','zonas.id','=','personas.zona_id')
            ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
            ->join('colonias','colonias.id','=','personas.colonia_id')
            ->where([
                ['personas.seguimiento','=','3'],
                ['personas.zona_id','=',$id],
                ['colonias.distrito_id','=',$distrito],
            ])
            ->whereNull('personas.deleted_at')
            ->get();
            return $persona;
            // return $this->model->get();
        }

        


        //Función para mostrar todos las personas en fase de  Mantenimiento Vecinos Muy Satisfechos (Válido para administradores y auditores)
        function getPersonasMantenimientoMuySatisfechos(){
            $persona = DB::table('personas')
            ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
            ->join('zonas','zonas.id','=','personas.zona_id')
            ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
            ->join('colonias','colonias.id','=','personas.colonia_id')
            ->where('personas.seguimiento','=','4')
            ->whereNull('personas.deleted_at')
            ->get();
            return $persona;
            // return $this->model->get();
        }

        //Función para mostrar todas las personas en fase de mantenimiento vecinos muy satisfechos (válido para alcaldes auxiliares y coordinadores)
        function getPersonasMantenimientoMuySatisfechosByZona(int $id){
            $persona = DB::table('personas')
            ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
            ->join('zonas','zonas.id','=','personas.zona_id')
            ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
            ->join('colonias','colonias.id','=','personas.colonia_id')
            ->where([
                ['personas.seguimiento','=','4'],
                ['personas.zona_id','=',$id],
            ])
            ->whereNull('personas.deleted_at')
            ->get();
            return $persona;
            // return $this->model->get();
        }

        //Funcion para mostrar a los vecinos mantenimiento muy satisfechos para auditores

        function getPersonasMntoMuySatisfechoAuditoria(int $id){
            $persona = DB::table('personas')
            ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','colonias.distrito_id','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
            ->join('zonas','zonas.id','=','personas.zona_id')
            ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
            ->join('colonias','colonias.id','=','personas.colonia_id')
            ->where([
                ['personas.seguimiento','=','4'],
                ['personas.zona_id','=',$id],
            ])
            ->whereIn('personas.id', function($query){
                $query->select('persona_id')
                ->from('mnto_vecinos_muysatisfechos')->get();
            })
            ->whereNull('personas.deleted_at')
            ->get();
            return $persona;
        }

        function getPersonasByZonaDistritoMntoMuySatisfecho(int $id, int $distrito){
            $persona = DB::table('personas')
            ->select('personas.id', 'personas.pNombre', 'personas.sNombre', 'personas.tNombre','personas.pApellido','personas.sApellido','personas.tApellido','personas.dpi',DB::raw('DATE_FORMAT(personas.nacimiento,"%d/%m/%Y")AS fecha'),'personas.nacimiento','personas.direccion','personas.numero_casa','zonas.zona','personas.colonia_id','colonias.colonia','personas.telefono_casa','celular','personas.correo','personas.genero','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.usuario_actualiza')
            ->join('zonas','zonas.id','=','personas.zona_id')
            ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
            ->join('colonias','colonias.id','=','personas.colonia_id')
            ->where([
                ['personas.seguimiento','=','4'],
                ['personas.zona_id','=',$id],
                ['colonias.distrito_id','=',$distrito],
            ])
            ->whereNull('personas.deleted_at')
            ->get();
            return $persona;
            // return $this->model->get();
        }


    /*
        FIN DESECCIÓN VECINOS MUY SATISFECHOS - MANTENIMIENTO VECINOS MUY SATISFECHOS xD

    */


    /*Función para trasladar a un vecino  de mantenimiento vecino satisfecho a mantenimiento vecino muy satisfecho
      o de mantenimiento muy satisfecho a mantenimiento satisfecho
    */
    function putCambioSeguimiento(array $persona, int $id)
    {
        switch ($persona['seguimiento']) {
         
            case '1':
                $this->model->where('id',$id)
                ->first()
                ->fill($persona)
                ->save();
                break;
            case '2':
                $this->model->where('id',$id)
                ->first()
                ->fill($persona)
                ->save();
                DB::select("CALL update_meta_mnto_muysatisfecho_a_mnto_satisfecho()");
                break;
            case '3':
                $this->model->where('id',$id)
                ->first()
                ->fill($persona)
                ->save();
                break;
            case '4':
                $this->model->where('id',$id)
                ->first()
                ->fill($persona)
                ->save();
                DB::select("CALL update_meta_mnto_satisfecho_a_mnto_muysatisfecho()");
                break;

            default:
                # code...
                break;
        }
    }


    //Función para llenar datatable de personas
    function getPersonaDataTable(){
        $persona = DB::table('personas')
        ->select(DB::raw("CONCAT(personas.pNombre,' ',personas.sNombre,' ',COALESCE(personas.tNombre,'')) AS nombres, CONCAT(personas.pApellido,' ',personas.sApellido)AS apellidos, CONCAT(personas.direccion,' ',personas.numero_casa,' Z.',personas.zona)AS direccion"),'personas.id','personas.dpi','personas.telefono_casa','personas.celular','liderazgos.liderazgo AS tipo','personas.liderazgo','personas.correo')
        ->get();
        return $persona;
    }

    //Función para mostrar persona por id @param int $id, @return boolean
    function getPersonaById(int $id){
        return $this->model->find($id);
    }

    //Función para mostrar persona por dpi

    function getPersonaByDpi(int $id){
        $persona = DB::table('personas')
        ->select(DB::raw("CONCAT(personas.pNombre,' ',personas.sNombre,' ',personas.tNombre) AS nombre, CONCAT(personas.pApellido,' ',personas.sApellido)AS apellido, CONCAT(personas.direccion,' ',personas.numero_casa,' Z.',zonas.zona,' ', colonias.colonia)AS direccion"), 'personas.dpi','personas.celular', 'personas.id', 'liderazgos.liderazgo AS tipo','personas.liderazgo','personas.zona_id','personas.seguimiento')
        ->join('zonas','zonas.id','=','personas.zona_id')
        ->join('liderazgos','liderazgos.id','=','personas.liderazgo')
        ->join('colonias','colonias.id','=','personas.colonia_id')
        ->where('dpi', $id)
        ->whereNull('personas.deleted_at')
        ->orWhere('celular', '=', $id)->first();
        return $persona;
    }

    //Función para creación de persona @param array $user, @param int $id, @return void
    function postPersona(array $persona){

        $this->model->create($persona);
    }

    //Función para actualización de usuario @param int $id, @return boolean
    function putPersona(array $persona, int $id)
    {
        $coloniaAnterior = DB::table('personas')
        ->select('personas.colonia_id','personas.seguimiento')
       ->where('personas.id', $id)
       ->first();
       if($coloniaAnterior->colonia_id!=$persona['colonia_id']){
           $idAnterior = $coloniaAnterior->colonia_id;
           $idNuevo = $persona['colonia_id'];

            switch ($coloniaAnterior->seguimiento) {
                case 1:
                    DB::select("CALL cambiocolonia_satisfechos($idAnterior,$idNuevo)");
                    break;
                case 2:
                    DB::select("CALL cambiocolonia_mntosatisfechos($idAnterior,$idNuevo)");
                    break;
                case 3:
                    DB::select("CALL cambiocolonia_muysatisfechos($idAnterior,$idNuevo)");
                    break;
                case 4:
                    DB::select("CALL cambiocolonia_mnto_muysatisfechos($idAnterior,$idNuevo)");
                    break;
            }
       }
        $this->model->where('id',$id)
        ->first()
        ->fill($persona)
        ->save();
        DB::select("CALL bitacora_update_personas()");
    }

    //Función para actualizar el tipo de seguimiento del usuario (satisfecho - > mantenimiento, muy satisfecho -)

    //Función para inhabilitar usuario @param int $id, @return boolean
    function delPersona(int $id)
    {
        $persona = $this->model->find($id);
        if($persona != null){
            $persona->delete();
        }

        $seguimiento = DB::table('personas')
         ->select('personas.seguimiento')
        ->where('personas.id', $id)
        ->first();

        switch ($seguimiento->seguimiento) {
            case 1:
                DB::select("CALL meta_satisfechos_eliminavecino()");
                break;
            case 2:
                DB::select("CALL meta_mntosatisfechos_eliminavecino()");
                break;
            case 3:
                DB::select("CALL meta_muysatisfechos_eliminavecino()");
                break;
            case 4:
                DB::select("CALL meta_mntomuysatisfechos_eliminavecino()");
                break;
        }
    }

    //Función para restaurar usuario bloqueado @param int $id, @return boolean
    function restorePersona(int $id)
    {
        $persona = $this->model->withTrashed()->find($id);
        if($persona != null){
            $persona->restore();
        }
    }
}
