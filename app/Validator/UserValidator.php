<?php

namespace App\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserValidator{
    private $request;

    public function __construct(Request $request){
        $this->request = $request;
    }

    public function validate(){
        return Validator::make($this->request->all(), $this->rules(), $this->messages());
    }

    private function rules(){
        return[
            "rol_id"=>"required",
            "nombres"=> "required",
            "apellidos"=>"required",
            "username"=>"required",
            "password"=>"required",
            "dpi"=>"required|unique:users,dpi,".$this->request->id
        ];

    }

    private function messages(){
        return[];
    }
}
