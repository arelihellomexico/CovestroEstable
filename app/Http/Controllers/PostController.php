<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(){
      return view('/login');
    }
    public function store(Request $request){
      $this->validate($request, [
        'email'=>'required',
        'contrasenia'=>'required',
      ]);
    }

}
