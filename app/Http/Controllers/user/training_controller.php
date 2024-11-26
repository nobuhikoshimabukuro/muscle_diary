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

            // 現在のURLを取得            
            $after_login_url = $request->fullUrl();
            session()->forget('after_login_url');
            session()->put(['after_login_url' => $after_login_url]);

            return redirect(route('user.login'));
        }

        $user_id = $user_info->user_id;
        $keep_user_training_count = 0;
        
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
        ->where('training_history_t.user_id', $user_id)
        ->orderBy('training_history_t.user_training_count', 'asc')->get();
    
        foreach ($training_history_t as $index => $info) {
        
            $info->gym_name  = $info->gym_name ? $info->gym_name : "";
            // start_datetimeとend_datetimeをスラッシュ形式で取得（NULLの場合はnullのまま）
            $info->start_datetime = $info->start_datetime ? Carbon::parse($info->start_datetime)->format('Y/m/d H:i:s') : "";
            $info->end_datetime = $info->end_datetime ? Carbon::parse($info->end_datetime)->format('Y/m/d H:i:s') : "";

            if(count($training_history_t) == ($index + 1) &&  $info->end_datetime == ""){
                $keep_user_training_count = $info->user_training_count;
            }
        }




        return view('user/screen/training/index', compact('training_history_t','keep_user_training_count'));     
    }


    function detail(Request $request)
    {            

        // セッション情報取得
        $user_info = common::get_login_user_info();
        // セッション有無
        if (!$user_info->login_status) {

            // 現在のURLを取得            
            $after_login_url = $request->fullUrl();
            session()->forget('after_login_url');
            session()->put(['after_login_url' => $after_login_url]);

            return redirect(route('user.login'));
        }

        $user_id = $user_info->user_id;          
        $user_training_count = $request->user_training_count;


        // 新しいモデルオブジェクトを作成
        $training_history_t = (object)array();
        $training_detail_t = (object)array();
        
 
        $data_type = 0;

        if($user_training_count == 0){           
            $training_history_t = (object)['start_datetime' => "" ,'user_training_count' => $user_training_count];
            $data_type = 1;

        }else{

            
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
            ->where('training_history_t.user_id', $user_id)
            ->where('training_history_t.user_training_count', $user_training_count)
            ->orderBy('training_history_t.user_training_count', 'desc')->first();

            if(!is_null($training_history_t)){                                  

                // start_datetimeとend_datetimeをスラッシュ形式で取得（NULLの場合はnullのまま）
                $training_history_t->start_datetime = $training_history_t->start_datetime ? Carbon::parse($training_history_t->start_datetime)->format('Y/m/d H:i:s') : "";
                $training_history_t->end_datetime = $training_history_t->end_datetime ? Carbon::parse($training_history_t->end_datetime)->format('Y/m/d H:i:s') : "";

                if( $training_history_t->end_datetime == ""){
                    $data_type = 2;
                }else{
                    $data_type = 3;
                }

                $training_detail_t = training_detail_t_model::select(
                    'training_detail_t.*',
                    'exercise_m.exercise_name'
                )
                ->leftJoin('exercise_m', function ($join) {
                    $join->on('training_detail_t.user_id', '=', 'exercise_m.user_id')
                        ->on('training_detail_t.user_exercise_id', '=', 'exercise_m.user_exercise_id');
                })
                ->where('training_detail_t.user_id', $user_id)
                ->where('training_detail_t.user_training_count', $user_training_count)
                ->get();

            }
        } 


        if($data_type == 0){            

            return redirect(route('user.training.index'));

        }

        
        
        $training_history_t->data_type = $data_type;


        $gym_m = db_common::get_user_item($user_id,1);
        $exercise_m = db_common::get_user_item($user_id,2);       

        return view('user/screen/training/detail', compact('training_history_t','training_detail_t','gym_m','exercise_m'));       
     
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
            
            $user_training_count = $request->user_training_count;
            $process = $request->process;
            
            $table = training_history_t_model::where('user_training_count', $user_training_count)
            ->where('user_id', $user_id)
            ->first();

            

            if (empty($table)) {
                
                $user_training_count = db_common::get_user_max_value($user_id,3);

                $table = new training_history_t_model;
                $table->user_id = $user_id;
                $table->user_training_count = $user_training_count;
                $table->user_gym_id = $request->user_gym_id;
                
                $table->start_datetime = $request->set_datetime;
                $table->created_by = $user_id;
                $table->created_at = now();

            }else{

                if($process == 2){
                    $table->end_datetime = $request->set_datetime;
                }
            }

            

            $table->updated_by = $user_id;
            $table->updated_at = now();

            // テーブル更新
            $table->save();

            $result_array = array(
                "result" => "success",
                "message" => "",
                "user_training_count" => $user_training_count,
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
                        
            $user_training_count = $request->user_training_count;            
            
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



    function record_sheet(Request $request)
    {            

        // セッション情報取得
        $user_info = common::get_login_user_info();
        // セッション有無
        if (!$user_info->login_status) {
            return redirect(route('user.login'));
        }

        $user_id = $user_info->user_id;
        $training_history_t = common::get_latest_training_history_t($user_id);
      

        return view('user/screen/training/record_sheet', compact('training_history_t'));       
     
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
        ->where('training_history_t.user_id', $user_id)
        ->orderBy('training_history_t.user_training_count', 'desc')->get();
      

        foreach ($training_history_t as $info) {


            $info->gym_name  = $info->gym_name ? $info->gym_name : "";

            // start_datetimeとend_datetimeをスラッシュ形式で取得（NULLの場合はnullのまま）
            $info->start_datetime = $info->start_datetime ? Carbon::parse($info->start_datetime)->format('Y/m/d H:i:s') : "";

            $info->end_datetime = $info->end_datetime ? Carbon::parse($info->end_datetime)->format('Y/m/d H:i:s') : "";
            
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

}
