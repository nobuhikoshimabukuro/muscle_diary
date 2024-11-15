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

      
        @if(count($training_history_t) > 0)
        <div class="data-display-area">  
            <table class="table data-display-table">

              <tr>
                <th class="text-start">ジム名</th>
                <th class="text-start">日時</th>
                <th class="text-start">合計時間</th>
                <th></th>
              </tr>

              @foreach ($training_history_t as $training_history_info)


                  <tr>
                    <td class="text-start">{{$training_history_info->gym_name}}</td> 

                    <td class="text-start">
                      @if($training_history_info->end_datetime == "")
                        {{$training_history_info->start_datetime}} ※トレーニング中
                      @else
                        {{$training_history_info->start_datetime}}～{{$training_history_info->end_datetime}}
                      @endif
                    </td>

                    <td class="text-start">{{$training_history_info->duration}}</td> 

                    <td class="text-end">
                      <button type="button" class="btn btn-outline-primary training_detail-button" data-target="{{$training_history_info->user_training_count}}" >詳細</button>
                    </td> 
                  </tr>                                            

              @endforeach

            </table>

          </div>
          @endif
        
    </div>
  </div> 



</div>







{{-- 再ログインモーダルの読み込み --}}
@include('user/common/login_again_modal')

@endsection

@section('pagejs')

<script type="text/javascript">


  
  $(document).on("click", ".training_detail-button", function (e) {
      // this の target を取得
      var target = $(this).data('target');      
      
      var url = "{{ route('user.training.detail') }}";
      window.location.href = url + "?user_training_count=" + target;
  });



</script>


@endsection

