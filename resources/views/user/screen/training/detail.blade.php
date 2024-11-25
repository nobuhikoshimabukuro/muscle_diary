@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'training_detail')  

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

@endphp

<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">

    <div class="card col-12 col-sm-10 col-md-9 col-lg-8 col-xl-7">     

      <input type="hidden" id="user_training_count" name="user_training_count" class="" value="{{$training_history_t->user_training_count}}">

      <table class="table">

     

          @if($training_history_t->data_type == 1)

            <tr>
              <td colspan="3">
                <div id="timer"></div>
              </td>          
            </tr>  


            <tr>          
              <td>
                ジム選択
              </td>

              <td>
                <select id="user_gym_id" name="user_gym_id" class="form-control">

                  <option value="0">未選択</option>                  
                  @foreach ($gym_m as $info1)
                
                    <option value="{{$info1->user_gym_id}}"                                        
                    >{{$info1->gym_name}}</option>                  
                  @endforeach
                </select>      
              </td>   
              
              <td>
                <button class="training_history_t-save-button btn btn-outline-success" data-process="1">開始</button>
              </td>
            </tr>  

            

          @elseif($training_history_t->data_type == 2)

          
            <tr>
              <td colspan="3">
                <div id="timer"></div>
              </td>          
            </tr>  

            <tr>          
              <td>
                ジム
              </td>

              <td colspan="2">
                {{$training_history_t->gym_name}}                
              </td>   
            </tr>  

            <tr>          
              <td>
                開始日時
              </td>

              <td colspan="2">
                {{$training_history_t->start_datetime}}              
              </td>   
            </tr>  
            
            
            <tr>          
              <td>
                経過時間
              </td>

              <td colspan="2">
                <div id="elapsed_timer"></div>    
              </td>   
            </tr>  


            <tr>          
              <td>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle='modal' data-bs-target='#training_detail-save-modal'
                >種目記録</button>
               
              </td>
              <td>
               
              </td>
              <td>
                <button class="training_history_t-save-button btn btn-outline-danger" data-process="2">終了</button>
              </td>
            </tr>  

          @elseif($training_history_t->data_type == 3)

          <tr>          
            <td>
              ジム
            </td>

            <td colspan="2">
              {{$training_history_t->gym_name}}                
            </td>   
          </tr>  

          <tr>          
            <td>
              トレーニング時間
            </td>

            <td colspan="2">
              {{$training_history_t->start_datetime}}～{{$training_history_t->end_datetime}}
            </td>   
          </tr>  
          
          
          

          @endif
          
      </table>

    </div>

    <div class="col-12 col-sm-11 col-md-10 col-lg-9 col-xl-9">

      
      @if(!empty((array)$training_detail_t))
      
          <table class="table">

            <tr>
              <th class="text-start">種目</th>
              <th class="text-start">詳細</th>
              
            </tr>

            @foreach ($training_detail_t as $info)        
              <tr>                 
                  <th class="text-start">
                    {{$info->exercise_name}}
                  </th>

                    <th class="text-start">

                      <table>
                        @if($info->type == 1)
                            
                              <tr>
                                <th class="text-end">重さ</th>
                                
                                <td class="text-start">
                                  {{$info->weight}}
                                </td>                                    
                              </tr>

                              <tr>
                                <th class="text-end">回数</th>
                                <td class="text-start">
                                  {{$info->reps}}
                                </td>
                              </tr>

                            
                        @else           
                          <tr>
                            <th class="text-end">
                              時間
                            </th>                                 
                            <td class="text-start">
                              {{$info->time}}
                            </td>
                          </tr>                                                            
                        @endif
                        </table>
                    </th>                  
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
                      <input type="radio" name="measurement_type" value="1" checked>Weight
                    </label>
                    <label>
                      <input type="radio" name="measurement_type" value="2" >Count
                    </label>

                    <label>
                      <input type="radio" name="measurement_type" value="3">Time
                    </label>
                  </td>                 
                </tr>

                <tr class="weight_row">
                  <th>
                    <label for="integer">重さ</label>
                  </th>               

                  <th>
                    <label for="reps">回数</label>
                  </th>               
                </tr>

                <tr class="weight_row">
                  <th colspan="" class="d-flex">
                    <input type="text" id="integer" name="integer" class="form-control text-end w-100px" maxlength="3">
                    <span class="comma">.</span>
                    <input type="text" id="decimal" name="decimal" class="form-control text-end w-100px" maxlength="3">
                    {{-- <span class="display_weight_type">kg</span>    --}}
                    
                    <label>
                      <input type="radio" name="weight_type" value="1" checked>kg
                    </label>
                    <label>
                      <input type="radio" name="weight_type" value="2">lb
                    </label>
                  </th>

                  <th colspan="">
                    <input type="text" id="reps" name="reps" class="form-control text-end w-100px" value="">                    
                  </th>               
                </tr>       


                <tr class="count_row d-none">
                  <th colspan="2">
                    <label for="count">回数</label>
                  </th>                                     
                </tr>
                
                <tr class="count_row d-none">                           
                  <th colspan="2">
                    <input type="text" id="count" name="count" class="form-control text-end w-100px" value="">
                  </th>               
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
  const start_datetime = '{{ $training_history_t->start_datetime }}'.replace(/\//g, '-').replace(' ', 'T');

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


  $(document).on("click", "input[name='measurement_type']", function (e) {
      
      var selectedValue = $("input[name='measurement_type']:checked").val();

      $(".weight_row").removeClass("d-none");
      $(".count_row").removeClass("d-none");
      $(".time_row").removeClass("d-none");
      
      
      if (selectedValue == 1) {
        $(".time_row").addClass("d-none");
        $(".count_row").addClass("d-none");
      } else if(selectedValue == 2) {
        $(".weight_row").addClass("d-none");
        $(".time_row").addClass("d-none");
      } else if(selectedValue == 3) {
        $(".weight_row").addClass("d-none");
        $(".count_row").addClass("d-none");
      }
  });






  //トレーニング履歴ボタン
  $(document).on("click", ".training_history_t-save-button", function (e) {


    var process = $(this).data('process'); 
    var set_datetime = document.getElementById('timer').textContent;
    var user_gym_id = $('#user_gym_id').val();
    var user_training_count = $('#user_training_count').val();
    
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
            data: { 'user_training_count' : user_training_count
                  , 'set_datetime' : set_datetime
                  , 'user_gym_id' : user_gym_id
                  , 'process' : process
                },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
	    })
    .done(function (data, textStatus, jqXHR) {

        standby_processing(2,button);

        var result_array = data.result_array;

        if(result_array["result"] == 'success'){

          var user_training_count = result_array["user_training_count"];
          var currentUrl = window.location.href.split('?')[0]; // ベースURLを取得

          // パラメータを付けて遷移
          window.location.href = currentUrl + "?user_training_count=" + user_training_count;

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
    var user_training_count = $('#user_training_count').val();
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
              'user_training_count' : user_training_count,
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

