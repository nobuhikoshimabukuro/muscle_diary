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

            //ログイン後のURLをセットする
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

            $get_limit_date = self::get_limit_date($user_id);
            // システム日付を基準に取得
            
            if($get_limit_date["end_date"] == "")
            {
                $now = Carbon::now();            
            }else{
                $now = Carbon::parse($get_limit_date["end_date"]);              
            }
            
            $start_date = $now->startOfMonth()->format('Y-m-d');
            $end_date = $now->endOfMonth()->format('Y-m-d');


        }   

        $search_array = [
            "user_id" => $user_id
            ,"branch" => $branch
            ,"start_date" => $start_date
            ,"end_date" => $end_date
        ];

        $get_record = self::get_record($search_array);        

        return view('user/screen/weight_log_t/index', compact('get_record' , 'search_array'));

    }

    function get_limit_date($user_id)
    {    

        $start_date = "";
        $end_date = "";

        $dates = weight_log_t_model::where('user_id', $user_id)
        ->selectRaw('MIN(measure_at) as start_date, MAX(measure_at) as end_date')
        ->first();
    
        if(!is_null($dates)){

            $start_date = Carbon::parse($dates->start_date)->format('Y-m-d');  
            $end_date = Carbon::parse($dates->end_date)->format('Y-m-d');  
        }
        

        return ["start_date" => $start_date , "end_date" => $end_date];
    }

    function get_record($search_array)
    {    

        $user_id = $search_array["user_id"];
        $branch = $search_array["branch"];

        
        $with_sql = "            
            WITH base_data AS ( 
                SELECT
                    user_id
                    , yyyymmdd
                    , TRUNCATE (AVG(weight), 3) AS weight 
                FROM
                    ( 
                        SELECT
                            user_id
                            , DATE_FORMAT(measure_at, '%Y%m%d') AS yyyymmdd
                            , weight 
                        FROM
                            weight_log_t 
                        ORDER BY
                            user_id
                            , measure_at
                    ) WORK 
                GROUP BY
                    user_id
                    , yyyymmdd
            ) 
            , day_data AS ( 
                SELECT
                    user_id
                    , yyyymmdd
                    , TRUNCATE (weight, 3) AS weight
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
                    , TRUNCATE (AVG(weight), 3) AS weight 
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
                    , TRUNCATE (AVG(weight), 3) AS weight 
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
                    , TRUNCATE (AVG(weight), 3) AS weight 
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
                        , weight 
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
                        , weight 
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
                        , weight 
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
                        , weight
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
        $weights = [];

        $count = 0;
        $total_weight = 0;
        $min_weight = 0;
        $max_weight = 0;
        $ave_weight = 0;
        $step_size = 0;
        //割算用の値を設定
        $numerator = 1;
        foreach ($record as $index => $info) {

            $weight = $info->weight;          
            $label = "";
            switch ($search_array["branch"]) {

                case 1:    
                    
                    $label = $info->yyyy;
                    $numerator = 1;
                    
                    break;
                case 2:

                    $label = $info->formatted_yyyymm;
                    $numerator = 1;
                    
                    break;
                case 3:
                    
                    $label = $info->formatted_start_date . "～" .$info->formatted_end_date;
                    $numerator = 5;
                    
                    break;
                case 4:

                    $label = $info->formatted_yyyymmdd;
                    $numerator = 2;                   
                    
                    break;            
                default:
            }

            $total_weight += $weight; // 合計体重を加算

            if ($min_weight >= $weight || $index == 0 ) {
                $min_weight = $weight; // 最小値を更新
            }

            if ($max_weight <= $weight || $index == 0) {
                $max_weight = $weight; // 最大値を更新
            }

            $labels[]= $label;
            $weights[]= $weight;

            $count = $index + 1;
        }

        // 平均体重を計算（小数点第3位まで）
        if ($count > 0) {

            $ave_weight = round($total_weight / $count, 3); // 小数点第3位まで  
            $min_weight = floor($min_weight);
            $max_weight = floor($max_weight) + 1;           
    
            while (true) {                
                
    
                // 設定した値で割り切れるまでループ
                if ($min_weight % 2 == 0) {
                    break;
                }            
                $min_weight -= 1;
            }

            while (true) {                
                   
                
                if ($max_weight % 2 == 0) {
                    break;
                }            

                $max_weight += 1;
            }

            // $step_size を計算する
            $step_size = round(($max_weight - $min_weight) / 10, 0); // 小数点第3位まで
                    
            
        }

        

        $user_info = user_m_model::get_user_info($user_id);
        $return_array = [
            "datas" => ["labels" => $labels , "weights" => $weights]
            ,"summary" => [
                'user_name' => $user_info->user_name
                ,'count' => $count
                ,'min_weight' => $min_weight
                ,'max_weight' => $max_weight
                ,'ave_weight' => $ave_weight
                ,'step_size' => $step_size
            ]
        ];

        return $return_array;
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
