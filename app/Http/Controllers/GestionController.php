<?php

namespace App\Http\Controllers;
use App\Services\Implementation\GestionServiceImpl;
use Illuminate\Http\Request;
use App\Validator\GestionValidator;
use App\Models\gestiones;
use App\Models\snto_vecinos_satisfechos;
use Illuminate\Support\Facades\DB;

class GestionController extends Controller
{
    private $gestionService;
    private $request;
    //validador de gestion
    private $validator;

    public function __construct(GestionServiceImpl $gestionService, Request $request){
        $this->gestionService = $gestionService;
        $this->request = $request;
    }


    //creacion de gestiones
    function createGestion(){
        $this->gestionService->postGestion($this->request->all());
        $id = gestiones::latest('id')
        ->select('id')
        ->first();
        return response()->json(['last_insert_id'=>$id, 'status'=>'Success']);
    }

    //lista de gestiones
    function getGestiones(){
        return response($this->gestionService->getGestiones());
    }

    //mostrar gestión por id
    function getGestionById(int $id){
        $peticion=$this->gestionService->getGestionById($id);
        return response()->json(['gestion'=>$peticion, 'status'=>'success']);
    }

    //mostrar gestiónes ingresadas por dpi del vecino
    function getGestionByDpi(int $id, int $alcaldia){
        return response($this->gestionService->getGestionByDpi($id, $alcaldia));
    }

    //actualización datos de gestión
    function putGestion(int $id){
        $response = response("",202);
        $this->gestionService->putGestion($this->request->all(), $id);
        return $response;
    }

    //eliminar (deshabilitar) gestión
    function deleteGestion(int $id){
        $this->gestionService->deleteGestion($id);
        return response("",204);
    }

    //Mostrar seguimientos de gestión según número de gestión
    function getGestionSeguimientobyId(int $id){
        return response($this->gestionService->getGestionSeguimientobyId($id));
    }

    //Crear seguimientos de gestion según número de gestión
    function createSeguimientoGestion(){
        $response = response("", 201);
        $this->gestionService->postSeguimientoGestion($this->request->all());
        return $response;
    }



    //Crear seguimientos (para promotores)
    function createSeguimientos(){
        $seguimiento=$this->gestionService->postSeguimientos($this->request->all());
        return response()->json($seguimiento);
    }



    //Crear seguimiento vecinos satisfechos
    function createSeguimientoVecinosSatisfechos(){
        
        $seguimiento=$this->gestionService->postSeguimientoVecinoSatisfecho($this->request->all());
        return response()->json($seguimiento);
    }

    //Crear seguimiento mantenimiento vecinos satisfechos
    function createSeguimientoMantenimientoVecinosSatisfechos(){
        $response = response("", 201);
        $this->gestionService->postSeguimientoMantenimientoSatisfecho($this->request->all());

    }

    //Mostrar seguimiento de vecino satisfecho por id
       function getSeguimientoVecinosSatisfechosById(int $id){
        $response = DB::table('snto_vecinos_satisfechos')
                    ->select('snto_vecinos_satisfechos.id','snto_vecinos_satisfechos.persona_id',DB::raw('DATE_FORMAT(snto_vecinos_satisfechos.fecha,"%d/%m/%Y")AS date'),'snto_vecinos_satisfechos.fecha','snto_vecinos_satisfechos.descripcion','cat_actividades.actividad','snto_vecinos_satisfechos.responsable','snto_vecinos_satisfechos.responsable','snto_vecinos_satisfechos.realizado' )
                    ->join('cat_actividades','cat_actividades.id','=','snto_vecinos_satisfechos.actividad_id')
                    ->where([
                        ['snto_vecinos_satisfechos.persona_id','=',$id],
                        ['snto_vecinos_satisfechos.realizado','=','1']
                    ])
                    ->get();

         return response()->json($response);

       }

       //Mostrar seguimiento de mantenimiento vecino satisfecho por id
       function getSeguimientoMantenimientoVecinosSatisfechosById(int $id){
        $response = DB::table('mnto_vecinos_satisfechos')
                    ->select('mnto_vecinos_satisfechos.id','mnto_vecinos_satisfechos.persona_id',DB::raw('DATE_FORMAT(mnto_vecinos_satisfechos.fecha,"%d/%m/%Y")AS date'),'mnto_vecinos_satisfechos.fecha','mnto_vecinos_satisfechos.descripcion','cat_actividades.actividad','mnto_vecinos_satisfechos.responsable','mnto_vecinos_satisfechos.realizado' )
                    ->join('cat_actividades','cat_actividades.id','=','mnto_vecinos_satisfechos.actividad_id')
                    ->where([
                        ['mnto_vecinos_satisfechos.persona_id','=',$id],
                        ['mnto_vecinos_satisfechos.realizado','=','1']
                    ])
                    ->get();

         return response()->json($response);

       }


       //Actualizar seguimiento de vecino satisfecho
       function putSeguimientoSatisfecho(int $id){
        $response = response("",202);
        $this->gestionService->putSeguimientoSatisfecho($this->request->all(), $id);
        return $response;
    }

    //Eliminar seguimiento de un vecino satisfecho
    function deleteSeguimientoSatisfecho(int $id){
        $this->gestionService->delSeguimientoSatisfecho($id);
    }


    //Mostrar seguimiento de vecino Muy satisfecho por id
    function getSeguimientoVecinosMuySatisfechosById(int $id){
        $response = DB::table('snto_vecinos_muysatisfechos')
                    ->select('snto_vecinos_muysatisfechos.id','snto_vecinos_muysatisfechos.persona_id',DB::raw('DATE_FORMAT(snto_vecinos_muysatisfechos.fecha,"%d/%m/%Y")AS date'),'snto_vecinos_muysatisfechos.fecha','snto_vecinos_muysatisfechos.descripcion','cat_actividades.actividad','snto_vecinos_muysatisfechos.responsable','snto_vecinos_muysatisfechos.realizado' )
                    ->join('cat_actividades','cat_actividades.id','=','snto_vecinos_muysatisfechos.actividad_id')
                    ->where([
                        ['snto_vecinos_muysatisfechos.persona_id','=',$id],
                        ['snto_vecinos_muysatisfechos.realizado','=','1']
                    ])
                    ->get();

         return response()->json($response);

       }
    
       //Función para crear seguimiento vecino muy 
       function createSeguimientoVecinosMuySatisfechos(){
        $seguimiento = $this->gestionService->postSeguimientoVecinoMuySatisfecho($this->request->all());
        return response()->json($seguimiento);
    }

     //Actualizar seguimiento de vecino Muy satisfecho
     function putSeguimientoMuySatisfecho(int $id){
        $response = response("",202);
        $this->gestionService->putSeguimientoMuySatisfecho($this->request->all(), $id);
        return $response;
    }

    //Eliminar seguimiento de un vecino Muy satisfecho
    function deleteSeguimientoMuySatisfecho(int $id){
        $this->gestionService->delSeguimientoMuySatisfecho($id);
    }


//Mostrar seguimiento de mantenimiento vecino satisfecho por id
// function getSeguimientoVecinosMntoSatisfechosById(int $id){
//     $response = DB::table('mnto_vecinos_satisfechos')
//                 ->select('mnto_vecinos_satisfechos.id','mnto_vecinos_satisfechos.persona_id','mnto_vecinos_satisfechos.fecha','mnto_vecinos_satisfechos.descripcion','cat_actividades.actividad','mnto_vecinos_satisfechos.responsable','mnto_vecinos_satisfechos.realizado' )
//                 ->join('cat_actividades','cat_actividades.id','=','mnto_vecinos_satisfechos.actividad_id')
//                 ->where([
//                     ['mnto_vecinos_satisfechos.persona_id','=',$id],
//                     ['mnto_vecinos_satisfechos.persona_id','=','1']
//                 ])
//                 ->get();

//      return response()->json($response);

//    }

   //Función para crear seguimiento mantenimiento vecino satisfecho
   function createSeguimientoVecinosMntoSatisfechos(){
    $response = response("", 201);
    $this->gestionService->postSeguimientoMantenimientoSatisfecho($this->request->all());
}

 //Actualizar seguimiento de mantenimiento vecino   satisfecho
 function putSeguimientoMantenimientoSatisfecho(int $id){
    $response = response("",202);
    $this->gestionService->putSeguimientoMantenimientoSatisfecho($this->request->all(), $id);
    return $response;
}

//Eliminar seguimiento de un mantenimiento vecino satisfecho
function delSeguimientoMantenimientoSatisfecho(int $id){
    $this->gestionService->delSeguimientoMantenimientoSatisfecho($id);
}






//Mostrar seguimiento de mantenimiento vecino muy satisfecho por id
function getSeguimientoVecinosMntoMuySatisfechosById(int $id){
    $response = DB::table('mnto_vecinos_muysatisfechos')
                ->select('mnto_vecinos_muysatisfechos.id','mnto_vecinos_muysatisfechos.persona_id',DB::raw('DATE_FORMAT(mnto_vecinos_muysatisfechos.fecha,"%d/%m/%Y")AS date'),'mnto_vecinos_muysatisfechos.fecha','mnto_vecinos_muysatisfechos.descripcion','cat_actividades.actividad','mnto_vecinos_muysatisfechos.responsable' )
                ->join('cat_actividades','cat_actividades.id','=','mnto_vecinos_muysatisfechos.actividad_id')
                ->where([
                    ['mnto_vecinos_muysatisfechos.persona_id','=',$id],
                    ['mnto_vecinos_muysatisfechos.realizado','=','1']
                    ])
                ->get();

     return response()->json($response);

   }

   //Función para crear seguimiento mantenimiento vecino muy satisfecho
   function createSeguimientoVecinosMntoMuySatisfechos(){
    $response = response("", 201);
    $this->gestionService->postSeguimientoMantenimientoMuySatisfecho($this->request->all());
}

 //Actualizar seguimiento de mantenimiento vecino  muy satisfecho
 function putSeguimientoMantenimientoMuySatisfecho(int $id){
    $response = response("",202);
    $this->gestionService->putSeguimientoMantenimientoMuySatisfecho($this->request->all(), $id);
    return $response;
}

//Eliminar seguimiento de un mantenimiento vecino muy satisfecho
function delSeguimientoMantenimientoMuySatisfecho(int $id){
    $this->gestionService->delSeguimientoMantenimientoMuySatisfecho($id);
}




}
