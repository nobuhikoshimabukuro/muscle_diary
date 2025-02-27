<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_m')->insert([            
            
            [                
                'user_name' => '1111さん',
                'mailaddress' => '1111@mail.com',                
                'password' => common::encryption('1111')
            ]
            ,
            [                
                'user_name' => '2222さん',
                'mailaddress' => '2222@mail.com',                
                'password' => common::encryption('2222')
            ]

        ]);

        $user_id = 1;
        $index = 1;
        DB::table('gym_m')->insert([            
            
            [                
                'user_id' => $user_id,
                'user_gym_id' => $index,                                
                'gym_name' => "AF浦添店",                
                'display_flg' => 1,
                'display_order' => $index++,        
            ]
            ,
            [                
                'user_id' => $user_id,
                'user_gym_id' => $index,                                
                'gym_name' => "AF沖縄市店",                
                'display_flg' => 1,
                'display_order' => $index++,    
            ]
            ,
            [                
                'user_id' => $user_id,
                'user_gym_id' => $index,                                
                'gym_name' => "AF北谷店",                
                'display_flg' => 1,
                'display_order' => $index++,    
            ]

        ]);

        $user_id = 2;
        $index = 1;
        DB::table('gym_m')->insert([            
            
            [                
                'user_id' => $user_id,
                'user_gym_id' => $index,                                
                'gym_name' => "沖縄市営",                
                'display_flg' => 1,        
                'display_order' => $index++,        
            ]
            ,
            [                
                'user_id' => $user_id,
                'user_gym_id' => $index,                                
                'gym_name' => "県総合",                
                'display_flg' => 1,        
                'display_order' => $index++,    
            ]

        ]);


        $user_id = 1;
        $index = 1;
        DB::table('exercise_m')->insert([            
            
            [                
                'user_id' => $user_id,
                'user_exercise_id' => $index,
                'exercise_name' => "ベンチプレス",                                
                'display_flg' => 1,
                'display_order' => $index++,        
            ]
            ,
            [                
                'user_id' => $user_id,
                'user_exercise_id' => $index,                          
                'exercise_name' => "スクワット",                
                'display_flg' => 1,        
                'display_order' => $index++,        
            ]
            ,
            [                
                'user_id' => $user_id,
                'user_exercise_id' => $index,
                'exercise_name' => "ジョギング",                    
                'display_flg' => 1,        
                'display_order' => $index++,         
            ]
            ,
            [                
                'user_id' => $user_id,
                'user_exercise_id' => $index,
                'exercise_name' => "ディップス",                    
                'display_flg' => 1,        
                'display_order' => $index++,         
            ]

        ]);

        $user_id = 2;
        $index = 1;
        DB::table('exercise_m')->insert([            
            
            [                
                'user_id' => $user_id,
                'user_exercise_id' => $index,
                'exercise_name' => "ウォーキング",                                
                'display_flg' => 1,
                'display_order' => $index++,        
            ]
            ,
            [                
                'user_id' => $user_id,
                'user_exercise_id' => $index,                          
                'exercise_name' => "ジョギング",                
                'display_flg' => 1,        
                'display_order' => $index++,        
            ]
            ,
            [                
                'user_id' => $user_id,
                'user_exercise_id' => $index,
                'exercise_name' => "マラソン",                    
                'display_flg' => 1,        
                'display_order' => $index++,         
            ]          

        ]);


        
        $user_id = 1;
        $dif = 0;
        $weight = 80.564;       
        // 今日の日付を取得
        $today = Carbon::today();

        // YYYYMMDD形式にフォーマット
        $formattedDate = $today->format('Ymd');

        $measure_at1 = Carbon::createFromFormat('YmdHis', $formattedDate . '090912'); // 初期値を設定
        $measure_at2 = Carbon::createFromFormat('YmdHis', $formattedDate . '171758'); // 初期値を設定        
        

        $index = 2200;
        $loop = ($index / 2) ;
        for ($i = 1; $i <= $loop; $i++) {

            $randomNumber = rand(1, 5);
        
            $dif = rand(0, 1000) / 10000; 
            if ($randomNumber % 2 != 0) {
                $weight = $weight + $dif;                
            } else {
                $weight = $weight - $dif;                
            }

            DB::table('weight_log_t')->insert([
                [
                    'user_id' => $user_id,
                    'user_weight_log_id' => $index--,                                
                    'weight' => $weight,                
                    'measure_at' => $measure_at1->format('Y-m-d H:i:s'), // 日時をフォーマット
                ]        
            ]);


            $randomNumber = rand(1, 5);
            $dif = rand(0, 1000) / 10000; 
            if ($randomNumber % 2 != 0) {
                $weight = $weight + $dif;                
            } else {
                $weight = $weight - $dif;                
            }

            DB::table('weight_log_t')->insert([
                [
                    'user_id' => $user_id,
                    'user_weight_log_id' => $index--,                                
                    'weight' => $weight,                
                    'measure_at' => $measure_at2->format('Y-m-d H:i:s'), // 日時をフォーマット
                ]        
            ]);


            // 1日マイナス
            $measure_at1 = $measure_at1->subDay();
            $measure_at2 = $measure_at2->subDay();

            
        }



        $user_id = 2;
        $dif = 0;
        $weight = 60.564;       
        // 今日の日付を取得
        $today = Carbon::today();

        // YYYYMMDD形式にフォーマット
        $formattedDate = $today->format('Ymd');

        $measure_at1 = Carbon::createFromFormat('YmdHis', $formattedDate . '090912'); // 初期値を設定
        $measure_at2 = Carbon::createFromFormat('YmdHis', $formattedDate . '171758'); // 初期値を設定        
        

        $index = 2200;
        $loop = ($index / 2) ;
        for ($i = 1; $i <= $loop; $i++) {

            $randomNumber = rand(1, 5);
        
            $dif = rand(0, 500) / 10000; 
            if ($randomNumber % 2 != 0) {
                $weight = $weight + $dif;                
            } else {
                $weight = $weight - $dif;                
            }

            DB::table('weight_log_t')->insert([
                [
                    'user_id' => $user_id,
                    'user_weight_log_id' => $index--,                                
                    'weight' => $weight,                
                    'measure_at' => $measure_at1->format('Y-m-d H:i:s'), // 日時をフォーマット
                ]        
            ]);


            $randomNumber = rand(1, 5);
            $dif = rand(0, 300) / 10000; 
            if ($randomNumber % 2 != 0) {
                $weight = $weight + $dif;                
            } else {
                $weight = $weight - $dif;                
            }

            DB::table('weight_log_t')->insert([
                [
                    'user_id' => $user_id,
                    'user_weight_log_id' => $index--,                                
                    'weight' => $weight,                
                    'measure_at' => $measure_at2->format('Y-m-d H:i:s'), // 日時をフォーマット
                ]        
            ]);


            // 1日マイナス
            $measure_at1 = $measure_at1->subDay();
            $measure_at2 = $measure_at2->subDay();
            
        }




        $user_id = 1;
        $dif = 0;        
        $today = Carbon::today();        
        $formattedDate = $today->copy()->subDay(); // $today を変更せずに減算

        $index = 1100;        
        $loop = $index;
        for ($i = 1; $i <= $loop; $i++) {

            $user_gym_id = rand(1, 2);

            $hour = sprintf('%02d', rand(0, 23));
            $minutes = sprintf('%02d', rand(0, 59));
            $second = sprintf('%02d', rand(0, 59));
            
       
            // 日付と時刻を結合して開始日時を生成
            $start_datetime = Carbon::createFromFormat(
                'YmdHis', 
                $formattedDate->format('Ymd') . $hour . $minutes . $second
            );

            $add_hour = rand(1, 2);
            $add_minutes = rand(0, 33);
            $add_second = rand(0, 59);            
            $end_datetime = $start_datetime->copy()->addHours($add_hour)->addMinutes($add_minutes)->addSeconds($add_second); 
       
            try{

                DB::table('training_history_t')->insert([
                    [
                        'training_history_id' => $index,
                        'user_id' => $user_id,
                        'user_training_count' => $index--,                                
                        'user_gym_id' => $user_gym_id,                
                        'start_datetime' => $start_datetime->format('Y-m-d H:i:s'),
                        'end_datetime' => $end_datetime->format('Y-m-d H:i:s'),
                    ]        
                ]);
    
                // 1日マイナス
                $formattedDate = $formattedDate->subDay();        

            }catch(Exception $e){


            }
              
        }


    }
}
