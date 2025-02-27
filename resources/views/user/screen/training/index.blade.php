@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'Training Management')  

@endsection
@section('content')

<style>

</style>


<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">

    <div class="col-6 text-start">
      <button class="save-modal-open btn btn-outline-success">開始</button>      
    </div>
    <div class="col-6 text-end">      
      <button class="search-modal-open btn btn-outline-success">検索設定</button>
    </div>

    <div class="col-12 mt-2">
      
                
      @if(count($training_history_t) > 0)

        @php
          $text_class_kinds = ["text-start" , "text-center" , "text-end"];

          $text_class = [];
          $text_class []= $text_class_kinds[1];
          $text_class []= $text_class_kinds[0];
          $text_class []= $text_class_kinds[0];
          $text_class []= $text_class_kinds[0];
          $text_class []= $text_class_kinds[0];
          $text_class []= $text_class_kinds[2];
          

          $text_class_index = 0;
        @endphp


     

   
        <div class="data-display-area">  
          <div class="pagination-area text-end">
            {{ $training_history_t->appends(request()->query())->links() }}            
          </div>
        
            <table class="table data-display-table">

              <tr>
                <th class="{{$text_class[$text_class_index++]}}">Record ID</th>
                <th class="{{$text_class[$text_class_index++]}}">Gym</th>
                <th class="{{$text_class[$text_class_index++]}}">Start</th>
                <th class="{{$text_class[$text_class_index++]}}">End</th>
                <th class="{{$text_class[$text_class_index++]}}">Total Time</th>           
                <th class="{{$text_class[$text_class_index++]}}">
                  {{ $training_history_t->firstItem() }}～{{ $training_history_t->lastItem() }} / {{ $training_history_t->total() }}
                </th>
              </tr>

              @foreach ($training_history_t as $training_history_info)

                @php
                  $text_class_index = 0;
                @endphp

                  <tr>
                    <td class="{{$text_class[$text_class_index++]}}">{{$training_history_info->user_training_count}}</td> 
                    <td class="{{$text_class[$text_class_index++]}}">{{$training_history_info->gym_name}}</td> 

                    <td class="{{$text_class[$text_class_index++]}}">
                      {{$training_history_info->start_datetime}}                      
                      
                    </td>

                    
                    <td class="{{$text_class[$text_class_index++]}}">
                      {{$training_history_info->end_datetime}}                    
                    </td>


                    <td class="{{$text_class[$text_class_index++]}}">
                      @if($training_history_info->end_datetime == "")
                        ※Training in Progress
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




{{-- 検索モーダル --}}
<div class="modal fade" id="search-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="search-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

          <div class="modal-header">
              <h5 class="modal-title" id="">検索項目</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>                
          </div>

          <div class="modal-body">             

            <div class="search-area">             

              <table class="search-table w-100">

                <tr>
                  <th>ジム選択</th>
                  <td>                  
                    <select data-target="user_gym_id" class="form-control input-sm w-auto">
              
                      <option value="">未選択</option>                  
                        @foreach ($gym_m as $info)              
                          <option value="{{$info->user_gym_id}}"
                            @if($info->user_gym_id == $search_array->user_gym_id) selected @endif
                          >{{$info->gym_name}}</option>                  
                        @endforeach
                    </select>
        
                  </td>
                </tr>

                <tr>
                  <th class="">トレ日（開始）</th>
                  <td class="">      
                    <input type="date" data-target="training_date_f" class="form-control w-auto" value="{{$search_array->training_date_f}}">
                  </td>
                </tr>
      
                <tr>
                  <th class="">トレ日（終了）</th>
                  <td class="">      
                    <input type="date" data-target="training_date_t" class="form-control w-auto" value="{{$search_array->training_date_t}}">
                  </td>
                </tr>

                <tr>
                  <th>表示件数</th>
                  <td>      
                    <select data-target="display_count" class="form-control input-sm text-center w-auto">                              
                        @foreach ($display_counts as $display_count)
                          
                          <option value="{{$display_count}}"                                       
                          @if($display_count == $search_array->display_count) selected @endif 
                          >{{$display_count}}</option>                  
                        @endforeach
                    </select>
                  </td>
                </tr>
                
              </table>           
          
            </div>

          </div>

          <div class="modal-footer">

              <div class="col-6 m-0 p-0 text-start">
                <button class="btn btn-outline-info common-clear-button">
                  クリア
                </button>
    
                <button class="btn btn-outline-primary common-search-button">
                    検索
                  </button>
              </div>

              <div class="col-6 m-0 p-0 text-end">
              
                  <button type="button" id="" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
              </div>
          </div>
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
                  <div class="error_message_area"></div>                
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
    var now = new Date();

    // 各値を取得
    var year = now.getFullYear();
    var month = ('0' + (now.getMonth() + 1)).slice(-2);  // 月は0から始まるので +1
    var day = ('0' + now.getDate()).slice(-2);
    var hours = ('0' + now.getHours()).slice(-2);
    var minutes = ('0' + now.getMinutes()).slice(-2);
    var seconds = ('0' + now.getSeconds()).slice(-2);

    // フォーマットに合わせて文字列を作成
    var formattedTime = `${year}/${month}/${day} ${hours}:${minutes}:${seconds}`;

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


  $('.save-modal-open').click(function(){
    // モーダルを表示する        
    $("#save-modal").modal('show');
  });


  $('.search-modal-open').click(function(){
    // モーダルを表示する        
    $("#search-modal").modal('show');
  });


  

  $(document).on("click", ".training_history_t-save-button", function (e) {

    e.preventDefault();
    var button = $(this);    

    var process = $(this).data('process');     
    var set_datetime = document.getElementById('timer').textContent;
    var user_gym_id = $('#user_gym_id').val();
    
    var user_training_count = 0;
    if(process == 2){
      user_training_count = $(this).data('user_training_count'); 
    }

    clear_error_message(".error_message_area");
    standby_processing(1,button,"body");
    

    var url = "{{ route('user.training_history.save') }}";

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

          var message = result_array["message"];

        }    

    })
    .fail(function (data, textStatus, errorThrown) {

      standby_processing(2,button);

    });

  });

</script>


@endsection

