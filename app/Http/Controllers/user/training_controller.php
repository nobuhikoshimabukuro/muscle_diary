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
        
        // セッション情報取得
        $user_info = common::get_login_user_info();
        // セッション有無
        if (!$user_info->login_status) {
            return redirect(route('user.login'));
        }

        $user_id = $user_info->user_id;
        $training_history_t = session()->get('training_history_t');
                
        //新しいデータか確認する為
        $new_data_flg = true;
        $start_datetime = "";
        $end_datetime = "";

        if(!is_null($training_history_t)){

            $start_datetime = $training_history_t->start_datetime ? $training_history_t->start_datetime : "";
            $end_datetime = $training_history_t->end_datetime ? $training_history_t->end_datetime : "";

            if($end_datetime == ""){
                $new_data_flg = false;
            }
            
        }

        


        return view('user/screen/training/index', compact('start_datetime'));
        
     
    }
}
