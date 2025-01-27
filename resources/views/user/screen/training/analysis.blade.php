@extends('user.common.layouts_app')

@section('pagehead')
@section('title', 'training analysis')  

@endsection
@section('content')

@php    

  $labels = $get_record_training_time['datas']['labels'];
  $elapsed_times = $get_record_training_time['datas']['elapsed_times'];
  $summary = $get_record_training_time['summary'];
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

.weight_graph_area{
  overflow-y: auto; 
  white-space: nowrap;
  display: flex;
  justify-content: center;
  align-items: center;
}

#mychart{
  min-width: 1000px; 
  min-height: 400px;
}
</style>

<div class="mt-3 text-center container">
  
  <div class="contents row justify-content-center p-0">
      
     



  </div> 


  @if($count > 0)
    <div class="weight_graph_area">  
      <canvas id="mychart"></canvas>
    </div>
  @endif




</div>



{{-- 再ログインモーダルの読み込み --}}
@include('user/common/login_again_modal')

@endsection

@section('pagejs')


<script type="text/javascript">


  


  @if($count > 0)

  //   //グラフ作成
  //   var labels = @json($labels);  // Days or date labels
  //   var elapsed_times = @json($elapsed_times);  // Weight data for each corresponding label

  //   var ctx = document.getElementById('mychart');

  //   var myChart = new Chart(ctx, {
  //   type: 'line',
  //   data: {
  //     labels: labels,
  //     datasets: [{
  //       label: '{{$summary['user_name']}}',
  //       data: elapsed_times,
  //       borderColor: '#f1a150',
  //       tension: 0.1,
  //     }],
  //   },
  //   options: {
  //     // responsive: false,
  //     responsive: false,
  //     scales: {
  //       y: {  // v3.x以降では 'y' に変更されました
  //         ticks: {
  //           beginAtZero: true,
  //           min: '{{ $summary['min_elapsed_time'] }}',  // 最小値
  //           max: '{{ $summary['max_elapsed_time'] }}',  // 最大値
  //           stepSize: {{ $summary['step_size'] }},  // メモリの間隔
  //         },
  //       },
  //     },
  //     plugins: {
  //       legend: {
  //         labels: {
  //           color: '#f1a150'  // ここで凡例のテキストカラーを指定
  //         }
  //       }
  //     }
  //   },
  // });




// 日付ラベル
var labels = ["2025/01/01","2025/01/02","2025/01/03","2025/01/04","2025/01/06","2025/01/06","2025/01/07","2025/01/08","2025/01/10","2025/01/11","2025/01/11","2025/01/12","2025/01/13","2025/01/14","2025/01/15","2025/01/16","2025/01/17","2025/01/18","2025/01/19","2025/01/20","2025/01/22","2025/01/23","2025/01/23","2025/01/24"];  

// elapsed_times を秒に変換する関数
function timeToSeconds(time) {
    var parts = time.split(':');
    return parseInt(parts[0]) * 3600 + parseInt(parts[1]) * 60 + parseInt(parts[2]);
}

// 時間データ（秒に変換したもの）
var elapsed_times = [
    "01:23:01", "02:08:47", "01:00:31", "01:21:40", "02:28:49", 
    "01:13:20", "01:29:51", "02:12:16", "02:20:09", "02:04:15", 
    "01:16:59", "02:02:06", "02:22:46", "01:08:08", "02:18:59", 
    "01:02:20", "02:25:49", "02:31:29", "02:19:16", "02:22:16", 
    "02:18:12", "02:04:00", "01:30:22", "01:16:04"
].map(time => timeToSeconds(time));  // 秒に変換

// 秒数を時:分:秒形式に変換する関数
function secondsToTime(seconds) {
    var hours = Math.floor(seconds / 3600);
    var minutes = Math.floor((seconds % 3600) / 60);
    var remainingSeconds = seconds % 60;
    return hours + ':' + ('00' + minutes).slice(-2) + ':' + ('00' + remainingSeconds).slice(-2);
}

// グラフ作成
var ctx = document.getElementById('mychart');

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: '1111さん',
            // グラフのデータは秒数のまま
            data: elapsed_times,
            borderColor: '#f1a150',
            tension: 0.1,
        }],
    },
    options: {
        responsive: false,
        scales: {
            y: {  
                beginAtZero: true,
                min: 0,  // 秒数で設定
                max: timeToSeconds("03:00:00"),  // 秒数で設定
                stepSize: 3600,  // 1時間ごとに表示
                ticks: {
                    callback: function(value) {
                        // 秒数から「時:分:秒」形式に変換
                        return secondsToTime(value);
                    }
                }
            },
        },
        plugins: {
            legend: {
                labels: {
                    color: '#f1a150'  // 凡例のテキストカラー
                }
            }
        }
    },
});




  

  @endif

</script>


@endsection

