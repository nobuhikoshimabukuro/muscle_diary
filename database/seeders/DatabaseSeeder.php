<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Original\common;
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

        DB::table('gym_m')->insert([            
            
            [                
                'user_id' => 1,
                'user_gym_id' => 1,                                
                'gym_name' => "AF浦添店",                
                'display_flg' => 1,        
                'display_order' => 1,        
            ]
            ,
            [                
                'user_id' => 1,
                'user_gym_id' => 2,                                
                'gym_name' => "AF沖縄市店",                
                'display_flg' => 1,        
                'display_order' => 1,    
            ]

        ]);

        $index = 1;
        DB::table('exercise_m')->insert([            
            
            [                
                'user_id' => 1,
                'user_exercise_id' => $index,
                'exercise_name' => "ベンチプレス",                                
                'display_flg' => 1,
                'display_order' => $index++,        
            ]
            ,
            [                
                'user_id' => 1,
                'user_exercise_id' => $index,                          
                'exercise_name' => "スクワット",                
                'display_flg' => 1,        
                'display_order' => $index++,        
            ]
            ,
            [                
                'user_id' => 1,
                'user_exercise_id' => $index,
                'exercise_name' => "ジョギング",                    
                'display_flg' => 1,        
                'display_order' => $index++,         
            ]
            ,
            [                
                'user_id' => 1,
                'user_exercise_id' => $index,
                'exercise_name' => "ディップス",                    
                'display_flg' => 1,        
                'display_order' => $index++,         
            ]

        ]);


        $index = 1;
        $user_id = 1;
        $dif = 0.131;
        $weight = 90.564;
        $goal = 80;
        $measure_at1 = Carbon::createFromFormat('YmdHis', '20231001090912'); // 初期値を設定
        $measure_at2 = Carbon::createFromFormat('YmdHis', '20231001171758'); // 初期値を設定
        
        while (true) {
        
            $randomNumber = rand(1, 3);
        
            if ($randomNumber == 3) {                
                $weight = $weight + $dif;
            } else {
                $weight = $weight - $dif;
            }
        
            DB::table('weight_log_t')->insert([
                [
                    'user_id' => $user_id,
                    'user_weight_log_id' => $index++,                                
                    'weight' => $weight,                
                    'measure_at' => $measure_at1->format('Y-m-d H:i:s'), // 日時をフォーマット
                ]        
            ]);


            $randomNumber = rand(1, 3);
        
            if ($randomNumber == 3) {                
                $weight = $weight + $dif;
            } else {
                $weight = $weight - $dif;
            }
        
            DB::table('weight_log_t')->insert([
                [
                    'user_id' => $user_id,
                    'user_weight_log_id' => $index++,                                
                    'weight' => $weight,                
                    'measure_at' => $measure_at2->format('Y-m-d H:i:s'), // 日時をフォーマット
                ]        
            ]);

              // 1日加算
            $measure_at1->addDay();
            $measure_at2->addDay();
        
            if ($weight < $goal) {
                break; 
            }
        }

        
        $index = 1;
        $user_id = 2;
        $dif = 0.091;
        $weight = 70.422;
        $goal = 59;
        $measure_at1 = Carbon::createFromFormat('YmdHis', '20231001090858'); // 初期値を設定
        $measure_at2 = Carbon::createFromFormat('YmdHis', '20231001181902'); // 初期値を設定
        
        while (true) {
        
            $randomNumber = rand(1, 3);
        
            if ($randomNumber == 3) {                
                $weight = $weight + $dif;
            } else {
                $weight = $weight - $dif;
            }
        
            DB::table('weight_log_t')->insert([
                [
                    'user_id' => $user_id,
                    'user_weight_log_id' => $index++,                                
                    'weight' => $weight,                
                    'measure_at' => $measure_at1->format('Y-m-d H:i:s'), // 日時をフォーマット
                ]        
            ]);


            $randomNumber = rand(1, 3);
        
            if ($randomNumber == 3) {                
                $weight = $weight + $dif;
            } else {
                $weight = $weight - $dif;
            }
        
            DB::table('weight_log_t')->insert([
                [
                    'user_id' => $user_id,
                    'user_weight_log_id' => $index++,                                
                    'weight' => $weight,                
                    'measure_at' => $measure_at2->format('Y-m-d H:i:s'), // 日時をフォーマット
                ]        
            ]);

              // 1日加算
            $measure_at1->addDay();
            $measure_at2->addDay();
        
            if ($weight < $goal) {
                break; 
            }
        }

        

    }
}
