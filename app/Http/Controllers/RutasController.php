<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RutasController extends Controller
{
    function getRutas(Request $request){
        $rutas = DB::table('menus')
        ->select('menus.url')
        ->join('menu_usuarios','menu_usuarios.menu_id','=','menus.id')
        ->where([
            ['menu_usuarios.rol_id','=',$request->rol],
            ['menus.url','=',$request->url]
        ])
        ->get();
        return response()->json($rutas);
    }
}