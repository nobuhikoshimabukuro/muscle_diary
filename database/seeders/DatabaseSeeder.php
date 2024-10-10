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

        DB::table('training_history_t')->insert([            
            
            [                
                'user_id' => 1,
                'user_training_count' => 1,                
                'user_gym_id' => 1,
                'start_datetime' => "2024/10/04 16:58:32",
                'end_datetime' => "2024/10/04 17:58:32"
            ]
            ,
            [                
                'user_id' => 1,
                'user_training_count' => 2,                
                'user_gym_id' => 1,
                'start_datetime' => "2024/10/05 16:58:32",
                'end_datetime' => null
            ]

        ]);

    }
}
