@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'exercise_setting')  

@endsection
@section('content')

<style>



</style>

<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">

    
    <div class="col-12 col-sm-10 col-md-9 col-lg-8 col-xl-7">     
      <button type="button" class="btn btn-success save-modal-open" data-user_exercise_id="0">新規登録</button>
    </div>

    


    

      <div class="col-12">

          <div id="" class="data-display-area m-0">

              <table id='' class='data-display-table'>

                  @php
                      $text_class_kinds = ["text-start" , "text-center" , "text-end"];

                      $text_class = [];
                      $text_class []= $text_class_kinds[1];
                      $text_class []= $text_class_kinds[0];
                      $text_class []= $text_class_kinds[1];                      
                      $text_class []= $text_class_kinds[2];
                      $text_class []= $text_class_kinds[2];

                      $text_class_index = 0;
                  @endphp
                  <tr>
                      <th class="{{$text_class[$text_class_index++]}}">ID</th>
                      <th class="{{$text_class[$text_class_index++]}}">種目名</th>
                      <th class="{{$text_class[$text_class_index++]}}">利用</th>                      
                      <th class="{{$text_class[$text_class_index++]}}">表示順</th>
                      <th class="{{$text_class[$text_class_index++]}}"></th>
                  </tr>

                  @foreach ($exercise_m as $item)

                    @php
                        $text_class_index = 0;
                    @endphp

                    <tr>
                        {{-- ユーザー毎種目ID --}}
                        <td class="{{$text_class[$text_class_index++]}}">
                            {{$item->user_exercise_id}}
                        </td>

                        {{-- 種目名 --}}
                        <td class="{{$text_class[$text_class_index++]}}">
                            {{$item->exercise_name}}
                        </td>

                        {{-- 表示フラグ --}}
                        <td class="{{$text_class[$text_class_index++]}}">
                            @if($item->display_flg == 0)
                              無
                            @else
                              有
                            @endif                            
                        </td>

                              {{-- 表示順 --}}
                        <td class="{{$text_class[$text_class_index++]}}">
                            {{$item->display_order}}
                        </td>

                        <td class="{{$text_class[$text_class_index++]}}">
                            <button type="button" class="btn btn-outline-secondary save-modal-open"                               
                                data-user_exercise_id="{{$item->user_exercise_id}}" 
                                data-exercise_name="{{$item->exercise_name}}"                                 
                                data-display_flg="{{$item->display_flg}}"
                                data-display_order="{{$item->display_order}}"                                
                            >編集</button>
                        </td>

                    </tr>

                  @endforeach

              </table>

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
              <form id='save-form' action="{{ route('user.exercise_m.save') }}" method="post" enctype="multipart/form-data">
                @csrf
                  <input type="hidden" name="user_exercise_id" value="">                 
                  
                  <table class="input-table">

                    <tr>
                      <th colspan="2">
                        <label for="exercise_name">種目名</label>
                      </th>                    
                    </tr>

                    <tr>               
                      <td colspan="2">
                        <input type="text" id="exercise_name" name="exercise_name" class="form-control" value="">
                      </td>
                    </tr>

                   


                    <tr>
                      <th>
                        <label>利用</label>
                      </th>         
                      <th>
                        <label for="display_order">表示順</label>
                      </th>               
                    </tr>

                    <tr>  
                      
                      <td>
                        <label>
                          <input type="radio" name="display_flg" value="1"> 有
                        </label>
                        <label>
                            <input type="radio" name="display_flg" value="0"> 無
                        </label>
                      </td>

                      <td>
                        <input type="text" id="display_order" name="display_order" class="form-control" value="">
                      </td>
                    </tr>


                   

                  </table>
                        
              </form>
          </div>

          <div class="modal-footer">

              <div class="col-6 m-0 p-0 text-start">
              </div>

              <div class="col-6 m-0 p-0 text-end">
                  <button type="button" id="save-button" class="btn btn-success">登録</button>
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

  $('.save-modal-open').click(function(){
        
    var button = $(this);

    var user_exercise_id = button.data('user_exercise_id');

    var exercise_name = "";
    var display_order = 1;
    var display_flg = 1;
    
     
    $(".bodyweight_flg-area").removeClass('d-none');
    if(user_exercise_id != 0){
      
      exercise_name = button.data('exercise_name');      
      display_order = button.data('display_order');    
      display_flg = button.data('display_flg');     
    }

    $('input[name="user_exercise_id"]').val(user_exercise_id);  
    $('input[name="exercise_name"]').val(exercise_name);
    $('input[name="display_flg"][value="' + display_flg + '"]').prop('checked', true);
    $('input[name="display_order"]').val(display_order);

    // モーダルを表示する        
    $("#save-modal").modal('show');

  });

 

  $(document).on("click", "#save-button", function (e) {

    e.preventDefault();

    var button = $(this);

    let f = $('#save-form');
    
    clear_error_message(".error_message_area");
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

          var message = result_array["message"];

        }    

    })
    .fail(function (data, textStatus, errorThrown) {

      standby_processing(2,button);


    });

  });

</script>


@endsection

