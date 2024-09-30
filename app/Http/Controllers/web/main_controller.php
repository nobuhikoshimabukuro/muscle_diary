<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class main_controller extends Controller
{
    function index(Request $request)
    {       
      

        $demo = "";

        return view('web/screen/index', compact('demo'));
        
     
    }
}
