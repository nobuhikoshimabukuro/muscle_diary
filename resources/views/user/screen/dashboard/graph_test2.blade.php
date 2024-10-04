@extends('user.common.layouts_app')


@section('pagehead')
@section('title', 'グラフテスト2')

@endsection
@section('content')




<style>

.alert_dead::before {
    content: "×";
    color: red;        
}

.alert_warning::before{
  content: "▲";
  color: rgb(35, 57, 5);  
  /* animation: flash 2s linear infinite; */
}



@keyframes flash {
	0% {
		opacity: 1;
	}
	50% {
		opacity: 0;
	}
	100% {
		opacity: 1;
	}
}

</style>

    <div class="mt-3 text-center container mb-5"> 

        {{-- <h1>表</h1> --}}
        <table class="table">
          <tr>
            <th>
            </th>
            @foreach ($months as $month)
              <th class="text-end">
                {{$month}}
              </th>
            @endforeach
            <th class="text-end">
              合計
            </th>
          </tr>

          @foreach ($staff_info as $info)

            @php
              $department_name = "";
              foreach ($department_info as $item) {

                if($item->department_id == $info->department_id){
                  $department_name = $item->department_name;
                }

              }
            @endphp

     
            <tr>

              <td>
                <span style="color: {{$info->rgba}}">■</span>
                {{$info->staff_name}}
                <br>
                {{$info->department_name}}
              
              </td>

              @php
                $over_times = $info->over_times;
                $total_over_time = 0;
              @endphp

              @foreach ($over_times as $over_time)

                @php

                  $total_over_time = $total_over_time + $over_time;

                  $add_class = "";
                  if($over_time >= $alert_info->dead_time){
                    $add_class = "alert_dead";
                  }elseif($over_time > $alert_info->warning_time){
                    $add_class = "alert_warning";
                  }

                @endphp
                <td class="text-end">
                  <span class="{{$add_class}}">{{$over_time}}</span>
                </td>
              @endforeach

              <td class="text-end">
                {{$total_over_time}}
              </td>

            </tr>
            
          @endforeach

        </table>
        
        
        {{-- <h1>棒グラフ</h1> --}}
        <canvas id="myBarChart"></canvas>




        

    </div>



@endsection

@section('pagejs')




<script type="text/javascript">

  var months = @json($months);
  var staff_info = @json($staff_info);

  var ctx = document.getElementById("myBarChart");
  var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: {      
    labels: months,
    datasets: [
      @foreach ($staff_info as $index => $staff)
              {
                  label: "{{ $staff->staff_name }}",
                  data: @json($staff->over_times),
                  backgroundColor: "{{ $staff->rgba }}"
              },
      @endforeach
    ]
  },
  options: {
    title: {
      display: true,
      text: '残業時間（H）'
    },
    scales: {
      yAxes: [{
        ticks: {
          suggestedMax: 120,
          suggestedMin: 0,
          stepSize: 5,
          callback: function(value, index, values){
            return  value +  'H';
          }
        }
      }]
    },
    annotation: {
      annotations: [
        {
          type: 'line',
          mode: 'horizontal',
          scaleID: 'y-axis-0',
          value: 60, // 1本目のラインの位置
          borderColor: 'red',
          borderWidth: 2,
          label: {
            enabled: true,
            content: '×',
            position: 'right'
          }
        },
        {
          type: 'line',
          mode: 'horizontal',
          scaleID: 'y-axis-0',
          value: 40, // 2本目のラインの位置
          borderColor: 'yellow', // ラインの色を変える場合
          borderWidth: 1, // 太さを変える
          label: {
            enabled: true,
            content: '▲',
            position: 'right'
          }
        }
      ]
    }
  }
});

</script>



@endsection