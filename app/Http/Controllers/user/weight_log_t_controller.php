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

            // 現在のURLを取得            
            $after_login_url = $request->fullUrl();
            session()->forget('after_login_url');
            session()->put(['after_login_url' => $after_login_url]);

            return redirect(route('user.login'));
        }

        $user_id = $user_info->user_id;

        $sql = "
        
        WITH yyyymmdd_data AS ( 
            SELECT
                user_id
                , yyyymmdd
                , AVG(weight) AS weight 
            FROM
                ( 
                    SELECT
                        user_id
                        , DATE_FORMAT(measure_at, '%Y%m%d') AS yyyymmdd
                        , weight 
                    FROM
                        weight_log_t
                ) WORK 
            GROUP BY
                user_id
                , yyyymmdd
        ) 
        , yyyymm_data AS ( 
            SELECT
                user_id
                , 
                LEFT (yyyymmdd, 6) AS yyyymm
                , AVG(weight) AS weight 
            FROM
                yyyymmdd_data 
            GROUP BY
                user_id
                , 
                LEFT (yyyymmdd, 6)
        ) 
        , week_data AS ( 
            SELECT
                user_id
                , MIN(yyyymmdd) AS start_date
                , MAX(yyyymmdd) AS end_date
                , YEAR (yyyymmdd) AS year
                , WEEK(yyyymmdd, 1) AS week
                , AVG(weight) AS weight 
            FROM
                yyyymmdd_data 
            GROUP BY
                user_id
                , YEAR (yyyymmdd)
                , WEEK(yyyymmdd, 1)                     -- 週番号で区切る
        )        
       
        
        ";

        $select_sql = "";
        $where_sql = "";        
        $branch = 1;
        if($branch == 1){

            $select_sql = "

            SELECT
                * 
            FROM
                yyyymmdd_data 
            
            ";


            // システム日付を基準に取得
            $today = Carbon::now();

            // 月初と月末の日付を取得
            $startOfMonth = $today->startOfMonth()->format('Ymd');
            $endOfMonth = $today->endOfMonth()->format('Ymd');

            $where_sql = " AND yyyymmdd >=" .$startOfMonth; 
            $where_sql .= " AND yyyymmdd <=" .$endOfMonth; 

        }else if($branch == 2){

            $select_sql = "                
                SELECT
                    * 
                FROM
                    yyyymm_data            
            ";


        }else if($branch == 3){

            $select_sql = "                
                SELECT
                    * 
                FROM
                    week_data           
            ";

        }


       



        $sql .= $select_sql . "WHERE user_id = " . $user_id;

        $sql .= $where_sql;

        Log::channel('sql_log')->info($sql);

        $info = DB::connection('mysql')->select($sql);

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
