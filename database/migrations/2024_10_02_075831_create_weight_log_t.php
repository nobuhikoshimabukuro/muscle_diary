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
        
        if (Schema::hasTable('weight_log_t')) {
            // テーブルが存在していればリターン
            return;
        }

        Schema::create('weight_log_t', function (Blueprint $table) {

            $table
                ->bigIncrements('weight_log_id')
                ->comment('連番');

            $table
                ->unsignedBigInteger('user_id')                
                ->comment('ユーザーID');

            $table
                ->unsignedBigInteger('user_weight_log_id')                
                ->comment('ユーザー毎体重管理ID');
                
            $table
                ->decimal('weight', 12, 3)
                ->default(0.000)                
                ->comment('重さ(kg)');
                
            $table
                ->dateTime('measure_at')                
                ->comment('記録日時');
            

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

        DB::statement("ALTER TABLE weight_log_t COMMENT '体重履歴T'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('weight_log_t');
    }
};
