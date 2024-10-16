<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('training_detail_t')) {
            // テーブルが存在していればリターン
            return;
        }

        Schema::create('training_detail_t', function (Blueprint $table) {

            $table
                ->bigIncrements('training_detail_id')
                ->comment('連番');

            $table
                ->unsignedBigInteger('user_id')                
                ->comment('ユーザーID');

            $table
                ->unsignedBigInteger('user_training_count')                
                ->comment('トレーニング回数:ユーザー毎');

            $table
                ->unsignedBigInteger('user_training_detail_id')                
                ->comment('詳細ID:トレーニング回数毎');
                
            $table
                ->integer('user_exercise_id')                
                ->comment('種目ID:ユーザー毎');

            $table
                ->integer('type')                
                ->comment('時間or回数:1 = 時間 , 2 = 回数');

            $table
                ->time('time')                
                ->nullable()
                ->comment('時間:hh:mm:ss');

            $table
                ->integer('reps')                
                ->nullable()
                ->comment('回数');

            $table
                ->decimal('weight', 12, 4)                
                ->nullable()
                ->comment('重さ(g)');

        
            $table
                ->dateTime('created_at')
                ->nullable()
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('作成日時:自動生成');

            $table
                ->integer('created_by')
                ->nullable()
                ->comment('作成者');

            $table
                ->dateTime('updated_at')
                ->nullable()
                ->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))
                ->comment('更新日時:自動生成');

            $table
                ->integer('updated_by')
                ->nullable()
                ->comment('更新者');

            $table
                ->dateTime('deleted_at')
                ->nullable()
                ->comment('削除日時');

            $table
                ->integer('deleted_by')
                ->nullable()
                ->comment('削除者');

        });

        DB::statement("ALTER TABLE training_detail_t COMMENT 'トレーニング詳細T'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_detail_t');
    }
};
