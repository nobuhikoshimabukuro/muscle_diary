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

class exercise_m_controller extends Controller
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

        $exercise_m = exercise_m_model::where('user_id', $user_id)
        ->get();

        $demo = "";

        return view('user/screen/exercise_m/index', compact('exercise_m'));
        
     
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
            
            $table = exercise_m_model::where('user_exercise_id', $request->user_exercise_id)
            ->where('user_id', $user_id)
            ->first();

            $measurement_type = $request->measurement_type;
            $bodyweight_flg = 0;

            //$measurement_type == 2（1 = 時間 , 2 = 重さ）のみ自重フラグの値を画面から取得する
            if($measurement_type == 2){              
                $bodyweight_flg = $request->bodyweight_flg ?? 0;
            }

            if (empty($table)) {

                $table = new exercise_m_model;
                $table->user_id = $user_id;
                $table->user_exercise_id = db_common::get_user_max_value($user_id,4);
                $table->created_by = $user_id;
                $table->created_at = now();

            }
     
            $table->exercise_name = $request->exercise_name;
            $table->measurement_type = $measurement_type;
            $table->bodyweight_flg = $bodyweight_flg;
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
