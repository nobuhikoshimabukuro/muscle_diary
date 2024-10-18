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

// Request ↓
use App\Http\Requests\training_detail_t_request;

// Request ↑

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
        $gym_name = "";
        $start_datetime = "";
        $end_datetime = "";

        if(!is_null($training_history_t)){

            $gym_name = $training_history_t->gym_name ? $training_history_t->gym_name : "";

            // start_datetimeとend_datetimeをスラッシュ形式で取得（NULLの場合はnullのまま）
            $start_datetime = $training_history_t->start_datetime
            ? Carbon::parse($training_history_t->start_datetime)->format('Y/m/d H:i:s')
            : "";

            $end_datetime = $training_history_t->end_datetime
            ? Carbon::parse($training_history_t->end_datetime)->format('Y/m/d H:i:s')
            : "";            

            if($end_datetime == ""){
                $new_data_flg = false;
            }
            
        }


        $training_info = (object)[
            "new_data_flg" => $new_data_flg
            ,"gym_name" => $gym_name
            ,"start_datetime" => $start_datetime
            ,"end_datetime" => $end_datetime
        ];
        

        $training_history_t = self::get_training_history_t($user_id);


        $gym_m = db_common::get_user_item($user_id,1);
        $exercise_m = db_common::get_user_item($user_id,2);       

        return view('user/screen/training/index', compact('training_history_t','training_info','gym_m','exercise_m'));       
     
    }

    function get_training_history_t($user_id)
    {
        //トレーニング記録取得                                           
        $training_history_t = training_history_t_model::
        select(
            'training_history_t.*',
            DB::raw('TIMEDIFF(training_history_t.end_datetime, training_history_t.start_datetime) as duration'),
            'gym_m.gym_name'
        )
        ->leftJoin('gym_m', function ($join) {
            $join->on('gym_m.user_id', '=', 'training_history_t.user_id')
                ->on('gym_m.user_gym_id', '=', 'training_history_t.user_gym_id');
        })
        ->where('training_history_t.user_id', $user_id) // training_history_tのuser_idを使用
        ->orderBy('training_history_t.user_training_count', 'desc')
        ->get();
      

        foreach ($training_history_t as $info) {
            
            $training_detail_t = training_detail_t_model::select(
                'training_detail_t.*',
                'exercise_m.exercise_name'
            )
            ->leftJoin('exercise_m', function ($join) {
                $join->on('training_detail_t.user_id', '=', 'exercise_m.user_id')
                    ->on('training_detail_t.user_exercise_id', '=', 'exercise_m.user_exercise_id');
            })
            ->where('training_detail_t.user_id', $info->user_id)
            ->where('training_detail_t.user_training_count', $info->user_training_count)
            ->get();


            $info->training_detail_t = $training_detail_t;

        }

        return $training_history_t;

    }

    function training_history_save(Request $request)
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
            
            $user_training_count = 1;

            if(!is_null($training_history_t)){    

                if(is_null($training_history_t->end_datetime)){
                    $user_training_count = $training_history_t->user_training_count;
                }else{
                    $user_training_count = $training_history_t->user_training_count + 1;
                }                             
            }
            
            $table = training_history_t_model::where('user_training_count', $user_training_count)
            ->where('user_id', $user_id)
            ->first();

            

            if (empty($table)) {

                $table = new training_history_t_model;
                $table->user_id = $user_id;
                $table->user_training_count = $user_training_count;
                $table->user_gym_id = $request->user_gym_id;
                
                $table->start_datetime = $request->set_datetime;
                $table->created_by = $user_id;
                $table->created_at = now();

            }else{
                $table->end_datetime = $request->set_datetime;                
            }

            

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


    function training_detail_save(training_detail_t_request $request)
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
            
            $user_training_count = 1;
            

            if(!is_null($training_history_t)){    

                $user_training_count = $training_history_t->user_training_count;         
            }
            
            $user_training_detail_id = $request->user_training_detail_id;

            //0の場合は新規登録なので、詳細IDのMAX値を再取得
            if($user_training_detail_id == 0){

                // MAX(user_training_detail_id)を取得
                $maxDetailId = training_detail_t_model::where('user_training_count', $user_training_count)
                ->where('user_id', $user_id)
                ->max('user_training_detail_id');

                // 存在しない場合は1、存在する場合は+1
                $user_training_detail_id = $maxDetailId ? $maxDetailId + 1 : 1;

            }

            $table = training_detail_t_model::where('user_training_count', $user_training_count)
            ->where('user_training_detail_id', $user_training_detail_id)
            ->where('user_id', $user_id)
            ->first();            

            if (empty($table)) {

                $table = new training_detail_t_model;
                $table->user_id = $user_id;
                $table->user_training_count = $user_training_count;
                $table->user_training_detail_id = $user_training_detail_id;
           
                $table->created_by = $user_id;
                $table->created_at = now();           
            }

            $table->user_exercise_id = $request->user_exercise_id;
            $table->type = $request->type;

            if($request->type == 1){
                $table->reps = $request->reps;
                $table->weight = $request->weight;
            }else{
                $table->time = $request->time;
                
            }                      

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
