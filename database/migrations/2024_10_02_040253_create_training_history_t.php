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
       
        if (Schema::hasTable('training_history_t')) {
            // テーブルが存在していればリターン
            return;
        }

        Schema::create('training_history_t', function (Blueprint $table) {

            $table
                ->bigIncrements('training_history_id')
                ->comment('連番');

            $table
                ->unsignedBigInteger('user_id')                
                ->comment('ユーザーID');               

            $table
                ->unsignedBigInteger('training_count')                
                ->comment('トレーニング回数:ユーザー毎');

            $table
                ->unsignedBigInteger('user_gym_id')                
                ->comment('ジムID:ユーザー毎');

            $table
                ->dateTime('start_datetime')
                ->nullable()
                ->comment('開始日時');

            $table
                ->dateTime('end_datetime')
                ->nullable()
                ->comment('終了日時');

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

        DB::statement("ALTER TABLE training_history_t COMMENT 'トレーニング履歴T'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_history_t');
    }
};
