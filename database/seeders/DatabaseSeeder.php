<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
                'measurement_type' => 2,
                'bodyweight_flg' => 0,
                'display_flg' => 1,
                'display_order' => $index++,        
            ]
            ,
            [                
                'user_id' => 1,
                'user_exercise_id' => $index,                          
                'exercise_name' => "スクワット",
                'measurement_type' => 2,
                'bodyweight_flg' => 0,
                'display_flg' => 1,        
                'display_order' => $index++,        
            ]
            ,
            [                
                'user_id' => 1,
                'user_exercise_id' => $index,
                'exercise_name' => "ジョギング",    
                'measurement_type' => 1,     
                'bodyweight_flg' => 0,       
                'display_flg' => 1,        
                'display_order' => $index++,         
            ]
            ,
            [                
                'user_id' => 1,
                'user_exercise_id' => $index,
                'exercise_name' => "ディップス",    
                'measurement_type' => 2,     
                'bodyweight_flg' => 1,       
                'display_flg' => 1,        
                'display_order' => $index++,         
            ]

        ]);

        // DB::table('training_history_t')->insert([            
            
        //     [                
        //         'user_id' => 1,
        //         'user_training_count' => 1,                
        //         'user_gym_id' => 1,
        //         'start_datetime' => "2024/10/04 16:58:32",
        //         'end_datetime' => "2024/10/04 17:58:32"
        //     ]
        //     ,
        //     [                
        //         'user_id' => 1,
        //         'user_training_count' => 2,                
        //         'user_gym_id' => 1,
        //         'start_datetime' => "2024/10/05 16:58:32",
        //         'end_datetime' => null
        //     ]

        // ]);

    }
}
