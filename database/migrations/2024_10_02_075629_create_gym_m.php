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

      
        if (Schema::hasTable('gym_m')) {
            // テーブルが存在していればリターン
            return;
        }

        Schema::create('gym_m', function (Blueprint $table) {

            $table
                ->bigIncrements('gym_id')
                ->comment('連番');

            $table
                ->unsignedBigInteger('user_id')                
                ->comment('ユーザーID');

            $table
                ->unsignedBigInteger('user_gym_id')                
                ->comment('ユーザー毎ジムID');
                
            $table
                ->string('gym_name',1000)       
                ->comment('ジム名');
                
            $table
                ->integer('display_flg')
                ->default(1)
                ->comment('表示フラグ:0 = 非表示 , 1 = 表示');

            $table
                ->integer('display_order')
                ->default(1)
                ->comment('表示順');

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

        DB::statement("ALTER TABLE gym_m COMMENT 'ジムM'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gym_m');
    }
};
