<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class user_m_model extends Model
{
    use SoftDeletes;

    //コネクション名を指定
    protected $connection = 'mysql';
    protected $table = 'user_m';
    protected $primaryKey = 'user_id';

     // user_idでデータを取得するメソッド
     public static function get_user_info($user_id)
     {
         return self::where('user_id', $user_id)->first();
     }
}
