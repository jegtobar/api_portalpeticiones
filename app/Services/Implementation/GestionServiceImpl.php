<?php

namespace App\Services\Implementation;
use App\Services\Interfaces\IGestionesServiceInterface;
use Illuminate\Support\Facades\DB;
use App\Models\gestiones;
use App\Models\personas;
Use App\Models\snto_vecinos_satisfechos;
Use App\Models\snto_vecinos_muysatisfechos;
use App\Models\mnto_vecinos_satisfechos;
Use App\Models\mnto_vecinos_muysatisfechos;
use App\Models\seguimientos_gestiones;


class GestionServiceImpl implements IGestionesServiceInterface{
    private $model;
    private $modelSeguimiento;
    private $modelSeguimientoVecinoSatisfecho;
    private $modelSegMantenimientoVecinoSatisfecho;
    private $modelSeguimientoMuySatisfecho;
    private $modelSeguimientoMantenimientoMuySatisfecho;

    function __construct()
    {
        $this->model = new gestiones();
        $this->modelSeguimiento = new seguimientos_gestiones();
        $this->modelSeguimientoVecinoSatisfecho = new snto_vecinos_satisfechos();
        $this->modelSegMantenimientoVecinoSatisfecho = new mnto_vecinos_satisfechos();
        $this->modelSeguimientoMuySatisfecho = new snto_vecinos_muysatisfechos();
        $this->modelSeguimientoMantenimientoMuySatisfecho = new mnto_vecinos_muysatisfechos();

    }

     //Función para mostrar todos las gestiones
     function getGestiones(){
         return $this->model->get();
     }
     //Función para mostrar gestion por id @param int $id, @return boolean
     function getGestionById(int $id){
      $gestion = DB::table('gestiones')
      ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS promotor,CONCAT(personas.pNombre,' ',personas.sNombre,' ',COALESCE(personas.tNombre,'')) AS nombres, CONCAT(personas.pApellido,' ',personas.sApellido)AS apellidos"),'gestiones.usuario_id','gestiones.id','descripcion', DB::raw('DATE_FORMAT(gestiones.fecha,"%d/%m/%Y")AS fecha'),'estatus','gestiones.direccion', DB::raw("CONCAT('Z. ',gestiones.zona)AS zona"),'colonias.colonia','gestiones.oficio')
      ->join('users','gestiones.usuario_id','=','users.id')
      ->join('personas', 'gestiones.persona_id','=','personas.id')
      ->join('colonias','colonias.id','=','gestiones.colonia')
      ->where('gestiones.id','=',$id)
      ->first();
      return $gestion;
     }
     //Función para creación de gestion @param array $persona, @param int $id, @return void
     function postGestion(array $gestion){
        $this->model->create($gestion);
        $id = gestiones::latest('id')->first();
     }


     //Función para actualización de gestion @param int $id, @return boolean
     function putGestion(array $gestion, int $id){
        $this->model->where('id',$id)
        ->first()
        ->fill($gestion)
        ->save();
     }
     //Función para inhabilitar gestion @param int $id, @return boolean
     function deleteGestion(int $id){

        $gestion = $this->model->find($id);
        if($gestion != null){
            $gestion->delete();
        }

     }
     //Función para restaurar persona bloqueado @param int $id, @return boolean
     function restoreGestion(int $id){
        $gestion = $this->model->withTrashed()->find($id);
        if($gestion != null){
            $gestion->restore();
        }
     }

    //  Funcion para obtener gestiones del vecino por dpi
     function getGestionbyDpi(int $id, int $alcaldia){
      $peticion = DB::table('gestiones')
      ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS promotor"),'gestiones.id', 'gestiones.descripcion', 'dependencias.dependencia','tipo_peticiones.peticion','gestiones.estatus', DB::raw('DATE_FORMAT(gestiones.fecha,"%d/%m/%Y")AS fecha'))
      ->join('dependencias', 'gestiones.dependencia_id','=','dependencias.id')
      ->join('tipo_peticiones', 'gestiones.peticion_id','=','tipo_peticiones.id')
      ->join('users','gestiones.usuario_id','=','users.id')
      ->join('personas', 'gestiones.persona_id','=','personas.id')
        ->where([
           ['personas.dpi','=', $id],
           ['users.alcaldia_id','=',$alcaldia],
         ])->get();
      return $peticion;
     }

     //Funcion para consultar el seguimiento de la gestion según número de gestión
     function getGestionSeguimientobyId(int $id){
        $seguimiento = DB::table('seguimientos_gestiones')
        ->select(DB::raw("CONCAT(users.nombres,' ',users.apellidos)AS promotor"),'actividad_id', 'descripcion', DB::raw('DATE_FORMAT(seguimientos_gestiones.fecha,"%d/%m/%Y")AS fecha'))
        ->join('users','seguimientos_gestiones.usuario_id','=','users.id')
        ->where('seguimientos_gestiones.gestion_id','=',$id)
        ->get();
        return $seguimiento;
     }

     //Funcion para ingresar seguimientos según número de gestión
     function postSeguimientoGestion(array $seguimiento){
      $this->modelSeguimiento->create($seguimiento);

     }


     //SEGUIMIENTO DE VECINOS

     //SEGUIMIENTOS VECINO SATISFECHO

     //Funcion para ingresar seguimiento de vecino satisfecho
     function postSeguimientoVecinoSatisfecho(array $seguimiento){
        $this->modelSeguimientoVecinoSatisfecho->create($seguimiento);
        $persona = DB::table('snto_vecinos_satisfechos')
        ->select('snto_vecinos_satisfechos.persona_id')
        ->orderBy('snto_vecinos_satisfechos.updated_at','desc')
        ->limit(1)
        ->first();
        $seguimientos = DB::table('snto_vecinos_satisfechos')
        ->select(DB::raw("count(snto_vecinos_satisfechos.persona_id)AS seguimientos"))
        ->where('snto_vecinos_satisfechos.persona_id','=',$persona->persona_id)
        ->first();
        //Validación, si el vecino acumula mas de 4 seguimientos, actualiza de vecino satisfecho a mantenimiento vecino satisfecho
        if ($seguimientos->seguimientos >= 4){
         $personaUpdate = personas::find($persona->persona_id);
         if($personaUpdate->seguimiento=1){
            $personaUpdate->seguimiento = 2;
            $personaUpdate->save();
            DB::select("CALL update_meta_satisfecho_a_mnto_satisfecho()");
            $res = 'true';
            return $res;
        }
      }
       
    }
     //Funcion para actualizar un seguimiento de vecino satisfecho

     function putSeguimientoSatisfecho(array $seguimiento, int $id){
      $this->modelSeguimientoVecinoSatisfecho->where('id',$id)
      ->first()
      ->fill($seguimiento)
      ->save();
     }

     //Funcion para eliminar seguimiento de vecino satisfecho
     function delSeguimientoSatisfecho(int $id)
    {
        $seguimiento = $this->modelSeguimientoVecinoSatisfecho->find($id);
        if($seguimiento != null){
            $seguimiento->delete();
        }
    }





 //SEGUIMIENTO VECINOS MUY SATISFECHOS
 //Funcion para ingresar seguimiento de vecino muy satisfecho
 function postSeguimientoVecinoMuySatisfecho(array $seguimiento){
   $this->modelSeguimientoMuySatisfecho->create($seguimiento);
   $persona = DB::table('snto_vecinos_muysatisfechos')
        ->select('snto_vecinos_muysatisfechos.persona_id')
        ->orderBy('snto_vecinos_muysatisfechos.updated_at','desc')
        ->limit(1)
        ->first();
        $seguimientos = DB::table('snto_vecinos_muysatisfechos')
        ->select(DB::raw("count(snto_vecinos_muysatisfechos.persona_id)AS seguimientos"))
        ->where('snto_vecinos_muysatisfechos.persona_id','=',$persona->persona_id)
        ->first();
        if ($seguimientos->seguimientos >= 12){
         $personaUpdate = personas::find($persona->persona_id);
         if($personaUpdate->seguimiento=3){
            $personaUpdate->seguimiento = 4;
            $personaUpdate->save();
            DB::select("CALL update_meta_muysatisfecho_a_mnto_muysatisfecho()");
            $res = 'true';
            return $res;
        }
      }
}

//Funcion para actualizar un seguimiento de vecino muy satisfecho

function putSeguimientoMuySatisfecho(array $seguimiento, int $id){
 $this->modelSeguimientoMuySatisfecho->where('id',$id)
 ->first()
 ->fill($seguimiento)
 ->save();
}

//Funcion para eliminar seguimiento de vecino muy satisfecho
function delSeguimientoMuySatisfecho(int $id)
{
   $seguimiento = $this->modelSeguimientoMuySatisfecho->find($id);
   if($seguimiento != null){
       $seguimiento->delete();
   }
}


//SEGUIMIENTO MANTENIMIENTO VECINOS SATISFECHOS
 //Funcion para ingresar seguimiento de vecino muy satisfecho
 function postSeguimientoMantenimientoSatisfecho(array $seguimiento){
   $this->modelSegMantenimientoVecinoSatisfecho->create($seguimiento);
}

//Funcion para actualizar un seguimiento de vecino muy satisfecho

function putSeguimientoMantenimientoSatisfecho(array $seguimiento, int $id){
 $this->modelSegMantenimientoVecinoSatisfecho->where('id',$id)
 ->first()
 ->fill($seguimiento)
 ->save();
}

//Funcion para eliminar seguimiento de vecino muy satisfecho
function delSeguimientoMantenimientoSatisfecho(int $id)
{
   $seguimiento = $this->modelSegMantenimientoVecinoSatisfecho->find($id);
   if($seguimiento != null){
       $seguimiento->delete();
   }
}


//SEGUIMIENTO MANTENIMIENTO VECINOS MUY  SATISFECHOS
 //Funcion para ingresar seguimiento de vecino muy satisfecho
 function postSeguimientoMantenimientoMuySatisfecho(array $seguimiento){
   $this->modelSeguimientoMantenimientoMuySatisfecho->create($seguimiento);
}

//Funcion para actualizar un seguimiento de vecino muy satisfecho

function putSeguimientoMantenimientoMuySatisfecho(array $seguimiento, int $id){
 $this->modelSeguimientoMantenimientoMuySatisfecho->where('id',$id)
 ->first()
 ->fill($seguimiento)
 ->save();
}

//Funcion para eliminar seguimiento de vecino muy satisfecho
function delSeguimientoMantenimientoMuySatisfecho(int $id)
{
   $seguimiento = $this->modelSeguimientoMantenimientoMuySatisfecho->find($id);
   if($seguimiento != null){
       $seguimiento->delete();
   }
}



//Funcion para ingresar seguimientos (módulo crear vecinos para promotróres)
function postSeguimientos(array $seguimiento){
   $personaUpdate = personas::find($seguimiento['persona_id']);
   switch ($personaUpdate->seguimiento) {
      case 1:
         $this->modelSeguimientoVecinoSatisfecho->create($seguimiento);
         $seguimientos = DB::table('snto_vecinos_satisfechos')
         ->select(DB::raw("count(snto_vecinos_satisfechos.persona_id)AS seguimientos"))
         ->where('snto_vecinos_satisfechos.persona_id','=',$seguimiento['persona_id'])
         ->first();
         //Validación, si el vecino acumula mas de 4 seguimientos, actualiza de vecino satisfecho a mantenimiento vecino satisfecho
         if ($seguimientos->seguimientos >= 4){
               $personaUpdate->seguimiento = 2;
               $personaUpdate->save();
               DB::select("CALL update_meta_satisfecho_a_mnto_satisfecho()");
               $res = 'true';
               return $res;
            }
         break;
      case 2:
         $this->modelSegMantenimientoVecinoSatisfecho->create($seguimiento);
         break;
      case 3:
         $this->modelSeguimientoMuySatisfecho->create($seguimiento);
         $seguimientos = DB::table('snto_vecinos_muysatisfechos')
         ->select(DB::raw("count(snto_vecinos_muysatisfechos.persona_id)AS seguimientos"))
         ->where('snto_vecinos_muysatisfechos.persona_id','=',$seguimiento['persona_id'])
         ->first();
         //Validación, si el vecino acumula mas de 4 seguimientos, actualiza de vecino satisfecho a mantenimiento vecino satisfecho
         if ($seguimientos->seguimientos >= 12){
               $personaUpdate->seguimiento = 4;
               $personaUpdate->save();
               DB::select("CALL update_meta_muysatisfecho_a_mnto_muysatisfecho()");
               $res = 'true';
               return $res;
            }
         break;
      case 4:
         $this->modelSeguimientoMantenimientoMuySatisfecho->create($seguimiento);
         break;
      
      default:
         # code...
         break;
   }

  
}

}
