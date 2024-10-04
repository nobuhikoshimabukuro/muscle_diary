@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'weight_management')  

@endsection
@section('content')

<style>

</style>

<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">

    <div class="card col-12 col-sm-10 col-md-9 col-lg-8 col-xl-7">
      
      <table class="table">
        <tr>
          <td>
            <div id="timer"></div>
          </td>
        </tr>       
      </table>

    </div>

  </div> 

</div>


{{-- 再ログインモーダルの読み込み --}}
@include('user/common/login_again_modal')

@endsection

@section('pagejs')

<script type="text/javascript">

  // 開始時刻をJavaScriptのDateオブジェクトに変換
  const start_datetime = '{{ $start_datetime }}';

  // datetime_flg の初期値を true に設定
  var datetime_flg = true;

  function time_calculation() {

    // datetime_flg が false なら処理をスキップ
    if (!datetime_flg) {
      return;
    }

    // 開始時刻がnullや空でないことを確認
    if (!start_datetime) {
      // タイマーがnullの場合の処理（任意でメッセージや初期値を設定）      
      datetime_flg = false;
      return;
    }

    const start = new Date(start_datetime);

    // 有効な日付か確認
    if (isNaN(start.getTime())) {      
      datetime_flg = false;
      return;
    }

    // 現在の日時を取得
    const now = new Date();

    // 経過時間をミリ秒で計算
    const elapsedMs = now - start;

    // 経過時間を秒単位に変換
    const elapsedSeconds = Math.floor(elapsedMs / 1000);

    // 経過時間を時、分、秒に変換
    const hours = Math.floor(elapsedSeconds / 3600);
    const minutes = Math.floor((elapsedSeconds % 3600) / 60);
    const seconds = elapsedSeconds % 60;

    // フォーマットに合わせて文字列を作成
    const formattedTime = `${hours}:${minutes}:${seconds}`;

    // タイマー表示部分を更新
    document.getElementById('timer').textContent = formattedTime;
  }

  // 初期表示
  time_calculation();

  // 1秒ごとにタイマーを更新
  setInterval(time_calculation, 1000);



</script>


@endsection

