@extends('web.common.layouts_app')

@section('pagehead')
@section('title', 'weight_management')  

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

.weight_type-select{
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

.item-center{
        display: flex;
        justify-content: center; /*左右中央揃え*/
        align-items: center;     /*上下中央揃え*/
    }
</style>

<div class="mt-3 text-center container">

  
  <div class="contents row justify-content-center p-0">

    <div class="card col-12 col-sm-10 col-md-9 col-lg-8 col-xl-7">
      
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
              name="weight_type"  
              value="1"
              data-value="1"
              class="weight_type-checkbox d-none"
            >

            <label id="weight_type-label2" 
              for="weight_type2" 
              class="weight_type-label item-center">pound
            </label>
            <input type="radio" 
              id="weight_type2"
              name="weight_type" 
              value="2"       
              data-value="2"                         
              class="weight_type-checkbox d-none"
            >
          </td>                

          
        </tr>

        <tr>
          <td class="text-center">
            <input type="text" maxlength="3">
            <span class="comma">.</span>
            <input type="text" maxlength="3">
            <span class="display_weight_type">kg</span>
            
          </td>    
        </tr> 

        <tr>
          <td class="text-end">
            <button type="button" class="btn btn-success" data-bs-toggle='modal' data-bs-target='#save-modal'>登録</button>            
          </td>
        </tr> 

      </table>

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
              <form id='save-form' action="{{ route('web.weight_log.save') }}" method="post" enctype="multipart/form-data">

                  <input type="hidden" name="time" value="">
                  <input type="hidden" name="weight" value="">
      
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
                  <button type="button" id="save-button" class="btn btn-success">登録</button>
                  <button type="button" id="" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
              </div>
          </div>
      </div>

  </div>
</div>



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


  $(document).on("change", ".weight_type-checkbox", function (e) {

    var value = $(this).data('value');

    // 反対の値を取得
    var opposite = (value == 1) ? 2 : 1;

    // クラスをリセット
    $(".weight_type-label").removeClass('weight_type-select');

    // 選択されたラジオボタンのラベルにクラスを追加
    $("#weight_type-label" + value).addClass('weight_type-select');

      

  });



  $('#save-modal').on('show.bs.modal', function(e) {

    var time = document.getElementById('timer').textContent;
    var weight = 89.214;

    $('input[name="time"]').val(time);
    $('input[name="weight"]').val(weight);

    $('#display_time').text(time);
    $('#display_weight').text(weight);

  });

</script>


@endsection

