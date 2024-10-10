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
use App\Models\user_m_model;
// Model ↑

class dashboard_controller extends Controller
{
    function index(Request $request)
    {       
      
        // セッション情報取得
        $user_info = common::get_login_user_info();
        // セッション有無
        if (!$user_info->login_status) {
            return redirect(route('user.login'));
        }
        
        $demo = "";

        return view('user/screen/dashboard/index', compact('demo'));
       
     
    }

    function graph_test(Request $request)
    {        

        $process_branch = 1;

        if($process_branch == 1){

            //基本値
            $base_value1 = 50000;
            $base_value2 = 10000;
            
            
            // controllerで構築しブレイドに持っていく
            $month_array = ['2018/01', '2018/02', '2018/03', '2018/04', '2018/05', '2018/06', '2018/07'];
            //収入
            $income_array = [200000, 210000, 230000, 190000, 180000, 270000, 200000];
            //支出
            $spending_array = [190000, 180000, 150000, 190000, 190000, 230000, 100000];

            $max_value1 = 0;
            $minimum_value1 = 0;

            $difference_array = [];
            foreach ($month_array as $index => $info) {

            

                $max_set_value = 0;
                $minimum_set_value = 0;

                $income = intval($income_array[$index]);
                $spending = intval($spending_array[$index]);


                $difference = $income - $spending;

                $difference_array[] = $difference;

                
                if($income >= $spending){
                    //収入が多い場合
                    $max_set_value = $income;
                    $minimum_set_value = $spending;
                }else{
                    //支出が多い場合
                    $max_set_value = $spending;
                    $minimum_set_value = $income;
                }

                if($index == 0){                
                    $max_value1 = $max_set_value;
                    $minimum_value1 = $minimum_set_value;
                }


                if($max_set_value >= $max_value1){
                    $max_value1 = $max_set_value;
                }

                if($minimum_set_value <= $minimum_value1){
                    $minimum_value1 = $minimum_set_value;
                }
            }

            while (true) {
                $max_value1++;
                if ($max_value1 % $base_value1 == 0) {
                    break;
                }
            }


            while (true) {

                $minimum_value1--;

                if($minimum_value1 > 0){

                    if ($minimum_value1 % $base_value1 == 0) {
                        $minimum_value1 = $minimum_value1;
                        break;
                    }

                }else{

                    if (($minimum_value1 * -1) % $base_value1 == 0) {
                        $minimum_value1 = $minimum_value1;
                        break;
                    }

                }
            
            }





            $max_value2 = 0;
            $minimum_value2 = 0;

            foreach ($difference_array as $index => $difference) {

                if($max_value2 <= $difference){
                    $max_value2 = $difference;
                }

                if($minimum_value2 >= $difference){
                    $minimum_value2 = $difference;
                }

            }

            while (true) {
                $max_value2++;
                if ($max_value2 % $base_value1 == 0) {
                    break;
                }
            }
        
            while (true) {

                $minimum_value2--;

                if($minimum_value2 > 0){

                    if ($minimum_value2 % $base_value1 == 0) {
                        $minimum_value2 = $minimum_value2;
                        break;
                    }

                }else{

                    if (($minimum_value2 * -1) % $base_value1 == 0) {
                        $minimum_value2 = $minimum_value2;
                        break;
                    }

                }
            
            }

        


            $set_value_array = [];

            $set_value_array["base_value1"] = $base_value1;
            $set_value_array["max_value1"] = $max_value1;
            $set_value_array["minimum_value1"] = $minimum_value1;

            $set_value_array["base_value2"] = $base_value2;
            $set_value_array["max_value2"] = $max_value2;
            $set_value_array["minimum_value2"] = $minimum_value2;




        
            return view('user/screen/dashboard/graph_test1',
            compact('month_array'
                , 'income_array'
                , 'spending_array'
                , 'set_value_array'
                , 'difference_array'
                
            ));           

        }else{



            //画面から 
            $search_year = $request->search_year == null ?  "" : $request->search_year;
            $search_department_id = $request->search_department_id == null ?  "" : $request->search_department_id;
            $search_warning_time = $request->search_warning_time == null ?  false : true;
            $search_dead_time = $request->search_dead_time == null ?  false : true;

            
            //テストの為、初期値をセット
            $search_year = 2023;            
            $search_department_id = 1;
            $search_warning_time = true;
            $search_dead_time = false;

          
            $year_list = [];
            $start_year = 2015;
            $current_year = Carbon::now()->year;

            for ($year = $start_year; $year <= $current_year; $year++) {
                $year_list[] = (object)['year' => $year, 'display' => "{$year}年度"];
            }


            $months = [];
            $start_month = 4; // 4月スタートのため
            for ($i = 0; $i < 12; $i++) {

                $month_number = (($start_month + $i - 1) % 12 + 1);
                
                if($month_number < 4){
                    $month = ($search_year + 1) ."/" . sprintf("%02d", $month_number); // 月を0埋め
                    // $month = "2024/" . sprintf("%02d", $month_number); // 月を0埋め
                }else{
                    $month = $search_year ."/" . sprintf("%02d", $month_number); // 月を0埋め
                    // $month = "2023/" . sprintf("%02d", $month_number); // 月を0埋め
                }
                
                $months[] = $month;
            }


            
            $department_info = [];
            $department_info[]= (object)['department_id' => 1, "department_name" => 'ドライバー'];
            $department_info[]= (object)['department_id' => 2, "department_name" => '商事課'];

            //ダミーデータ作成  start
            $staff_info = [];
            $staff_info []= (object)['staff_id' => 1 ,'staff_name' => "社員A" , "rgba" => "rgba(219,39,91,0.5)", "department_id" => 2];
            $staff_info []= (object)['staff_id' => 2 ,'staff_name' => "社員B" , "rgba" => "rgba(130,201,169,0.5)", "department_id" => 1];
            $staff_info []= (object)['staff_id' => 3 ,'staff_name' => "社員C" , "rgba" => "rgba(255,183,76,0.5)", "department_id" => 1];
            $staff_info []= (object)['staff_id' => 4 ,'staff_name' => "社員D" , "rgba" => "rgba(48,47,43,0.5)", "department_id" => 2];
            $staff_info []= (object)['staff_id' => 5 ,'staff_name' => "社員E" , "rgba" => "rgba(236,223,43,0.5)", "department_id" => 2];
            $staff_info []= (object)['staff_id' => 6 ,'staff_name' => "社員F" , "rgba" => "rgba(87,119,47,0.5)", "department_id" => 1];

            $over_time_base = 0.5;
            foreach ($staff_info as $info) {

                $over_times = [];

                if(1 == 2){

                    for ($i = 0; $i < 12; $i++) {

                        $random = rand(10, 120);
                        $over_times[]= $random * $over_time_base;
                    }
    
                }else{

                    switch ($info->staff_id) {

                        case 1:
                            $over_times = [
                                25.5, 7.0, 41.5, 18.5, 55.0, 33.0, 12.0, 49.5, 20.5, 30.0, 77.5, 43.0
                            ];                        
                            break;
                    
                        case 2:
                            $over_times = [
                                9.5, 60.0, 26.0, 12.5, 38.5, 5.0, 70.0, 45.0, 8.5, 18.0, 33.5, 29.0
                            ];
                            break;
                    
                        case 3:
                            $over_times = [
                                14.5, 41.0, 25.5, 30.5, 22.5, 19.5, 54.0, 8.0, 35.0, 8.0, 13.5, 48.5
                            ];
                            break;
                    
                        case 4:
                            $over_times = [
                                21.0, 39.5, 62.0, 5.5, 77.0, 47.5, 14.0, 32.5, 56.5, 10.0, 28.0, 69.0
                            ];
                            break;
                    
                        case 5:
                            $over_times = [
                                33.0, 8.5, 54.0, 11.5, 19.0, 65.5, 7.0, 40.5, 57.5, 12.5, 72.0, 26.0
                            ];
                            break;
                    
                        case 6:
                            $over_times = [
                                52.5, 20.0, 30.5, 8.0, 75.0, 17.5, 44.5, 63.0, 5.5, 12.0, 70.5, 39.0
                            ];
                            break;
                    
                        default:
                            $over_times = [
                                21.0, 39.5, 62.0, 5.5, 77.0, 47.5, 14.0, 32.5, 56.5, 10.0, 28.0, 69.0
                            ];
                            break;
                    }

                }
                
                $info->over_times = $over_times;


                $department_name = "";
                foreach ($department_info as $item) {  
                    
                  if($item->department_id == $info->department_id){
                    $department_name = $item->department_name;
                  }
  
                }

                $info->department_name = $department_name;

            }

            //ダミーデータ作成  end
          
      
            $warning_time = 40;
            $dead_time = 60;
            $alert_info = (object)[
                'warning_time' => $warning_time                 
                , "dead_time" => $dead_time
            ];

            foreach ($staff_info as $key => $info) {


                $department_id = $info->department_id;
                $over_times = $info->over_times;

                
                $department_display_flg = true;
                //部署IDがセット時
                if($search_department_id != ""){
                    
                    //部署が一致しない場合
                    if($search_department_id != $info->department_id){
                        $department_display_flg = false;
                    }

                }
                          

                $over_time_display_flgs = [];
                foreach ($over_times as $index => $over_time) {

                    $over_time_display_flg = false;

                    if($search_warning_time && $search_dead_time){                      

                        if($over_time >= $alert_info->warning_time ){
                            $over_time_display_flg = true;
                        }
                       
                    }elseif(!$search_warning_time && $search_dead_time){

                        if($over_time >= $alert_info->dead_time ){
                            $over_time_display_flg = true;
                        }


                    }elseif($search_warning_time && !$search_dead_time){

                        if($over_time >= $alert_info->warning_time &&  $over_time < $alert_info->dead_time){
                            $over_time_display_flg = true;
                        }

                    }else{

                        $over_time_display_flg = true;

                    }            

                    $over_time_display_flgs[] = $over_time_display_flg;                             
                }

                // $over_time_display_flgsにtureが存在するかをチェック
                $has_true = in_array(true, $over_time_display_flgs);
              
                if(!$department_display_flg || !$has_true){
                    unset($staff_info[$key]);
                }

                
            }     

            

            return view('user/screen/dashboard/graph_test2',
            compact('months'
                , 'staff_info'
                , 'department_info'
                , 'alert_info'              
                , 'year_list'
                
            ));
            
        }


        
        
    }
}
