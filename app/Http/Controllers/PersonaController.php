<?php

namespace App\Http\Controllers;
use App\Services\Implementation\PersonaServiceImpl;
use Illuminate\Http\Request;
use App\Validator\PersonValidator;

class PersonaController extends Controller
{
    private $personaService;
    private $request;
    //validador de persona
    private $validator;

    public function __construct(PersonaServiceImpl $personaService, Request $request, PersonValidator $personaValidator){
        $this->personaService = $personaService;
        $this->request = $request;
        $this->validator = $personaValidator;
    }

    //creacion de personas
    function createPersona(){
        $response = response("", 201);
        $this->personaService->postPersona($this->request->all());
        // $validator = $this->validator->validate();
        // if ($validator->fails()){
        //     $response = response([
        //         "status"=>422,
        //         "message"=>"Error",
        //         "errors"=>$validator->errors()
        //     ],422);
        // }else{

        // }

        return $response;

    }

   /*
        SECCIÓN VECINOS SATISFECHOS - MANTENIMIENTO VECINOS SATISFECHOS xD

    */

    //Lista de Vecinos Satisfechos (Válido para administrador y auditor)
    function getPersona(){
        $personas=$this->personaService->getPersonas();
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

    function getTodasLasPersonas(){
        $personas=$this->personaService->getTodasLasPersonas();
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

    //Lista de vecinos satisfechos (Válido para alcaldes auxiliares y coordinadores)

    function getPersonasByZona(int $id){
        $persona=$this->personaService->getPersonasByZona($id);
        return response()->json(['persona'=>$persona, 'status'=>'Success']);
    }

    function getPersonasByZonaPromotores(int $id, string $vecino){
        $persona=$this->personaService->getPersonasByZonaPromotores($id,$vecino);
        return response()->json(['persona'=>$persona, 'status'=>'Success']);
    }
    
    function getPersonasByZonaDistritoPromotores(int $id, int $distrito, string $vecino){
        $persona=$this->personaService->getPersonasByZonaDistritoPromotor($id, $distrito, $vecino);
        return response()->json(['persona'=>$persona, 'status'=>'Success']);
    }

    function getPersonasByZonaDistritoSatisfecho(int $id, int $distrito){
        $persona=$this->personaService->getPersonasByZonaDistrito($id, $distrito);
        return response()->json(['persona'=>$persona, 'status'=>'Success']);
    }
    function getPersonasByZonaDistritoMntoSatisfecho(int $id, int $distrito){
        $persona=$this->personaService->getPersonasByZonaDistritoMntoSatisfechos($id, $distrito);
        return response()->json(['persona'=>$persona, 'status'=>'Success']);
    }

    function getPersonasByZonaDistritoMuySatisfecho(int $id, int $distrito){
        $persona=$this->personaService->getPersonasByZonaDistritoMSatisfecho($id, $distrito);
        return response()->json(['persona'=>$persona, 'status'=>'Success']);
    }

    function getPersonasByZonaDistritoMntoMuySatisfecho(int $id, int $distrito){
        $persona=$this->personaService->getPersonasByZonaDistritoMntoMuySatisfecho($id, $distrito);
        return response()->json(['persona'=>$persona, 'status'=>'Success']);
    }
    function getPersonasByName(int $seguimiento, string $vecino){
        $persona=$this->personaService->getPersonasByName($seguimiento, $vecino);
        return response()->json(['persona'=>$persona, 'status'=>'Success']);
    }

    function getPersonaInsatisfechos(){
        $personas=$this->personaService->getPersonasInsatisfechas();
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

    //Lista de vecinos satisfechos (Válido para alcaldes auxiliares y coordinadores)

    function getPersonasInsatisfechosByZona(int $id){
        $persona=$this->personaService->getPersonasInsatisfechasByZona($id);
        return response()->json(['persona'=>$persona, 'status'=>'Success']);
    }


    //Lista de personas Mantenimiento satisfechos para administradores y auditores
    function getPersonaMantenimientoSatisfechos(){
        $personas=$this->personaService->getPersonasMantenimientoSatisfechos();
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

    //Lista de mantenimiento vecinos satisfechos para alcaldes auxiliares y coordinadores
    function getPersonaMantenimientoSatisfechosByZona(int $id){
        $personas=$this->personaService->getPersonasMantenimientoSatisfechosByZona($id);
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

     //Lista de mantenimiento vecinos satisfechos para alcaldes auxiliares y coordinadores
     function getPersonaMntoSatisfechosAuditoría(int $id){
        $personas=$this->personaService->getPersonasMntoSatisfechosAuditoria($id);
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

//Actualización de seguimiento del vecino a mantenimiento (sea satisfecho o muy satisfecho)
    function putMantenimientoSatisfecho(int $id){
        $response = response("",202);
        $this->personaService->putCambioSeguimiento($this->request->all(), $id);
        // $validator = $this->validator->validate();
        // if ($validator->fails()){
        //     $response = response([
        //         "status"=>422,
        //         "message"=>"Error",
        //         "errors"=>$validator->errors()
        //     ],422);
        // }else{

        // }

        return $response;
    }


   /*
        FIN DE SECCIÓN VECINOS SATISFECHOS - MANTENIMIENTO VECINOS SATISFECHOS xD

 */


 /*
        SECCIÓN VECINOS MUY SATISFECHOS - MANTENIMIENTO VECINOS MUY SATISFECHOS xD

    */



    //Lista de personas (Vecinos Muy Satisfechos) Válido para administradores y auditores
    function getPersonaMuySatisfechos(){
        $personas=$this->personaService->getPersonasMuySatisfechas();
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

     //Lista de personas (Vecinos Muy Satisfechos) Válido para alcaldes auxiliares y coordinadores
     function getPersonaMuySatisfechosByZona(int $id){
        $personas=$this->personaService->getPersonasMuySatisfechasByZona($id);
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

    //Lista de personas Mantenimiento Muy satisfechos válido para administradores y auditores
    function getPersonaMantenimientoMuySatisfechos(){
        $personas=$this->personaService->getPersonasMantenimientoMuySatisfechos();
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

     //Lista de personas Mantenimiento Muy satisfechos válido para alcaldes auxiliares y coordinadores
     function getPersonaMantenimientoMuySatisfechosByZona(int $id){
        $personas=$this->personaService->getPersonasMantenimientoMuySatisfechosByZona($id);
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

    //Lista de personas Mantenimiento Muy satisfechos para auditores
    function getPersonaMntoMuySatisfechosAuditoria(int $id){
        $personas=$this->personaService->getPersonasMntoMuySatisfechoAuditoria($id);
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }


   /*
        FIN DE SECCIÓN VECINOS MUY SATISFECHOS - MANTENIMIENTO VECINOS MUY SATISFECHOS xD

 */


    //Motrar persona por id
    function getPersonaById(int $id){
        $persona=$this->personaService->getPersonaById($id);
        return response()->json(['persona'=>$persona, 'status'=>'Success']);
    }

    //Mostrar persona por dpi
    function getPersonaByDpi(int $id){
        $persona=$this->personaService->getPersonaByDpi($id);
        return response()->json(['persona'=>$persona, 'status'=>'success']);
    }

    //Mostrar personas en datatable
    function getPersonaDataTable(){
        $personas=$this->personaService->getPersonaDataTable();
        return response()->json(['personas'=>$personas, 'status'=>'Success']);
    }

    //Actualizar persona

    function putPersona(int $id){

        $response =  $this->personaService->putPersona($this->request->all(), $id);
        // $validator = $this->validator->validate();
        // if ($validator->fails()){
        //     $response = response([
        //         "status"=>422,
        //         "message"=>"Error",
        //         "errors"=>$validator->errors()
        //     ],422);
        // }else{

        // }

        return $response;
    }

    //Eliminar (deshabilitar) persona

    function deletePersona(int $id){
        $this->personaService->delPersona($id);
        // return response()->json($delete);
    }

     //Restaurar persona
     function restorePersona(int $id){
        $this->personaService->restorePersona($id);
        return response("",204);
    }
}
