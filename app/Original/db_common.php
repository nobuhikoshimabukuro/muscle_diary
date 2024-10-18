<?php

namespace App\Original;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// controller作成時ここからコピー↓
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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


class db_common
{   

    /**
    * ユーザー毎の最大値取得処理
    * 1:user_gym_id
    * 2:user_weight_log_id
    * 3:user_training_count
    * 4:user_exercise_id    
    */
    public static function get_user_max_value($user_id , $process_branch)
    {
        // 初期値
        $return_value = 1;      
    
        // 最大値を取得する変数
        $max_value = null;  

        switch ($process_branch) {

            case 1:        
                // データが存在する場合は最大の user_gym_id を取得
                $max_value = gym_m_model::where('user_id', $user_id)->withTrashed()->max('user_gym_id');
                break;
            
            case 2:
                // データが存在する場合は最大の user_weight_log_id を取得
                $max_value = weight_log_t_model::where('user_id', $user_id)->withTrashed()->max('user_weight_log_id');
                break;        

            case 3:                
                // データが存在する場合は最大の user_training_count を取得
                $max_value = training_history_t_model::where('user_id', $user_id)->withTrashed()->max('user_training_count');
                break;

            case 4:                
                // データが存在する場合は最大の user_exercise_id を取得
                $max_value = exercise_m_model::where('user_id', $user_id)->withTrashed()->max('user_exercise_id');
                break;
        
            default:
                // どのケースにも一致しなかった場合
                break;
        }



        // 最大値が存在する場合、その値に1を加えて返す
        if (!is_null($max_value)) {           
            $return_value = $max_value + 1;
        }
    
        return $return_value;
    }

    /**
    * ユーザー毎の情報取得処理
    * 1:ジム情報
    * 2:種目情報
    * 3:user_training_count
    * 4:user_exercise_id    
    */
    public static function get_user_item($user_id , $process_branch)
    {
        // 初期値
        $return_object = array();

        switch ($process_branch) {

            case 1:        
              
                $return_object = gym_m_model::where('user_id', $user_id)
                ->where('display_flg', 1)
                ->orderBy('display_order', 'asc') 
                ->get();
                break;
            
            case 2:

                $return_object = exercise_m_model::where('user_id', $user_id)
                ->where('display_flg', 1)
                ->orderBy('display_order', 'asc') 
                ->get();
                break;        

            default:
                // どのケースにも一致しなかった場合
                break;
        }

        return $return_object;
    }

    
}
