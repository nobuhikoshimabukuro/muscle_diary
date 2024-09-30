@extends('web.common.layouts_app')


@section('pagehead')
@section('title', 'グラフテスト1')

@endsection
@section('content')




<style>



</style>



    <div class="mt-3 text-center container mb-5"> 

        <a href="https://qiita.com/Haruka-Ogawa/items/59facd24f2a8bdb6d369" target="_blank">参考サイト1</a>
        <a href="https://qiita.com/matyahiko2831/items/30c09416dcb334a5576f" target="_blank">参考サイト2</a>



        
        
        <canvas id="myChart"></canvas>




        

    </div>



@endsection

@section('pagejs')



<script type="text/javascript">

    var month_array = @json($month_array);
    var income_array = @json($income_array);
    var spending_array = @json($spending_array);
    var set_value_array = @json($set_value_array);
    
    var difference_array = @json($difference_array);
    
    
    var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    // labels: ['2018/01', '2018/01/02', '2018/01/03', '2018/01/04', '2018/01/05', '2018/01/06', '2018/01/07'],
                    labels: month_array,
                    datasets: [{
                        label: '収入',
                        type: "line",
                        fill: false,
                        // data: [10000, 11000, 15000, 12000, 9000, 12000, 13000],
                        data: income_array,
                        borderColor: "rgb(154, 162, 235)",
                        yAxisID: "y-axis-1",
                    }, {
                        label: '支出',
                        type: "line",
                        fill: false,
                        // data: [8000, 9000, 10000, 9000, 6000, 8000, 7000],
                        data: spending_array,
                        borderColor: "rgb(54, 162, 235)",
                        yAxisID: "y-axis-1",
                    }, {
                        label: '差額',
                        // data: [100, 110, 110, 110, 110, 110, 110],
                        data: difference_array,
                        borderColor: "rgb(255, 99, 132)",
                        backgroundColor: "rgba(255, 99, 132, 0.2)",
                        yAxisID: "y-axis-2",
                    }]
                },
                options: {
                    tooltips: {
                        mode: 'nearest',
                        intersect: false,
                    },
                    responsive: true,
                    scales: {
                        yAxes: [{
                            id: "y-axis-1",
                            type: "linear",
                            position: "left",
                            ticks: {
                                max: set_value_array["max_value1"],
                                min: set_value_array["minimum_value1"],                            
                                stepSize: set_value_array["base_value1"]
                            },
                        }, {
                            id: "y-axis-2",
                            type: "linear",
                            position: "right",
                            ticks: {
                                max: set_value_array["max_value2"],
                                min: set_value_array["minimum_value2"],
                                stepSize: set_value_array["base_value2"]
                            },
                            gridLines: {
                                drawOnChartArea: false,
                            },
                        }],
                    },
                }
            });
    
    
    
    </script>

@endsection