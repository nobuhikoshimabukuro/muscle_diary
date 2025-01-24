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


<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">

    <div class="col-12">
      
       <table class="table">

          <tr>
            <td>              
              <button class="btn btn-outline-success page-transition-button"
              data-url="{{ route('user.training.analysis') }}"
              data-process="1"
              >トレーニング解析
              </button>
            </td>          

            <td>
              
            </td>          

            <td>
              
            </td>          
          </tr>  


          <tr>
            <td colspan="3">
              <div id="timer"></div>
            </td>          
          </tr>  


          <tr>          
            <td class="">
              <label for="user_gym_id">ジム選択</label>
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
  
      </table>      
          
      @if(count($training_history_t) > 0)

        @php
          $text_class_kinds = ["text-start" , "text-center" , "text-end"];

          $text_class = [];
          $text_class []= $text_class_kinds[1];
          $text_class []= $text_class_kinds[0];
          $text_class []= $text_class_kinds[0];
          $text_class []= $text_class_kinds[0];
          $text_class []= $text_class_kinds[2];
          

          $text_class_index = 0;
        @endphp

        <div class="data-display-area">  
            <table class="table data-display-table">

              <tr>
                <th class="{{$text_class[$text_class_index++]}}">記録ID</th>
                <th class="{{$text_class[$text_class_index++]}}">ジム名</th>
                <th class="{{$text_class[$text_class_index++]}}">日時</th>
                <th class="{{$text_class[$text_class_index++]}}">合計時間</th>           
                <th></th>
              </tr>

              @foreach ($training_history_t as $training_history_info)

                @php
                  $text_class_index = 0;
                @endphp

                  <tr>
                    <td class="{{$text_class[$text_class_index++]}}">{{$training_history_info->user_training_count}}</td> 
                    <td class="{{$text_class[$text_class_index++]}}">{{$training_history_info->gym_name}}</td> 

                    <td class="{{$text_class[$text_class_index++]}}">
                      @if($training_history_info->end_datetime == "")
                        {{$training_history_info->start_datetime}}
                      @else
                        {{$training_history_info->start_datetime}}～{{$training_history_info->end_datetime}}
                      @endif
                    </td>

                    <td class="{{$text_class[$text_class_index++]}}">
                      @if($training_history_info->end_datetime == "")
                        ※トレーニング中
                        <button class="training_history_t-save-button btn btn-outline-success" 
                          data-process="2" data-user_training_count="{{$training_history_info->user_training_count}}">終了</button>
                      @else
                        {{$training_history_info->elapsed_time}}
                      @endif
                      
                    </td> 

                    <td class="{{$text_class[$text_class_index++]}}">
                      <button type="button" class="btn btn-outline-primary training_detail-button" data-target="{{$training_history_info->user_training_count}}" >詳細</button>
                    </td> 
                  </tr>                                            

              @endforeach

            </table>

          </div>

        @else

          {{-- <div class="card">
            
          </div> --}}


        @endif
        
    </div>

  </div> 



</div>






{{-- 登録更新モーダル --}}
<div class="modal fade" id="save-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="save-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

          <div class="modal-header">
              <h5 class="modal-title" id=""></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                
          </div>

          <div class="modal-body">
              <div class="col-12">
                  <div class="ajax-msg"></div>                
              </div>
              <form id='save-form' enctype="multipart/form-data">                

                <input type="hidden" name="measure_at" value="">                

                <table class="table">
                  <tr>
                    <td colspan="2">
                      <div id="timer"></div>
                    </td>          
                  </tr>  
      
      
                  <tr>          
                    <td class="">
                      <label for="user_gym_id">ジム選択</label>
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
                    
              
                  </tr>  
          
                </table>                  
              </form>
          </div>

          <div class="modal-footer">

              <div class="col-6 m-0 p-0 text-start">
              </div>

              <div class="col-6 m-0 p-0 text-end">
                  <button class="training_history_t-save-button btn btn-outline-success" data-process="1">開始</button>
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
  }

  // 1秒ごとにタイマーを更新
  setInterval(updateTimer, 1000);

  // 初期表示
  updateTimer();


  
  $(document).on("click", ".training_detail-button", function (e) {
      // this の target を取得
      var target = $(this).data('target');      
      
      var url = "{{ route('user.training.detail') }}";
      window.location.href = url + "?user_training_count=" + target;
  });


  
  $(document).on("click", ".training_history_t-save-button", function (e) {


    var process = $(this).data('process');     
    var set_datetime = document.getElementById('timer').textContent;
    var user_gym_id = $('#user_gym_id').val();
    var user_training_count = $('#user_training_count').val();

    var user_training_count = 0;

    if(process == 2){
      user_training_count = $(this).data('user_training_count'); 
    }
    

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

          if(process == 1){
            url = "{{ route('user.training.detail') }}";
            url = url + "?user_training_count=" + user_training_count;
          }else{
            url = "{{ route('user.training.index') }}";
          }
          

          // パラメータを付けて遷移
          window.location.href = url;

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

