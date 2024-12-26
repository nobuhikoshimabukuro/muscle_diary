<?php

namespace App\Http\Controllers\user\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// controller作成時ここからコピー↓
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Original\common;
use App\Original\db_common;
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

class gym_m_controller extends Controller
{
    function index(Request $request)
    {       
      
        // セッション情報取得
        $user_info = common::get_login_user_info();
        // セッション有無
        if (!$user_info->login_status) {

            common::set_after_login_url($request);            
            return redirect(route('user.login'));
        }

        $user_id = $user_info->user_id;

        $gym_m = gym_m_model::where('user_id', $user_id)
        ->get();

        $demo = "";

        return view('user/screen/gym_m/index', compact('gym_m'));
        
     
    }

    function save(Request $request)
    {       
      
        // セッション情報取得
        $user_info = common::get_login_user_info();

        // セッション有無
        if (!$user_info->login_status) {

            $result_array = array(
                "result" => "login_again",
                "message" => "",
            );

            return response()->json(['result_array' => $result_array]);
        }       

        try{

            $user_id = $user_info->user_id;

            
            $table = gym_m_model::where('user_gym_id', $request->user_gym_id)
                   ->where('user_id', $user_id)
                   ->first();

            if (empty($table)) {

                $table = new gym_m_model;
                $table->user_id = $user_id;
                $table->user_gym_id = db_common::get_user_max_value($user_id,1);
                $table->created_by = $user_id;
                $table->created_at = now();

            }

     
            $table->gym_name = $request->gym_name;
            $table->display_flg = $request->display_flg;            
            $table->display_order = $request->display_order;

            $table->updated_by = $user_id;
            $table->updated_at = now();

            // テーブル更新
            $table->save();

            $result_array = array(
                "result" => "success",
                "message" => "",
            );

        }catch(Exception $e){
            
            $error_message = $e->getMessage();
            
            $result_array = [
                "result" => "error",
                "message" => "登録処理でエラーが発生しました[{$error_message}]"
            ];
           
        }

        return response()->json(['result_array' => $result_array]);

    }
}
