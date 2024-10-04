@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'gym_setting')  

@endsection
@section('content')

<style>

</style>

<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">

    

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
              <form id='save-form' action="{{ route('user.weight_log.save') }}" method="post" enctype="multipart/form-data">

                  <input type="hidden" name="time" value="">
                  <input type="hidden" name="weight" value="">
                  <input type="hidden" name="weight_type" value="">
      
                  <div class="form-group row">
                      <span id="display_time"></span>
                      <span id="display_weight"></span>                      
                  </div>                  
              </form>
          </div>

          <div class="modal-footer">

              <div class="col-6 m-0 p-0 text-start">
              </div>

              <div class="col-6 m-0 p-0 text-end">
                  <button type="button" id="save-button" class="btn btn-success">はい</button>
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

    var time = document.getElementById('timer').textContent;
    var weight = 89.214;

    var selectedRadio = document.querySelector('input[name="weight_type_radio"]:checked');
    var weight_type = selectedRadio.value;

    $('input[name="time"]').val(time);
    $('input[name="weight"]').val(weight);
    $('input[name="weight_type"]').val(weight_type);

    var display_weight = weight;
    if(weight_type == 1){
      display_weight += "kg";
    }else{
      display_weight += "lb";
    }

    $('#display_time').text(time);
    $('#display_weight').text(display_weight); 

  });

  $(document).on("click", ".comment-button", function (e) {

    e.preventDefault();

    var $button = $(this);

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

