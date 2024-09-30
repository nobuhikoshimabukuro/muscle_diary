<?php

namespace App\Original;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// controller作成時ここからコピー↓
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
// controller作成時ここまでコピー↑


// Model ↓
use App\Models\user_m_model;
// Model ↑


class common
{
    public static function test()
    {
        // 初期値
        $return_value = 0;      
        

        return $return_value;
    
    }

}
