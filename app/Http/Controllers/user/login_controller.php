<?php

namespace App\Http\Controllers\user;

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

class login_controller extends Controller
{
    function login(Request $request){

        $demo = "";
        return view('user/screen/login', compact('demo'));       
     
    }

    // ログインチェック
    function login_check(Request $request)
    {
        // 画面入力値
        $mailaddress = $request->mailaddress;
        $password = $request->password;

        $result_array = common::login_check($mailaddress , $password);

        if ($result_array->result) {
            // ログイン成功時
            $user_m = $result_array["user_m"];                
            session()->put(['user_id' => $user_m->user_id]);

            if (session()->has('after_login_url')) {

                $after_login_url = session('after_login_url');
                session()->forget('after_login_url');
                
                return redirect($after_login_url);    

            }else{                
                return redirect(route('user.top'));
            }            

        } else {
            
            session()->flash('login_error_message', $result_array->login_error_message);
            return redirect(route('web.login'));            
        }
    }

    // ログアウト
    function logout(Request $request)
    {
        session()->flush();
        return redirect(route('user.index'));        
    }
}
