<?php

namespace App\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PersonValidator{
    private $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function validate(){
        return Validator::make($this->request->all(), $this->rules(), $this->messages());
    }

    private function rules(){
        return[

            "pNombre"=>"required",

            "pApellido"=>"required",
     
            "direccion"=>"required",

            "zona"=>"required",
         
            "celular"=>"required",
         
            "genero"=>"required",
            "liderazgo"=>"required",
        ];

    }

    private function messages(){
        return[];
    }
}
