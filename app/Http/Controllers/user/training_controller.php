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


        $training_info = (object)[
            "new_data_flg" => $new_data_flg
            ,"start_datetime" => $start_datetime
            ,"end_datetime" => $end_datetime
        ];
        


        return view('user/screen/training/index', compact('training_info'));       
     
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

            

            $training_history_t = session()->get('training_history_t');                            
            $training_history_id = 0;
            $user_training_count = 1;

            if(!is_null($training_history_t)){    

                if(is_null($training_history_t->end_datetime)){
                    $training_history_id = $training_history_t->training_history_id;
                }else{
                    $user_training_count = $training_history_t->user_training_count + 1;
                }                             
            }
           
            $table = training_history_t_model::find($training_history_id);

            if (empty($table)) {

                $table = new training_history_t_model;
                $table->user_id = $user_id;
                $table->user_training_count = $user_training_count;
                
                $table->start_datetime = $request->set_datetime;
                $table->created_by = $user_id;
                $table->created_at = now();

            }else{
                $table->end_datetime = $request->set_datetime;                
            }

            $table->user_gym_id = $request->user_gym_id;

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
