<?php

namespace App\Http\Controllers\user;

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
use App\Models\exercise_m_model;
use App\Models\gym_m_model;
use App\Models\training_detail_t_model;
use App\Models\training_history_t_model;
use App\Models\user_m_model;
use App\Models\weight_log_t_model;
// Model ↑

class training_controller extends Controller
{
    function index(Request $request)
    {            
        
        $user_id = 1;

        // ユーザー毎の最大値training_countかつend_datetimeがnullのデータを取得
        $training_history_t = training_history_t_model::where('user_id', $user_id)
        ->whereNull('end_datetime')  // end_datetime が null のものを取得
        ->orderBy('training_count', 'desc')  // training_count の降順でソート
        ->withTrashed()
        ->first();
        
        $start_datetime = $training_history_t ? $training_history_t->start_datetime : null;


        return view('user/screen/training/index', compact('start_datetime'));
        
     
    }
}
