<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// controller作成時ここからコピー↓
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Original\common;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
// controller作成時ここまでコピー↑


// Model ↓
use App\Models\user_m_model;
// Model ↑

class dashboard_controller extends Controller
{
    function index(Request $request)
    {       
      
        $demo = "";

        return view('web/screen/dashboard/index', compact('demo'));
       
     
    }
}
