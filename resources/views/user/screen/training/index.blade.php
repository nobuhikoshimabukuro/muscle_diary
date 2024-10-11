@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'training_management')  

@endsection
@section('content')

<style>
/* 操作不可能 */
.impossible {
	pointer-events: none;
	opacity: 0.3;
}

</style>

@php

  $new_data_flg = $training_info->new_data_flg;
  $start_datetime = $training_info->start_datetime;
  $end_datetime = $training_info->end_datetime;

  if($new_data_flg){
    $start_datetime = "";
  }

@endphp

<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">

    <div class="card col-12 col-sm-10 col-md-9 col-lg-8 col-xl-7">
      
      <table class="table">

        <tr>
          <td>
            <div id="timer"></div>
          </td>          
        </tr>  

        @if($new_data_flg)

          <tr>          
            <td>
              前回トレーニング終了日時{{$end_datetime}}              
            </td>
          </tr>  

        @else

          <tr>          
            <td>
              トレーニング開始日時{{$start_datetime}}
              <br>
              経過時間<div id="elapsed_timer"></div>
            </td>
          </tr>  

        @endif
        

        <tr>
          <td>
            <button class="save-button btn btn-outline-success @if(!$new_data_flg) impossible @endif">開始</button>
            <button class="save-button btn btn-outline-danger @if($new_data_flg) impossible @endif">終了</button>
          </td>
        </tr>       
      </table>

    </div>

  </div> 

  <form id='save-form' action="{{ route('user.training.save') }}" method="post" enctype="multipart/form-data">
    @csrf
      <input type="hidden" name="user_gym_id" value="">
      <input type="hidden" name="set_datetime" value="">       
  </form>

</div>


{{-- 再ログインモーダルの読み込み --}}
@include('user/common/login_again_modal')

@endsection

@section('pagejs')

<script type="text/javascript">

  // 開始時刻をISO 8601形式に変換
  const start_datetime = '{{ $start_datetime }}'.replace(' ', 'T');

  // datetime_flg の初期値を true に設定
  var datetime_flg = true;

  function updateTimer() {
    // 現在の日時を取得
    const now = new Date();

    // 各値を取得
    const year = now.getFullYear();
    const month = ('0' + (now.getMonth() + 1)).slice(-2);  // 月は0から始まるので +1
    const day = ('0' + now.getDate()).slice(-2);
    const hours = ('0' + now.getHours()).slice(-2);
    const minutes = ('0' + now.getMinutes()).slice(-2);
    const seconds = ('0' + now.getSeconds()).slice(-2);

    // フォーマットに合わせて文字列を作成
    const formattedTime = `${year}/${month}/${day} ${hours}:${minutes}:${seconds}`;

    // タイマー表示部分を更新
    document.getElementById('timer').textContent = formattedTime;

    // datetime_flg が false なら処理をスキップ
    if (!datetime_flg) {
      return;
    }

    // 開始時刻がnullや空でないことを確認
    if (!start_datetime) {
      datetime_flg = false;
      return;
    }

    const start = new Date(start_datetime);

    // 有効な日付か確認
    if (isNaN(start.getTime())) {
      datetime_flg = false;
      return;
    }

    // 経過時間をミリ秒で計算
    const elapsedMs = now - start;

    // 経過時間を秒単位に変換
    const elapsedSeconds = Math.floor(elapsedMs / 1000);

    // 経過時間を時、分、秒に変換
    const elapsedHours = Math.floor(elapsedSeconds / 3600);
    const elapsedMinutes = Math.floor((elapsedSeconds % 3600) / 60);
    const elapsedSecs = elapsedSeconds % 60;

    // フォーマットに合わせてゼロ埋めしながら文字列を作成
    // 時間はゼロ埋めせずそのまま表示、分と秒はゼロ埋め
    const formattedElapsedTime = `${elapsedHours}時間${('0' + elapsedMinutes).slice(-2)}分${('0' + elapsedSecs).slice(-2)}秒`;


    // 経過時間表示部分を更新
    document.getElementById('elapsed_timer').textContent = formattedElapsedTime;
  }

  // 1秒ごとにタイマーを更新
  setInterval(updateTimer, 1000);

  // 初期表示
  updateTimer();


  $(document).on("click", ".save-button", function (e) {

    var set_datetime = document.getElementById('timer').textContent;
    $('input[name="set_datetime"]').val(set_datetime);
    $('input[name="user_gym_id"]').val(1);

    e.preventDefault();

    var button = $(this);

    button.prop("disabled", true);
    document.body.style.cursor = 'wait';

    let f = $('#save-form');

    standby_processing(1,button,"#save-modal");

    $.ajax({
      url: f.prop('action'), // 送信先
      type: f.prop('method'),
      dataType: 'json',
      data: f.serialize(),
    })
    .done(function (data, textStatus, jqXHR) {

        standby_processing(2,button);

        var result_array = data.result_array;

        if(result_array["result"] == 'success'){

            location.reload();

        }else if(result_array["result"] == 'login_again'){
                       
          // モーダルを表示する
          $("#login_again-modal").modal('show');

        } else{
          
          button.prop("disabled", false);          
          document.body.style.cursor = 'auto';                               

          var message = result_array["message"];

        }    

    })
    .fail(function (data, textStatus, errorThrown) {

      standby_processing(2,button);


    });

  });



</script>


@endsection

