<?php

namespace App\Http\Controllers\user;

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

class weight_log_t_controller extends Controller
{
    function index(Request $request)
    {       
      
        // セッション情報取得
        $user_info = common::get_login_user_info();
        // セッション有無
        if (!$user_info->login_status) {
            return redirect(route('user.login'));
        }


        $demo = "";

        return view('user/screen/weight_log_t/index', compact('demo'));
        
     
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

            // $table = weight_log_t_model::find($request->user_id);
            $table = weight_log_t_model::find(0);

            if (empty($table)) {

                $table = new weight_log_t_model;
                $table->user_id = $user_id;
                $table->user_weight_log_id = db_common::get_user_max_value($user_id,2);
                $table->created_by = $user_id;
                $table->created_at = now();

            }

            $weight = $request->weight;
            $weight_type = $request->weight_type;

            if($weight_type == 2){
                $weight = common::weight_conversion($weight,2);
            }

            $table->weight = $weight;            
            $table->measure_at = $request->measure_at;

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
