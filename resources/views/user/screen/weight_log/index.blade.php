@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'weight management')  

@endsection
@section('content')



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


.weight_list_area {
  height: 700px; 
  overflow-y: auto;
  overflow-x: hidden;
  border: 1px solid #ccc; /* 必要なら枠線を追加 */
  padding: 0 0 5px 0;
}

.weight_log_table th {
  border: none;
  position: sticky; /* 固定 */
  top: 0; 
  background-color: #fff; 
  z-index: 10; 
  padding: 3px 0 0 0; 

}

.weight_log_table-th{
  padding: 3px;
  border-bottom: solid 2px rgb(189, 184, 184);
}

.weight_graph_area {
  overflow-x: auto;              /* 横スクロール有効にする */
  overflow-y: hidden;            /* 縦スクロールは不要なら隠す */
  white-space: nowrap;
  display: block;                /* flex不要なのでblockに */
  padding: 10px;
}

.mychart {
  min-width: 0;
  height: auto;    /* CSSの高さは自動に */
  display: inline-block;
}
</style>

<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">
      

      <div class="col-12 col-sm-10 col-md-9 col-lg-8 col-xl-7">     
        <button type="button" class="btn btn-success save-modal-open">記録</button>
      </div>

      <div class="col-12 col-sm-10 col-md-9 col-lg-8 col-xl-7 search-area">       
   
        <table class="table search-table">

          <tr>
            <td class="col-3">

              <label>
                <input type="radio" data-target="branch" name="branch" value="1">
                年単位
              </label>

            </td>

            <td class="col-3">

              <label>
                <input type="radio" data-target="branch" name="branch" value="2">
                月単位
              </label>
            </td>

            <td class="col-3">

              <label>
                <input type="radio" data-target="branch" name="branch" value="3">
                週単位
              </label>
            </td>

            <td class="col-3">

              <label>
                <input type="radio" data-target="branch" name="branch" value="4">
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
              <button class="btn btn-outline-primary common-search-button">
                表示
              </button>
            </td>

          </tr>

          <tr>
            <td colspan="4">

              <div class="contents row justify-content-center d-flex gap-3">

                <button class="btn btn-outline-primary list_graph_change_button w-120px" data-target="1">
                  グラフ
                </button>
        
                <button class="btn btn-outline-primary list_graph_change_button w-120px" data-target="2">
                  表
                </button>                
            
              </div>

            </td>

          </tr>

        </table>

      </div>

     

      <div class="weight_list_area d-none">
        <table class="table weight_log_table"> 
          
            <tr>
              <th>
                <div class="weight_log_table-th">記録ID</div>                
              </th>
              <th>
                <div class="weight_log_table-th">記録日時</div>                
              </th>
              <th>
                <div class="weight_log_table-th">体重</div>
              </th>

              <th>
                <div class="weight_log_table-th">前回比較</div>
              </th>
            </tr>
          
          
            @foreach ($weight_log_t as $index => $item)
                @php
                    $comparison = 0;
                    // 次のレコードが存在する場合のみ比較を行う
                    if (isset($weight_log_t[$index + 1])) {
                        $comparison = $item->weight - $weight_log_t[$index + 1]->weight;

                        $comparison = number_format($comparison, 3); // 小数点3桁まで表示
                    }
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->measure_at }}</td>
                    <td>{{ $item->weight }}</td>
                    <td>{{ $comparison }}</td>
                </tr>
            @endforeach

          
        </table>
      </div>



  </div> 


  
  <div class="weight_graph_area">
    @php 
      $fixedIndex = 1; 
      $display_flg = true;
      $display_index = 0;
    @endphp

    @foreach($get_records as $record)
        @if($record['summary']['count'] > 0)
            <canvas id="mychart{{ $fixedIndex }}"
            class="
            mychart
            @if(!$display_flg) d-none @endif
            "           
            ></canvas>
            @php 
             
              if($display_flg){
                $display_index = $fixedIndex;
              }

              $fixedIndex++; 
              $display_flg = false;
            
            @endphp
        @endif
    @endforeach
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
              <form id='save-form' action="{{ route('user.weight_log.save') }}" method="post" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="measure_at" value="">
                <input type="hidden" name="weight" value="">
                <input type="hidden" name="weight_type" value="">

                <table class="table">
                  <tr>
                    <td class="item-center">
                      <div id="timer"></div>
                    </td>
                  </tr>
          
                  <tr>
                    <td class="item-center">
          
                      <label for="weight_type1">
                        <input type="radio" id="weight_type1" name="weight_type_radio" value="1" checked >
                        kg
                      </label>


                      <label for="weight_type2">
                        <input type="radio" id="weight_type2" name="weight_type_radio" value="2">
                        pound
                      </label>

                      
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




$(document).ready(function () {


  @php $fixedIndex = 1; @endphp
  @foreach($get_records as $record)
    @if($record['summary']['count'] == 0)
      // count=0の分は対応するラジオボタンを無効化
      $('input[name="branch"][value="{{ $fixedIndex }}"]').prop('disabled', true).addClass('disabled-branch-radio');
      @php $fixedIndex++; @endphp
      @continue
    @endif

    window['labels{{ $fixedIndex }}'] = @json($record['datas']['labels']);
    window['weights{{ $fixedIndex }}'] = @json($record['datas']['weights']);
    window['label{{ $fixedIndex }}'] = @json($record['summary']['user_name']);
    window['min{{ $fixedIndex }}'] = {{ $record['summary']['min_weight'] }};
    window['max{{ $fixedIndex }}'] = {{ $record['summary']['max_weight'] }};
    window['step{{ $fixedIndex }}'] = {{ $record['summary']['step_size'] }};

    @php $fixedIndex++; @endphp
  @endforeach



  let initialIndex = {{ $display_index }};
  $('input[name="branch"][value="' + initialIndex + '"]').prop('checked', true);
  showChart(initialIndex);

  $('input[name="branch"]').on('change', function () {
    let value = $(this).val();
    showChart(value);
  });

  // function showChart(index) {
  //   // すべてのチャートを非表示
  //   $('.mychart').addClass('d-none');

  //   const target = $('#mychart' + index);
  //   if (target.length === 0) return;

  //   // 該当チャートを表示
  //   target.removeClass('d-none');

  //   // すでに描画済みなら何もしない
  //   if (target.data('initialized')) return;

  //   // Chart.js用の変数が揃っているか確認
  //   if (
  //     typeof window['labels' + index] === 'undefined' ||
  //     typeof window['weights' + index] === 'undefined' ||
  //     typeof window['label' + index] === 'undefined' ||
  //     typeof window['min' + index] === 'undefined' ||
  //     typeof window['max' + index] === 'undefined' ||
  //     typeof window['step' + index] === 'undefined'
  //   ) {
  //     console.warn(`Chartデータが不足しています (index: ${index})`);
  //     return;
  //   }

  //   const ctx = target[0].getContext('2d');

  //   new Chart(ctx, {
  //     type: 'line',
  //     data: {
  //       labels: window['labels' + index],
  //       datasets: [
  //         {
  //           label: window['label' + index],
  //           data: window['weights' + index],
  //           borderColor: '#f1a150',
  //           tension: 0.1,
  //         },
  //       ],
  //     },
  //     options: {
  //       responsive: true,
  //       // maintainAspectRatio: true,
  //       maintainAspectRatio: false,
  //       scales: {
  //         x: {
  //           ticks: {
  //             maxRotation: 45,  // 最大45度回転
  //             minRotation: 45,  // 最小45度回転
  //             autoSkip: true,   // ラベルを間引く
  //           },
  //         },
  //         y: {
  //           ticks: {
  //             beginAtZero: true,
  //             min: window['min' + index],
  //             max: window['max' + index],
  //             stepSize: window['step' + index],
  //           },
  //         },
  //       },
  //       plugins: {
  //         legend: {
  //           labels: {
  //             color: '#f1a150',
  //           },
  //         },
  //       },
  //     },
  //   });



    

  //   target.data('initialized', true); // 初期化済みマーク
  // }





  function showChart(index) {
  // すべてのチャートを非表示
  $('.mychart').addClass('d-none');

  const target = $('#mychart' + index);
  if (target.length === 0) return;

  // 該当チャートを表示
  target.removeClass('d-none');

  // すでに描画済みなら何もしない
  if (target.data('initialized')) return;

  // Chart.js用の変数が揃っているか確認
  if (
    typeof window['labels' + index] === 'undefined' ||
    typeof window['weights' + index] === 'undefined' ||
    typeof window['label' + index] === 'undefined' ||
    typeof window['min' + index] === 'undefined' ||
    typeof window['max' + index] === 'undefined' ||
    typeof window['step' + index] === 'undefined'
  ) {
    console.warn(`Chartデータが不足しています (index: ${index})`);
    return;
  }

  const ctx = target[0].getContext('2d');

  // 横軸ラベル数を取得
  const labelCount = window['labels' + index].length;

  // 1ラベルあたりの幅(px)の目安（必要に応じて調整してください）
  const perLabelWidth = 60;

  // 最小横幅(px)
  const minWidth = 600;

  // 計算したキャンバス幅（最低幅かラベル数に応じた幅の大きい方）
  const canvasWidth = Math.max(minWidth, labelCount * perLabelWidth);

  // canvas要素の幅を動的にセット
  target.attr('width', canvasWidth);

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: window['labels' + index],
      datasets: [
        {
          label: window['label' + index],
          data: window['weights' + index],
          borderColor: '#f1a150',
          tension: 0.1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          ticks: {
            maxRotation: 45,
            minRotation: 45,
            autoSkip: true,
          },
        },
        y: {
          ticks: {
            beginAtZero: true,
            min: window['min' + index],
            max: window['max' + index],
            stepSize: window['step' + index],
          },
        },
      },
      plugins: {
        legend: {
          labels: {
            color: '#f1a150',
          },
        },
      },
    },
  });

  target.data('initialized', true); // 初期化済みマーク
}










  
});





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


  // ラジオボタンの変更イベントを監視
  $('input[name="weight_type_radio"]').on('change', function() {

    // 選択されたラジオボタンの値を取得
    var selectedValue = $('input[name="weight_type_radio"]:checked').val();
     // 表示用の文字列を決定
    var display;
    if (selectedValue == 1) {
        display = "kg";
    } else {
        display = "pound";
    }
    
    // <span>要素の値を更新
    $('.display_weight_type').text(display);    

  });
 

  $('.save-modal-open').click(function(){
        
    $('.error_message_area').html("");
    $("#save-modal .is-invalid").removeClass('is-invalid');

    $('input[name="measure_at"]').val("");
    $('input[name="weight"]').val("");
    $('input[name="weight_type"]').val("");    
    // モーダルを表示する        
    $("#save-modal").modal('show');
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

    let f = $('#save-form');

    clear_error_message(".error_message_area");
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
        $('.error_message_area').html(errorsHtml);

    });

  });






  



</script>


@endsection

