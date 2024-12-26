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

        // システム日付を基準に取得
        $today = Carbon::now();
        $today = Carbon::createFromFormat('Ymd', '20241001');
        $startOfMonth = $today->startOfMonth()->format('Ymd');

        $search_array = [
            "user_id" => $user_id
            ,"branch" => $request->branch == null ?  1 : $request->branch
            ,"start_date" => $request->start_date == null ? $today->startOfMonth()->format('Ymd') : $request->start_date            
            ,"end_date" => $request->end_date == null ? $today->endOfMonth()->format('Ymd') : $request->end_date
        ];

        $get_record = self::get_record($search_array);        

        return view('user/screen/weight_log_t/index', compact('get_record' , 'search_array'),);

    }


    function get_record($search_array)
    {    
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
        $params = ['user_id' => $search_array["user_id"]]; 


        $select_sql = "";
        switch ($search_array["branch"]) {
            case 1:
                
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

                // 月初と月末の日付を取得
                // 日付を Carbon オブジェクトに変換してからフォーマットする                

                $where_sql .= " AND ";
                $where_sql .= " yyyymmdd >= :start_date AND yyyymmdd <= :end_date";
                $params['start_date'] = Carbon::parse($search_array["start_date"])->format('Ymd');
                $params['end_date'] = Carbon::parse($search_array["end_date"])->format('Ymd');

                break;
            case 2:

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

                // 月初と月末の日付を取得
                $start_date = Carbon::parse($search_array["start_date"])->format('Ymd');
                $end_date = Carbon::parse($search_array["end_date"])->format('Ymd');

                $where_sql .= " AND ";
                $where_sql .= " (";
                $where_sql .= " (:start_date1 <= start_date AND :end_date1 >= start_date)";
                $where_sql .= " OR ";
                $where_sql .= " (:start_date2 <= end_date AND :end_date2 >= end_date)";
                $where_sql .= " )";

                
                  
                // パラメータに start_date と end_date を追加
                $params['start_date1'] = $start_date;
                $params['start_date2'] = $start_date;
                $params['end_date1'] = $end_date;
                $params['end_date2'] = $end_date;
                
                
                break;
            case 3:
                
                $select_sql = "                
                    SELECT
                        user_id
                        , yyyymm
                        , CONCAT(LEFT (yyyymm, 4), '/', RIGHT (yyyymm, 2)) AS formatted_yyyymm
                        , weight 
                    FROM
                        month_data
                ";

                $where_sql .= " AND LEFT(yyyymm, 4) =:yyyy";   
                                
                $params['yyyy'] = Carbon::parse($search_array["start_date"])->format('Y');

                break;
            case 4:
                
                $select_sql = "                
                    SELECT
                        user_id
                        , yyyy
                        , weight 
                    FROM
                        year_data           
                ";

                break;            
            default:
                
        }       
       

        $sql = $with_sql . $select_sql . $where_sql;

        Log::channel('sql_log')->info($sql);        

        $record = DB::connection('mysql')->select($sql, $params);

        $labels = [];
        $weights = [];

        $count = 0;
        $total_weight = 0;
        $min_weight = 0;
        $max_weight = 0;
        $ave_weight = 0;
        //割算用の値を設定
        $numerator = 2;
        foreach ($record as $index => $info) {

            $weight = $info->weight;          
            $label = "";
            switch ($search_array["branch"]) {
                case 1:
            
                    $label = $info->formatted_yyyymmdd;
                    $numerator = 2;
                    break;
                case 2:
                    $label = $info->formatted_start_date . "～";
                    $numerator = 5;
                    break;
                case 3:
                    
                    $label = $info->formatted_yyyymm;
                    $numerator = 10;
                    break;
                case 4:
                    
                    $label = $info->yyyy;
                    $numerator = 10;
                    break;            
                default:                    
            }

            $total_weight += $weight; // 合計体重を加算

            if ($min_weight >= $weight ||  $index == 0 ) {
                $min_weight = $weight; // 最小値を更新
            }

            if ($max_weight <= $weight ||  $index == 0) {
                $max_weight = $weight; // 最大値を更新
            }

            $labels[]= $label;
            $weights[]= $weight;

            $count = $index + 1;
        }

        // 平均体重を計算（小数点第3位まで）
        if ($count > 0) {
            $ave_weight = round($total_weight / $count, 3); // 小数点第3位まで
        } else {
            $ave_weight = 0; // データがない場合は平均体重を0
        }


        while (true) {

            // 小数点以下を切り捨て
            $min_weight = floor($min_weight);
            $min_weight -= 1;

            // 設定した値で割り切れるまでループ
            if ($min_weight % $numerator == 0) {
                break;
            }            
        }

        while (true) {

            // 小数点以下を切り捨て
            $max_weight = floor($max_weight);
            $max_weight += 1;

            // 設定した値で割り切れるまでループ
            if ($max_weight % $numerator == 0) {
                break;
            }            
        }

        // $step_size を計算する
        $step_size = ceil(($max_weight - $min_weight) / 10);



        $return_array = [
            "datas" => ["labels" => $labels , "weights" => $weights]
            ,"summary" => [
                'count' => $count 
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
