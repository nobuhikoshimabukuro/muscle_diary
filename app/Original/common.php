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
use App\Models\user_m_model;
// Model ↑


class common
{
    public static function get_login_user_info()
    {

        $login_status = false;
        $user_id = "";
        $user_name = "";        

        if (session()->has('user_id')) {
            // セッションに'user_id'が存在する場合の処理

            // セッションに'user_id'が存在する場合の処理
            $user_id = session('user_id'); // セッションから user_id を取得
            $user_m = user_m_model::where('user_id', $user_id)->first();

            if (!is_null($user_m)) {

                $login_status = true;
                $user_id = $user_m->user_id;
                $user_name = $user_m->user_name;              
                session()->put(['user_id' => $user_id]);
                session()->put(['user_name' => $user_name]);
                
            }
        }

        $user_info = (object)[
            'login_status' => $login_status, 'user_id' => $user_id, 'user_name' => $user_name
        ];

        return $user_info;
    }

    public static function test()
    {
        // 初期値
        $return_value = 0;      
        

        return $return_value;
    
    }

}
