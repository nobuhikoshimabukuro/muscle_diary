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


class common
{
    public static function login_check($mailaddress, $password)
    {
        $return_array = [];

        //処理1
        //メールアドレスでユーザーマスタを参照
        $user_m = user_m_model::where('mailaddress', $mailaddress)->first();
        if (is_null($user_m)) {

            $return_array = (object)[
                "result" => false
                ,"login_error_message" => "メアドがないよ"
            ];

            return $return_array;
        }

        //処理2
        //メールアドレスとパスワードでユーザーマスタを参照                     
        if (common::encryption($password) != $user_m->password) {

            $return_array = (object)[
                "result" => false
                ,"login_error_message" => "パスワードが一致しないよ"
            ];

            return $return_array;
        }

        //処理3
        //パスワード一致と判断しユーザー情報を返却用配列に格納する        
        $return_array = (object)[
            "result" => true
            , "user_m" => $user_m
        ];

        return $return_array;
    }

    public static function get_login_user_info()
    {

        $login_status = false;
        $user_id = "";
        $user_name = "";
        $mailaddress = "";

        if (session()->has('user_id')) {
            // セッションに'user_id'が存在する場合の処理

            // セッションに'user_id'が存在する場合の処理
            $user_id = session('user_id'); // セッションから user_id を取得
            $user_m = user_m_model::where('user_id', $user_id)->first();

            if (!is_null($user_m)) {

                $login_status = true;
                $user_id = $user_m->user_id;
                $user_name = $user_m->user_name;
                $mailaddress = $user_m->mailaddress;

              
                session()->put(['user_id' => $user_id]);
                session()->put(['user_name' => $user_name]);
                session()->put(['mailaddress' => $mailaddress]);                
            }
        }

        $user_info = (object)[
            'login_status' => $login_status, 'user_id' => $user_id, 'user_name' => $user_name
        ];

        return $user_info;
    }

    public static function get_latest_training_history_t($user_id)
    {

        //前回のトレーニング記録取得                                           
        $training_history_t = training_history_t_model::
        select(
            'training_history_t.*',
            'gym_m.gym_name'
        )
        ->leftJoin('gym_m', function ($join) {
            $join->on('gym_m.user_id', '=', 'training_history_t.user_id')
                ->on('gym_m.user_gym_id', '=', 'training_history_t.user_gym_id');
        })
        ->where('training_history_t.user_id', $user_id) // training_history_tのuser_idを使用
        ->orderBy('training_history_t.user_training_count', 'desc') // training_countの降順に並べる        
        ->first();

        return $training_history_t;

    }

    /**
    * 重さ変換処理
    * 1:kgからpound
    * 2:poundからkg    
    * ※小数点第4位まで
    */
    public static function weight_conversion($weight , $process_branch)
    {
        
        // 1ポンド = 453.592グラム
        $pound = 453.592;

        // 初期値
        $return_value = 0;

        switch ($process_branch) {

            // kgからpoundへの変換
            case 1:        
                // 1kg = 1000グラムなので、weightをグラムに変換してポンドにする
                $return_value = round(($weight * 1000) / $pound, 4);
                break;
            
            // poundからkgへの変換
            case 2:
                // 1ポンドをグラムに換算し、それをkgに変換
                $return_value = round(($weight * $pound) / 1000, 4);
                break;        

            // どのケースにも一致しなかった場合
            default:
                // エラーハンドリング等が必要ならここで行う
                break;
        }

        return $return_value;
    
    }


    public static function set_after_login_url(Request $request)
    {

        // 現在のURLを取得            
        $after_login_url = $request->fullUrl();
        session()->forget('after_login_url');
        session()->put(['after_login_url' => $after_login_url]);

        return true;

    }


    //※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
    //※本番稼働後は暗号化キーは絶対に変更してはダメ
    //※$encryption_key = 'muscle';
    //※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
    // 平文から暗号文
    public static function encryption($plain_text)
    {
        $encryption_key = self::get_encryption_key();

        $encrypted_text = openssl_encrypt($plain_text, 'AES-128-ECB', $encryption_key);

        return $encrypted_text;
    }
    
    // 暗号文から平文
    public static function decryption($encrypted_text)
    {
        $encryption_key = self::get_encryption_key();
      
        $plain_text = openssl_decrypt($encrypted_text, 'AES-128-ECB', $encryption_key);
       
        return $plain_text;
    }

    //暗号キー取得処理
    public static function get_encryption_key()
    {
        //※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
        //※本番稼働後は暗号化キーは絶対に変更してはダメ
        //※$encryption_key = 'muscle';
        //※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※
        $encryption_key = 'muscle';       
       
        return $encryption_key;
    }

}
