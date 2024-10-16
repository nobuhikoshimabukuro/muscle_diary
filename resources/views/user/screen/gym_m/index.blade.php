@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'gym_setting')  

@endsection
@section('content')

<style>

</style>

<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">

    <button type="button" class="btn btn-success" data-user_gym_id="0" data-bs-toggle='modal' data-bs-target='#save-modal'>新規登録</button>


    

      <div class="col-12">

          <div id="" class="data-display-area m-0">

              <table id='' class='data-display-table'>

                  @php
                      $text_class_kinds = ["text-start" , "text-center" , "text-end"];

                      $text_class = [];
                      $text_class []= $text_class_kinds[1];
                      $text_class []= $text_class_kinds[0];
                      $text_class []= $text_class_kinds[1];
                      $text_class []= $text_class_kinds[0];
                      $text_class []= $text_class_kinds[2];

                      $text_class_index = 0;
                  @endphp
                  <tr>
                      <th class="{{$text_class[$text_class_index++]}}">ID</th>
                      <th class="{{$text_class[$text_class_index++]}}">ジム名</th>
                      <th class="{{$text_class[$text_class_index++]}}">表示</th>
                      <th class="{{$text_class[$text_class_index++]}}">表示順</th>
                      <th class="{{$text_class[$text_class_index++]}}"></th>
                  </tr>

                  @foreach ($gym_m as $item)

                  @php
                      $text_class_index = 0;
                  @endphp

                  <tr>
                      {{-- ユーザー毎ジムID --}}
                      <td class="{{$text_class[$text_class_index++]}}">
                          {{$item->user_gym_id}}
                      </td>

                      {{-- ジム名 --}}
                      <td class="{{$text_class[$text_class_index++]}}">
                          {{$item->gym_name}}
                      </td>

                      {{-- 表示フラグ --}}
                      <td class="{{$text_class[$text_class_index++]}}">
                          {{$item->display_flg}}
                      </td>

                      {{-- 表示順 --}}
                      <td class="{{$text_class[$text_class_index++]}}">
                          {{$item->display_order}}
                      </td>

                      <td class="{{$text_class[$text_class_index++]}}">
                          <button type="button" class="btn btn-outline-secondary"                               
                              data-user_gym_id="{{$item->user_gym_id}}" 
                              data-gym_name="{{$item->gym_name}}" 
                              data-display_flg="{{$item->display_flg}}"                                  
                              data-display_order="{{$item->display_order}}"
                              data-bs-toggle='modal' data-bs-target='#save-modal'
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
                  <div class="ajax-msg1"></div>                
              </div>
              <form id='save-form' action="{{ route('user.gym_m.save') }}" method="post" enctype="multipart/form-data">
                @csrf
                  <input type="hidden" name="user_gym_id" value="">
                  <input type="text" name="gym_name" value="">
                  <input type="text" name="display_order" value="">     
                        
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


  $('#save-modal').on('show.bs.modal', function(e) {

    // イベント発生元
    let evCon = $(e.relatedTarget);

    let user_gym_id = evCon.data('user_gym_id');
   
    $('input[name="user_gym_id"]').val(user_gym_id);

  });

  $(document).on("click", "#save-button", function (e) {

    e.preventDefault();

    var button = $(this);

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

