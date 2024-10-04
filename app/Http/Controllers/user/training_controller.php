<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class training_controller extends Controller
{
    function index(Request $request)
    {       
      
        $demo = "";

        return view('user/screen/exercise_m/index', compact('demo'));
        
     
    }
}
