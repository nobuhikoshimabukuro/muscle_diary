@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'training_save')  

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
  $gym_name = $training_info->gym_name;
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
            @if($new_data_flg) 
              <select id="user_gym_id" name="user_gym_id" class="form-control">

                <option value="0">未選択</option>                  
                @foreach ($gym_m as $info1)
              
                  <option value="{{$info1->user_gym_id}}"                                        
                  >{{$info1->gym_name}}</option>                  
                @endforeach
              </select>

              <button class="training_history_t-save-button btn btn-outline-success">開始</button>
            
            @else
              {{$gym_name}}
              <button class="training_history_t-save-button btn btn-outline-danger">終了</button>

              <button type="button" class="btn btn-outline-secondary" data-bs-toggle='modal' data-bs-target='#training_detail-save-modal'
              >種目記録</button>
            @endif
            
            
          </td>
        </tr>       
      </table>

    </div>


    @if(!$new_data_flg) 
      
    @endif


    <div class="col-12">

      @if(count($training_history_t) > 0)
      
          <table class="table">

            <tr>
              <th>ジム名</th>
              <th>時間</th>
              <th></th>
            </tr>

            @foreach ($training_history_t as $training_history_info)

              @php
                $training_detail_t = $training_history_info->training_detail_t;
              @endphp


                <tr>
                  <td>{{$training_history_info->gym_name}}</td> 

                  <td>
                    @if(is_null($training_history_info->end_datetime))
                      {{$training_history_info->start_datetime}}
                    @else
                      {{$training_history_info->start_datetime}}～{{$training_history_info->end_datetime}}
                    @endif
                  </td>

                  <td>
                    <button type="button" class="btn btn-outline-primary training_detail-button" data-target="{{$training_history_info->user_training_count}}" >詳細</button>
                  </td> 
                </tr>

                <tr>

                  <td colspan="3">

                    <div class="training_detail-area d-none" data-target="{{$training_history_info->user_training_count}}">

                      <table class="table">
        
                        <tr>
                          <th class="text-start">種目</th>
                          <th class="text-center">詳細</th>                  
                        </tr>
        
                        @foreach ($training_detail_t as $training_detail_info)  
                        
                          <tr>
                            <th class="text-start">
                              {{$training_detail_info->exercise_name}}
                            </th>
        
                            <th class="text-center">
                              @if($training_detail_info->type == 1)
                                  <table>
                                    <tr>
                                      <th class="text-end">
                                        重さ
                                      </th>          
                                      
                                      <td class="text-start">
                                        {{$training_detail_info->weight}}
                                      </td>                                    
                                    </tr>

                                    <tr>
                                      <th class="text-end">
                                        回数
                                      </th>                                 
                                      <td class="text-start">
                                        {{$training_detail_info->reps}}
                                      </td>
                                    </tr>

                                  </table>
                              @else
                              
                                  時間:{{$training_detail_info->time}}
                              
                              @endif
                            </th>                  
                          </tr>
        
                        @endforeach
        
                      </table>
        
                    </div>

                  </td>
                </tr>

            @endforeach

          </table>

        @endif

    </div>
  </div> 



</div>




{{-- 登録更新モーダル --}}
<div class="modal fade" id="training_detail-save-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="training_detail-save-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

          <div class="modal-header">
              <h5 class="modal-title" id=""></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                
          </div>

          <div class="modal-body">

              <div class="col-12">
                  <div class="training_detail-save-modal-error-message-area"></div>                
              </div>
         
              <table class="input-table">

                <tr>
                  <th colspan="2">
                    <label for="user_exercise_id">種目</label>
                  </th>                    
                </tr>

                <tr>               
                  <td colspan="2">
                    <select id="user_exercise_id" name="user_exercise_id" class="form-control">
                      @foreach ($exercise_m as $info2)
                            
                        <option value="{{$info2->user_exercise_id}}"                                        
                        >{{$info2->exercise_name}}</option>                  
                      @endforeach
                    </select>
                  </td>
                </tr>

                <tr>             
                  <th colspan="2">
                    <label>種類</label>
                  </th>               
                </tr>

                <tr>                  
                  <td colspan="2">
                    <label>
                      <input type="radio" name="type" value="1" checked>Wait
                    </label>
                    <label>
                      <input type="radio" name="type" value="2">Time
                    </label>
                  </td>                 
                </tr>

                <tr class="time_row d-none">
                  <th colspan="2">
                    <label for="time">時間</label>
                  </th>               
                </tr>

                <tr class="time_row d-none">
                  <th colspan="2">
                    <input type="time" id="time" name="time" class="form-control w-200px" step="1" value="00:00:00">
                  </th>               
                </tr>


                <tr class="weight_row">
                  <th>
                    <label for="weight">重さ</label>

                    <label>
                      <input type="radio" name="weight_type" value="1" checked>kg
                    </label>
                    <label>
                      <input type="radio" name="weight_type" value="2">lb
                    </label>
                  </th>               

                  <th>
                    <label for="time">回数</label>
                  </th>               
                </tr>

                <tr class="weight_row">
                  <th colspan="" class="d-flex">
                    <input type="text" id="integer" name="integer" class="form-control text-end w-100px" maxlength="3">
                    <span class="comma">.</span>
                    <input type="text" id="decimal" name="decimal" class="form-control text-end w-100px" maxlength="3">
                    <span class="display_weight_type">kg</span>                  
                  </th>               
                  <th colspan="">
                    <input type="text" id="reps" name="reps" class="form-control w-100px" value="">                    
                  </th>               
                </tr>              

              </table>


              
                
        
              
              
          </div>

          <div class="modal-footer">

              <div class="col-6 m-0 p-0 text-start">
              </div>

              <div class="col-6 m-0 p-0 text-end">
                <button class="training_detail_t-save-button btn btn-outline-success">記録</button>
                  <button type="button" id="" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
              </div>
          </div>
      </div>

  </div>
  
</div>




{{-- 再ログインモーダルの読み込み --}}
@include('user/common/login_again_modal')

@endsection

@section('pagejs')

<script type="text/javascript">

  // 開始時刻をISO 8601形式に変換
  const start_datetime = '{{ $start_datetime }}'.replace(/\//g, '-').replace(' ', 'T');

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


  $(document).on("click", "input[name='type']", function (e) {
      
      var selectedValue = $("input[name='type']:checked").val();

      $(".time_row").removeClass("d-none");
      $(".weight_row").removeClass("d-none");
      
      if (selectedValue == 1) {
        $(".time_row").addClass("d-none");
      } else {
        $(".weight_row").addClass("d-none");
      }
  });

  
    $(document).on("click", ".training_detail-button", function (e) {
        // this の target を取得
        var target = $(this).data('target');
        
        // 同一の target を持つ .training_detail-area 要素を取得
        var $targetArea = $('.training_detail-area[data-target="' + target + '"]');
        
        // d-none クラスがあればリムーブ、なければ add
        $targetArea.toggleClass('d-none');
    });


  //トレーニング履歴ボタン
  $(document).on("click", ".training_history_t-save-button", function (e) {

    var set_datetime = document.getElementById('timer').textContent;
    var user_gym_id = $('#user_gym_id').val();
    
    e.preventDefault();

    var button = $(this);

    button.prop("disabled", true);
    document.body.style.cursor = 'wait';

    var url = "{{ route('user.training_history.save') }}";    

    standby_processing(1,button,"body");

    $.ajax({
            url: url, // 送信先
            type: 'post',
            dataType: 'json',
            data: { 'set_datetime' : set_datetime, 'user_gym_id' : user_gym_id},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
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


  //トレーニング詳細ボタン
  $(document).on("click", ".training_detail_t-save-button", function (e) {


    var error_message_area = ".training_detail-save-modal-error-message-area";
    error_reset(error_message_area);
    var user_training_detail_id = 0;
    var user_exercise_id = $('#user_exercise_id').val();  
    var type = $('input[name="type"]:checked').val();
    var weight_type = $('input[name="weight_type"]:checked').val();
    var time = $('#time').val();
    var reps = $('#reps').val();
    var weight = $('#integer').val() + "." + $('#decimal').val();

    e.preventDefault();

    var button = $(this);

    button.prop("disabled", true);
    document.body.style.cursor = 'wait';

    var url = "{{ route('user.training_detail.save') }}";   

    standby_processing(1,button,"body");

  $.ajax({
          url: url, // 送信先
          type: 'post',
          dataType: 'json',
          data: { 
              'user_training_detail_id' : user_training_detail_id,
              'user_exercise_id' : user_exercise_id,
              'type' : type,
              'time' : time,
              'weight_type' : weight_type,
              'reps' : reps,
              'weight' : weight
          },
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
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

        //{{-- アラートメッセージ表示 --}}
        let errors_html = '<div class="alert alert-danger text-left">';

        if (data.status == '422') {
          //{{-- vlidationエラー --}}
          $.each(data.responseJSON.errors, function (key, value) {
            //{{-- responsからerrorsを取得しメッセージと赤枠を設定 --}}
            errors_html += '<li>' + value[0] + '</li>';

            $("[name='" + key + "']").addClass('is-invalid');

            $("[name='" + key + "']").next('.invalid-feedback').text(value);

          });

        } else {
          //{{-- その他のエラー --}}
          errors_html += '<li>' + data.status + ':' + errorThrown + '</li>';
        }

        errors_html += '</div>';
        //{{-- アラート --}}
        $(error_message_area).html(errors_html);      


    });

  });



  function error_reset(target) {
    $(target).html("");    
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').removeClass('invalid-feedback');
  }

</script>


@endsection

