<?php

namespace App\Http\Controllers\table;

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

class weight_log_t_controller extends Controller
{
    function index(Request $request)
    {       
      
        $demo = "";

        return view('web/screen/weight_log/index', compact('demo'));
        
     
    }

    function save(Request $request)
    {       
      
        try{
            
            $operator = 0;
            // $table = weight_log_t_model::find($request->user_id);
            $table = weight_log_t_model::find(0);

            if (empty($table)) {

                $table = new weight_log_t_model;
                $table->created_by = $operator;
                $table->created_at = now();
            }


            $table->weight = $request->weight;
            $table->measure_at = $request->measure_at;

            $table->updated_by = $operator;
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
                "message" => "登録処理でエラーが発生しました。: " . $error_message,
            ];

           
        }
        
        return response()->json(['result_array' => $result_array]);     
    }

}
