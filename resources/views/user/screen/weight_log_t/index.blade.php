@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'weight management')  

@endsection
@section('content')

@php    
  $labels = $get_record['datas']['labels'];
  $weights = $get_record['datas']['weights'];
  $summary = $get_record['summary'];
  $count = $summary['count'];  
@endphp

<style>
.weight_type-label
{
  margin: 0 10px;
  height: 100%;
  width: 100px; 
  color: rgb(53, 7, 7);       
  border-radius: 3px;     
  background-color: rgb(208, 208, 241);        
}

.weight_type-select
{
  background-color: rgb(49, 49, 105);
  color: white;
  border: solid 1px rgb(208, 208, 241);
  font-weight: bold;
  animation: arrowrotate .1s;
}

@keyframes arrowrotate {
    100% {
        transform: rotate(6deg);
    }
}


</style>

<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">
      

      <button type="button" class="btn btn-success" data-bs-toggle='modal' data-bs-target='#save-modal'>記録</button>

      <div class="col-12 col-sm-10 col-md-9 col-lg-8 col-xl-7 search-area">       
   
        <table class="table search-table">

          <tr>
            <td class="col-3">

              <label>
                <input type="radio" data-target="branch" name="branch" value="1" @if($search_array["branch"] == 1) checked @endif>
                年単位
              </label>

            </td>

            <td class="col-3">

              <label>
                <input type="radio" data-target="branch" name="branch" value="2" @if($search_array["branch"] == 2) checked @endif>
                月単位
              </label>
            </td>

            <td class="col-3">

              <label>
                <input type="radio" data-target="branch" name="branch" value="3" @if($search_array["branch"] == 3) checked @endif>
                週単位
              </label>
            </td>

            <td class="col-3">

              <label>
                <input type="radio" data-target="branch" name="branch" value="4" @if($search_array["branch"] == 4) checked @endif>
                日単位
              </label>

            </td>

          </tr>

          <tr>
            <td colspan="3">

              <div class="row m-0 p-0">
                <div class="col-5 m-0 p-0">
                  <input type="date" data-target="start_date" class="form-control" value="{{$search_array['start_date']}}">
                </div>

                <div class="col-2 m-0 p-0">
                  ～
                </div>

                <div class="col-5 m-0 p-0">
                  <input type="date" data-target="end_date" class="form-control" value="{{$search_array['end_date']}}">
                </div>

              </div>

            </td>

            <td>
              <button class="btn btn-outline-primary search-button">
                表示
              </button>
            </td>

          </tr>

        </table>

      </div>

      <div class="contents row justify-content-center d-flex p-5 gap-3">

        <button class="btn btn-outline-primary list_graph_change_button w-120px" data-target="1">
          グラフ
        </button>

        <button class="btn btn-outline-primary list_graph_change_button w-120px" data-target="2">
          表
        </button>

 
    
      </div>

      <div class="weight_list_area d-none">
        <table class="table"> 
          <tr>
            <th>
              記録日時              
            </th>
            <th>
              体重
            </th>
          </tr>

          @foreach ($weight_log_t as $item)
          <tr>
            <th class="">
              {{$item->measure_at}}
            </th>

            <th class="">
              {{$item->weight}}
            </th>
          </tr>
          @endforeach
        </table>
        </div>

  </div> 



  @if($count > 0)
    <div class="weight_graph_area">  
      <canvas id="mychart"></canvas>
    </div>
  @endif



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
              <form id='save-form' action="{{ route('user.weight_log.save') }}" method="post" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="measure_at" value="">
                <input type="hidden" name="weight" value="">
                <input type="hidden" name="weight_type" value="">

                <table class="table">
                  <tr>
                    <td>
                      <div id="timer"></div>
                    </td>
                  </tr>
          
                  <tr>
                    <td class="item-center">
          
                      <label id="weight_type-label1" 
                        for="weight_type1" 
                        class="weight_type-label weight_type-select item-center">kg
                      </label>
          
                      <input type="radio" 
                        id="weight_type1"
                        name="weight_type_radio"  
                        value="1"
                        data-value="1"
                        class="d-none"
                        checked
                      >
          
                      <label id="weight_type-label2" 
                        for="weight_type2" 
                        class="weight_type-label item-center">pound
                      </label>
                      <input type="radio" 
                        id="weight_type2"
                        name="weight_type_radio" 
                        value="2"       
                        data-value="2"                         
                        class="d-none"
                      >
                    </td>                
          
                    
                  </tr>
          
                  <tr>
                    <td class="">
                      <div class="row m-0 p-0">

                        <div class="col-5 m-0 p-0">
                          <input type="text" id="integer"class="form-control text-end" maxlength="3">
                        </div>

                        <div class="col-1 m-0 p-0">
                          <span class="comma">.</span>
                        </div>

                        <div class="col-5 m-0 p-0">
                          <input type="text" id="decimal" class="form-control text-end" maxlength="3">
                        </div>

                        <div class="col-1 m-0 p-0">
                          <span class="display_weight_type">kg</span>                      
                        </div>

                      </div>
                    </td>    
                  </tr>          
                  
          
                </table>                  
              </form>
          </div>

          <div class="modal-footer">

              <div class="col-6 m-0 p-0 text-start">
              </div>

              <div class="col-6 m-0 p-0 text-end">
                  <button type="button" id="save-button" class="btn btn-success">記録</button>
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


  $(document).on("change", 'input[name="weight_type_radio"]', function (e) {

    var value = $(this).data('value');
    
    // クラスをリセット
    $(".weight_type-label").removeClass('weight_type-select');

    // 選択されたラジオボタンのラベルにクラスを追加
    $("#weight_type-label" + value).addClass('weight_type-select');

    var weight_type_radio = document.querySelector('input[name="weight_type_radio"]:checked');
    var weight_type = weight_type_radio.value;

    if(weight_type == 1){
      $('.display_weight_type').text("kg");
    }else{
      $('.display_weight_type').text("lb");
    }

  });

  $('#save-modal').on('show.bs.modal', function(e) {

    $('.ajax-msg').html("");
    $("#save-modal .is-invalid").removeClass('is-invalid');

    $('input[name="measure_at"]').val("");
    $('input[name="weight"]').val("");
    $('input[name="weight_type"]').val("");    

  });


  $(document).on("click", ".list_graph_change_button", function (e) {
    
    var target = $(this).data('target');

    $(".weight_list_area").removeClass('d-none');
    $(".weight_graph_area").removeClass('d-none');

    if(target == 1){
      $(".weight_list_area").addClass('d-none');      
    }else{
      $(".weight_graph_area").addClass('d-none');
    }


  });


  $(document).on("click", "#save-button", function (e) {

    // e.preventDefault();

    var button = $(this);

    button.prop("disabled", true);
    document.body.style.cursor = 'wait';

    let f = $('#save-form');

    standby_processing(1,button,"#save-modal");

    var measure_at = document.getElementById('timer').textContent;


    var integer = $('#integer').val();    
    var decimal = $('#decimal').val();

    if (integer.trim() == "") {
      integer = 0;
    }

    if (decimal.trim() == "") {
      decimal = 0;
    }

 

    var weight = integer + "." + decimal;

    var weight_type_radio = document.querySelector('input[name="weight_type_radio"]:checked');
    var weight_type = weight_type_radio.value;

    $('input[name="measure_at"]').val(measure_at);
    $('input[name="weight"]').val(weight);
    $('input[name="weight_type"]').val(weight_type);

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
            
            $("#save-modal").modal('hide');
            // モーダルを表示する
            $("#login_again-modal").modal('show');

        } else{
                                               

          var message = result_array["message"];

        }    

    })
    .fail(function (data, textStatus, errorThrown) {

        standby_processing(2,button);

        
        var errorsHtml = '<div class="alert alert-danger text-left">';

        if (data.status == '422') {
            
            //{{-- vlidationエラー --}}
            $.each(data.responseJSON.errors, function(key, value) {

                //{{-- responsからerrorsを取得しメッセージと赤枠を設定 --}}
                errorsHtml += '<li>' + value[0] + '</li>';

                if(key == 'weight'){

                  $("#integer").addClass('is-invalid');
                  $("#decimal").addClass('is-invalid');
                  
                }else{

                  $("[name='" + key + "']").addClass('is-invalid');                  

                }

                
            });

        } else {
            //{{-- その他のエラー --}}
            errorsHtml += '<li>Processing Error</li>';
            errorsHtml += '<li>' + data.status + ':' + errorThrown + '</li>';
        }
        errorsHtml += '</div>';
        //{{-- アラート --}}
        $('.ajax-msg').html(errorsHtml);

    });

  });


  @if($count > 0)

    //グラフ作成
    var labels = @json($labels);  // Days or date labels
    var weights = @json($weights);  // Weight data for each corresponding label

    var ctx = document.getElementById('mychart');

    var myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: labels,
    datasets: [{
      label: '{{$summary['user_name']}}',
      data: weights,
      borderColor: '#f1a150',
      tension: 0.1,
    }],
  },
  options: {
    responsive: true,
    scales: {
      y: {  // v3.x以降では 'y' に変更されました
        ticks: {
          beginAtZero: true,
          min: {{ $summary['min_weight'] }},  // 最小値
          max: {{ $summary['max_weight'] }},  // 最大値
          stepSize: {{ $summary['step_size'] }},  // メモリの間隔
        },
      },
    },
    plugins: {
      legend: {
        labels: {
          color: '#f1a150'  // ここで凡例のテキストカラーを指定
        }
      }
    }
  },
});

  @endif

</script>


@endsection

