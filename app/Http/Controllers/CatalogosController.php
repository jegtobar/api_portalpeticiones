<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Services\Implementation\catalogos\AlcaldiaServiceImpl;
use App\Services\Implementation\catalogos\RolesServiceImpl;
use App\Services\Implementation\catalogos\ZonasServiceImpl;
use App\Services\Implementation\catalogos\LiderazgoServiceImpl;
use App\Services\Implementation\catalogos\DependenciasServiceImpl;
use App\Services\Implementation\catalogos\PeticionesServiceImpl;
use App\Services\Implementation\catalogos\CatalogoCanalServiceImpl;
use App\Services\Implementation\catalogos\DistritoServiceImpl;
use App\Services\Implementation\catalogos\SectoresServiceImpl;
use App\Services\Implementation\catalogos\ColoniaServiceImpl;
use App\Services\Implementation\catalogos\ZonaAlcaldiaServiceImpl;
use App\Services\Implementation\catalogos\CatalogoActividadesServiceImpl;
use Illuminate\Http\Request;

class CatalogosController extends Controller{
    private $alcaldiasService;
    private $rolesService;
    private $zonasService;
    private $liderazgoService;
    private $dependenciaService;
    private $peticionService;
    private $canalService;
    private $distritoService;
    private $sectorService;
    private $coloniaService;
    private $catalogoActividades;
    private $zonaAlcaldiaService;
    private $request;

    public function __construct(CatalogoActividadesServiceImpl $catalogoActividades,ZonaAlcaldiaServiceImpl $zonaAlcaldiaService,ColoniaServiceImpl $coloniaService,SectoresServiceImpl $sectorService,DistritoServiceImpl $distritoService,CatalogoCanalServiceImpl $canal,PeticionesServiceImpl $peticionService,DependenciasServiceImpl $dependenciaService,LiderazgoServiceImpl $liderazgoService,ZonasServiceImpl $zonasService, AlcaldiaServiceImpl $alcaldiasService, Request $request, RolesServiceImpl $rolesService ){
        $this->alcaldiasService = $alcaldiasService;
        $this->rolesService = $rolesService;
        $this->zonasService = $zonasService;
        $this->liderazgoService = $liderazgoService;
        $this->dependenciaService = $dependenciaService;
        $this->peticionService = $peticionService;
        $this->canalService = $canal;
        $this->distritoService = $distritoService;
        $this->sectorService = $sectorService;
        $this->coloniaService = $coloniaService;
        $this->zonaAlcaldiaService = $zonaAlcaldiaService;
        $this->request = $request;
        $this->catalogoActividades = $catalogoActividades;

    }


    //Listar las alcaldias en json
    function getAlcaldias(){
        $alcaldias=$this->alcaldiasService->getAlcaldias();
        return response()->json(['alcaldias'=>$alcaldias, 'status'=>'Success']);
    }

     //Listar los roles en json
     function getRoles(){
        $roles=$this->rolesService->getRoles();
        return response()->json(['roles'=>$roles, 'status'=>'Success']);
    }

    //Listar las zonas en json
    function getZonas(){
        $zonas=$this->zonasService->getZonas();
        return response()->json(['zonas'=>$zonas, 'status'=>'Success']);
    }

       //Listar liderazgo en json
       function getLiderazgo(){
        $liderazgo=$this->liderazgoService->getLiderazgo();
        return response()->json(['liderazgo'=>$liderazgo, 'status'=>'Success']);
    }

     //Listar dependencias en json
    function getDependencia(){
        $dependencia=$this->dependenciaService->getDependencia();
        return response()->json(['dependencia'=>$dependencia, 'status'=>'Success']);
    }

    //Listar tipo de peticiones en json
    function getPeticion(){
        $peticion =$this->peticionService->getPeticiones();
        return response()->json(['peticion'=>$peticion, 'status'=>'Success']);
    }

       //Listar catalogo de canales (vías de comunicación entre vecino y alcaldia) en json
    function getCanales(){
        $canal =$this->canalService->getCanal();
        return response()->json(['canales'=>$canal, 'status'=>'Success']);
    }


    //Listado de colonias por filtro en json

    //Todas las colonias
    function getColonia(){
        $colonia =$this->coloniaService->getColonia();
        return response()->json(['colonias'=>$colonia, 'status'=>'Success']);
    }

    //Catalogo actividades
    function getCatActividades(){
        $actividades = $this->catalogoActividades->getCatActividades();
        return response()->json(['actividades'=>$actividades, 'status'=>'Success']);
    }

    /*Inicio de filtros según alcaldía auxiliar

    Se filtran las zonas según la alcaldía asignada al usuario que está logeado
    en el sistema.

    zona>distrito(si aplica)>sector(si aplica) -> colonia

    */

    //Selección de zona por alcaldía
    function getZonaByAlcaldia(int $id){
        $zonaAlcaldia=DB::table('zonas')
        ->join('alcaldia_zona', 'alcaldia_zona.zona_id','=','zonas.id')
        ->where('alcaldia_zona.alcaldia_id','=', $id)->get();

        //Validar si la zona tiene distrito
        $distrito = DB::table('zonas_distrito')
                        ->where('zonas_distrito.zona_id',"=",$id)
                        ->get();
        if($distrito->isEmpty()){
            $distrito="n";
        }else{
            $distrito="s";
        }

        //validar si la zona tiene sectores
        $sector =  DB::table('sectores')
                ->where('sectores.zona_id',"=",$id)
                ->get();
        if($sector->isEmpty()){
        $sector="n";
        }else{
        $sector="s";
        }
        return response()->json(['zona'=>$zonaAlcaldia, 'distrito'=>$distrito, 'sector'=>$sector , 'status'=>'Success']);
    }


    //Funcion para mostrar la zona de la alcaldía para el usuario logeado
    function getZonaAlcaldia(int $id){
        $zonaAlcaldia=DB::table('zonas')
        ->join('alcaldia_zona', 'alcaldia_zona.zona_id','=','zonas.id')
        ->where('alcaldia_zona.alcaldia_id','=', $id)->first();
        return response()->json([
            'zona'=>$zonaAlcaldia->zona
        ]);
    }

    //funcion para mostrar el nombre del rol del usuario logeado
    function getRol(int $id){
        $rol = DB::table('roles')
        ->where('roles.id','=',$id)
        ->first();
        return response()->json([
            'rol'=>$rol->rol
        ]);
    }

        //Lista de distritos segun zona
        function getDistritos(int $id){
            $distrito=DB::table('distritos')
            ->join('zonas_distrito', 'distritos.id','=','zonas_distrito.distrito_id')
            ->where('zonas_distrito.zona_id','=', $id)->get();
            return response()->json(['distritos'=>$distrito, 'status'=>'Success']);
        }

        //Lista de sector (aplica solo a zona 11)
        function getSectores(int $id){
            $sector=DB::table('sectores')
            ->select('sectores.id', 'sectores.sector')
            ->join('zonas', 'zonas.id','=','sectores.zona_id')
            ->where('sectores.zona_id','=', $id)->get();
            return response()->json(['sectores'=>$sector, 'status'=>'Success']);
        }



    //Colonia por zonas
    function getColoniaByZona(int $id){
        $colonia=DB::table('colonias')
        ->select('colonias.id','colonias.colonia')
        ->where('zona_id', $id)->get();
        return response()->json(['colonias'=>$colonia, 'status'=>'Success']);
    }

    //Colonia por distritos
    function getColoniaByDistritos(int $distrito,int $zona){
        $colonia=DB::table('colonias')
        ->select('colonias.id','colonias.colonia')
        ->join('zonas', 'colonias.zona_id','=','zonas.id')
        ->join('distritos', 'colonias.distrito_id','=','distritos.id')
        ->where([
            ['colonias.distrito_id','=', $distrito],
            ['colonias.zona_id','=',$zona],
        ])->get();
        return response()->json(['colonias'=>$colonia, 'status'=>'Success']);
    }
    //Colonia por sectores
    function getColoniaBySectores(int $sector, int $zona){
        $colonia=DB::table('colonias')
        ->select('colonias.id','colonias.colonia')
        ->join('sectores', 'colonias.sector_id','=','sectores.id')
        ->where([
            ['colonias.sector_id','=', $sector],
            ['colonias.zona_id','=',$zona],
        ])->get();
        return response()->json(['colonias'=>$colonia, 'status'=>'Success']);
    }
}
