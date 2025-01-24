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

            common::set_after_login_url($request);
            return redirect(route('user.login'));
        }

        $user_id = $user_info->user_id;
        $keep_user_training_count = 0;
        
        //トレーニング記録取得                                           
        $training_history_t = training_history_t_model::
        select(
            'training_history_t.*',
            DB::raw('TIMEDIFF(training_history_t.end_datetime, training_history_t.start_datetime) as elapsed_time'),
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

        $gym_m = db_common::get_user_item($user_id,1);
                
        return view('user/screen/training/index', compact('training_history_t','keep_user_training_count','gym_m'));     
    }


    function detail(Request $request)
    {            

        // セッション情報取得
        $user_info = common::get_login_user_info();
        // セッション有無
        if (!$user_info->login_status) {

            common::set_after_login_url($request);
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
                DB::raw('TIMEDIFF(training_history_t.end_datetime, training_history_t.start_datetime) as elapsed_time'),
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
                                        
                    $table->end_datetime = now();
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
            DB::raw('TIMEDIFF(training_history_t.end_datetime, training_history_t.start_datetime) as elapsed_time'),
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

    function analysis(Request $request)
    {            

        // セッション情報取得
        $user_info = common::get_login_user_info();
        // セッション有無
        if (!$user_info->login_status) {

            common::set_after_login_url($request);
            return redirect(route('user.login'));
        }

        $user_id = $user_info->user_id;
        

        $start_date = "";
        $end_date = "";

        $branch = 4;
        if(isset($request->branch)){

            $branch = $request->branch;

            if(isset($request->start_date)){
                $start_date = $request->start_date;
            }

            if(isset($request->end_date)){
                $end_date = $request->end_date;
            }

        }else{            
            
            $now = Carbon::now();    

            $start_date = $now->startOfMonth()->format('Y-m-d');
            $end_date = $now->endOfMonth()->format('Y-m-d');


        }   

        $search_array = [
            "user_id" => $user_id
            ,"branch" => $branch
            ,"start_date" => $start_date
            ,"end_date" => $end_date
        ];

        $get_record_training_time = self::get_record_training_time($search_array); 
        return view('user/screen/training/analysis', compact('get_record_training_time'));     
    }



    function get_record_training_time($search_array)
    {    

        $user_id = $search_array["user_id"];
        $branch = $search_array["branch"];

        
        $with_sql = "            
            WITH base_data AS ( 
                SELECT
                    training_history_id
                    , user_id
                    , user_training_count
                    , user_gym_id
                    , start_datetime
                    , end_datetime
                    , DATE_FORMAT(end_datetime, '%Y%m%d') AS yyyymmdd
                    , TIME_TO_SEC(TIMEDIFF(end_datetime, start_datetime)) AS elapsed_seconds 
                FROM
                    training_history_t
            ) 
            , day_data AS ( 
                SELECT
                    user_id
                    , yyyymmdd
                    , SEC_TO_TIME(elapsed_seconds) AS elapsed_time
                    , CASE 
                        WHEN DAYOFWEEK(STR_TO_DATE(yyyymmdd, '%Y%m%d')) = 1 
                            THEN 'Mon' 
                        WHEN DAYOFWEEK(STR_TO_DATE(yyyymmdd, '%Y%m%d')) = 2 
                            THEN 'Tue' 
                        WHEN DAYOFWEEK(STR_TO_DATE(yyyymmdd, '%Y%m%d')) = 3 
                            THEN 'Wed' 
                        WHEN DAYOFWEEK(STR_TO_DATE(yyyymmdd, '%Y%m%d')) = 4 
                            THEN 'Thu' 
                        WHEN DAYOFWEEK(STR_TO_DATE(yyyymmdd, '%Y%m%d')) = 5 
                            THEN 'Fri' 
                        WHEN DAYOFWEEK(STR_TO_DATE(yyyymmdd, '%Y%m%d')) = 6 
                            THEN 'Sat' 
                        WHEN DAYOFWEEK(STR_TO_DATE(yyyymmdd, '%Y%m%d')) = 7 
                            THEN 'Sun' 
                        END AS day_of_week_jp 
                FROM
                    base_data
            ) 
            , week_data AS ( 
                SELECT
                    user_id
                    , MIN(yyyymmdd) AS start_date
                    , MAX(yyyymmdd) AS end_date
                    , YEAR (yyyymmdd) AS year
                    , WEEK(yyyymmdd, 1) AS week
                    , SEC_TO_TIME(SUM(elapsed_seconds)) AS elapsed_time 
                FROM
                    base_data 
                GROUP BY
                    user_id
                    , YEAR (yyyymmdd)
                    , WEEK(yyyymmdd, 1)
            ) 
            , month_data AS ( 
                SELECT
                    user_id
                    , 
                    LEFT (yyyymmdd, 6) AS yyyymm
                    , SEC_TO_TIME(SUM(elapsed_seconds)) AS elapsed_time 
                FROM
                    base_data 
                GROUP BY
                    user_id
                    , 
                    LEFT (yyyymmdd, 6)
            ) 
            , year_data AS ( 
                SELECT
                    user_id
                    , 
                    LEFT (yyyymmdd, 4) AS yyyy
                    , SEC_TO_TIME(SUM(elapsed_seconds)) AS elapsed_time 
                FROM
                    base_data 
                GROUP BY
                    user_id
                    , 
                    LEFT (yyyymmdd, 4)
            ) 
        ";
        
        $where_sql = "WHERE user_id = :user_id";
        $params = ['user_id' => $user_id]; 


        $select_sql = "";
        $order_sql = "";
        switch ($branch) {

            case 1:                
                //年単位
                $select_sql = "                
                    SELECT
                        user_id
                        , yyyy
                        , elapsed_time 
                    FROM
                        year_data           
                ";

                $order_sql .= " ORDER BY ";
                $order_sql .= " yyyy ";

                break;
            case 2:

                //月単位
                $select_sql = "                
                    SELECT
                        user_id
                        , yyyymm
                        , CONCAT(LEFT (yyyymm, 4), '/', RIGHT (yyyymm, 2)) AS formatted_yyyymm
                        , elapsed_time 
                    FROM
                        month_data
                ";


                if($search_array["start_date"] != ""){
                    $where_sql .= " AND yyyymm >=:start_yyyymm";  
                    $params['start_yyyymm'] = Carbon::parse($search_array["start_date"])->format('Ym');
                }


                if($search_array["end_date"] != ""){
                    $where_sql .= " AND yyyymm <=:end_yyyymm";  
                    $params['end_yyyymm'] = Carbon::parse($search_array["end_date"])->format('Ym');
                }

                $order_sql .= " ORDER BY ";
                $order_sql .= " yyyymm ";
                
                break;
            case 3:
                //週単位               

                $select_sql = "                
                    SELECT
                        user_id
                        , start_date
                        , DATE_FORMAT(STR_TO_DATE(start_date, '%Y%m%d'), '%Y/%m/%d') AS formatted_start_date
                        , end_date
                        , DATE_FORMAT(STR_TO_DATE(end_date, '%Y%m%d'), '%Y/%m/%d') AS formatted_end_date
                        , year
                        , week
                        , elapsed_time 
                    FROM
                        week_data                  
                ";


                $start_date = "";
                $end_date = "";
                if($search_array["start_date"] != ""){


                    $temporary_sql = $with_sql . $select_sql . "                    
                        WHERE
                            start_date <= :start_date 
                        ORDER BY
                            start_date DESC
                    
                    ";

                    $temporary_params['start_date'] = Carbon::parse($search_array["start_date"])->format('Ymd');
                    $temporary_record = DB::connection('mysql')->select($temporary_sql, $temporary_params);


                    if (!empty($temporary_record) && isset($temporary_record[0]->start_date)) {
                        $start_date = $temporary_record[0]->start_date;
                    }



                }

                if($search_array["end_date"] != ""){
                    
                    $temporary_sql = $with_sql . $select_sql . "                    
                        WHERE
                            end_date >= :end_date 
                        ORDER BY
                            end_date ASC
                    
                    ";

                    $temporary_params =[];
                    $temporary_params['end_date'] = Carbon::parse($search_array["end_date"])->format('Ymd');
                    $temporary_record = DB::connection('mysql')->select($temporary_sql, $temporary_params);

                    if (!empty($temporary_record) && isset($temporary_record[0]->end_date)) {
                        $end_date = $temporary_record[0]->end_date;
                    }
                }

                if($start_date != ""){

                    $where_sql .= " AND start_date >= :start_date ";    
                    $params['start_date'] = $start_date;

                }

                if($end_date != ""){

                    $where_sql .= " AND end_date <= :end_date ";    
                    $params['end_date'] = $end_date;

                }

         

                $order_sql .= " ORDER BY ";
                $order_sql .= " start_date ";

                break;
            case 4:
                //日単位

                $select_sql = "
                    SELECT
                        user_id
                        , yyyymmdd
                        , DATE_FORMAT(STR_TO_DATE(yyyymmdd, '%Y%m%d'), '%Y/%m/%d') AS formatted_yyyymmdd
                        , elapsed_time
                        , day_of_week_jp 
                    FROM
                        day_data
                ";           

                if($search_array["start_date"] != ""){
                    $where_sql .= " AND yyyymmdd >= :start_date ";    
                    $params['start_date'] = Carbon::parse($search_array["start_date"])->format('Ymd');
                }

                if($search_array["end_date"] != ""){
                    $where_sql .= " AND yyyymmdd <= :end_date ";    
                    $params['end_date'] = Carbon::parse($search_array["end_date"])->format('Ymd');
                }

                               

                $order_sql .= " ORDER BY ";
                $order_sql .= " yyyymmdd ";

                break;            
            default:
                
        }       
       

        $sql = $with_sql . $select_sql . $where_sql .$order_sql;

        Log::channel('sql_log')->info($sql);        

        $record = DB::connection('mysql')->select($sql, $params);

        $labels = [];
        $elapsed_times = [];

        $count = 0;
        $total_elapsed_time = 0;
        $ave_elapsed_time = "00:00:00";
        $min_elapsed_time = "00:00:00";
        $max_elapsed_time = "0";          
        $step_size = 0;
        
        foreach ($record as $index => $info) {

            

            $label = "";
            switch ($search_array["branch"]) {

                case 1:    
                    
                    $label = $info->yyyy;
                    break;
                case 2:

                    $label = $info->formatted_yyyymm;                    
                    break;
                case 3:
                    
                    $label = $info->formatted_start_date . "～" .$info->formatted_end_date;                    
                    break;
                case 4:

                    $label = $info->formatted_yyyymmdd;                    
                    break;            
                default:
            }
            
            $elapsed_time = common::time_to_seconds($info->elapsed_time);  
            
            $total_elapsed_time += $elapsed_time;


            if ($max_elapsed_time <= $elapsed_time || $index == 0) {
                $max_elapsed_time = $elapsed_time; // 最大値を更新
            }

            $labels[]= $label;
            $elapsed_times[]= $info->elapsed_time;

            $count = $index + 1;
        }

        // 平均体重を計算（小数点第3位まで）
        if ($count > 0) {

            $ave_elapsed_time = round($total_elapsed_time / $count); // 小数点第3位まで                                
            
            $ave_elapsed_time = common::seconds_to_time($ave_elapsed_time);
            $max_elapsed_time = common::seconds_to_time($max_elapsed_time);

            list($hours, $minutes, $seconds) = explode(':', $max_elapsed_time);

            $hours = (int)$hours;
            $minutes = (int)$minutes;
            $seconds = 0;

            if ($minutes >= 30 && $minutes <= 60) {
                $minutes = 0;
                $hours += 1;
            }else{
                $minutes = 30;
            }

            $max_elapsed_time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        

        $user_info = user_m_model::get_user_info($user_id);
        $return_array = [
            "datas" => ["labels" => $labels , "elapsed_times" => $elapsed_times]
            ,"summary" => [
                'user_name' => $user_info->user_name
                ,'count' => $count
                ,'min_elapsed_time' => $min_elapsed_time
                ,'max_elapsed_time' => $max_elapsed_time
                ,'ave_elapsed_time' => $ave_elapsed_time
                ,'step_size' => $step_size
            ]
        ];

        return $return_array;
    }

}
