@extends('layouts.app')

@section('title','لــوحة التحكم')


@section('content')
<div class="row">
    @component('components.small_card',['color' => 'primary','title'=> 'المشتريات المستحقة','money'=>number_format($total_purchase_cash),'icon'=>'cart-plus'])@endcomponent
    @component('components.small_card',['color' => 'success','title'=> 'المشتريات غيرالمستحقة','money'=>number_format($total_purchase_uncash),'icon'=>'calendar-minus'])@endcomponent
    @component('components.small_card',['color' => 'info','title'=> 'المبيعات المستحقة','money'=>number_format($total_sale_cash),'icon'=>'cart-arrow-down'])@endcomponent
    @component('components.small_card',['color' => 'warning','title'=> 'المبيعات غيرالمستحقة','money'=>number_format($total_sale_uncash),'icon'=>'calendar-plus'])@endcomponent
</div>

<div class="row">
    @component('components.small_card',['color' => 'primary','title'=> 'الديون الي لك','money'=>number_format($total_clients),'icon'=>'plus-circle'])@endcomponent
    @component('components.small_card',['color' => 'danger','title'=> 'الديون التي عليك','money'=>number_format($total_suppliers),'icon'=>'minus-circle'])@endcomponent
    @component('components.small_card',['color' => 'info','title'=> 'الخزينة','money'=>number_format($total_save),'icon'=>'briefcase'])@endcomponent
    @component('components.small_card',['color' => 'success','title'=> 'البنك','money'=>number_format($total_bank),'icon'=>'university'])@endcomponent
</div>

<div class="row">
    <!-- Area Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary chart_line">{{$setting->linechart == 0 ? 'المبيعات' : 'المشتريات'}}</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item line_chart" id="purchase_line" data-url="{{route('chart.line')}}" href="#">المشتريات</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item line_chart" id="sale_line" data-url="{{route('chart.line1')}}" href="#">المبيعات</a>                            
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary chart_pie">{{$setting->piechart == 0 ? 'المبيعات' : 'المشتريات'}}</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item pie_chart" id="purchase_pie" data-url="{{route('chart.pie')}}" href="#">المشتريات</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item pie_chart" id="sale_pie" data-url="{{route('chart.pie1')}}" href="#">المبيعات</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="myPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    <span class="mr-2 cash_pie">
                        <i class="fas fa-circle text-primary "></i> {{$setting->piechart == 0 ? 'المبيعات المستحقة' : 'المشتريات المستحقة'}}
                    </span>
                    <span class="mr-2 uncash_pie">
                        <i class="fas fa-circle text-success "></i> {{$setting->piechart == 0 ? 'المبيعات غير المستحقة' : 'المشتريات غير المستحقة'}}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
  {!! Html::script('assets/vendor/chartjs/Chart.min.js') !!}
  <!-- Page level custom scripts -->
  {!! Html::script('assets/js/demo/chart-area-demo.js') !!}
  {!! Html::script('assets/js/demo/chart-pie-demo.js') !!}
  <script>
      // Area Chart Example
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["يناير", "فبراير", "مارس", "ابريل", "مايو", "يونيو", "يوليو", "اغسطس", "سبتمبر", "اكتوبر", "نوفمبر", "ديسمبر"],
        datasets: [{
        label: "جنيه",
        lineTension: 0.3,
        backgroundColor: "rgba(78, 115, 223, 0.05)",
        borderColor: "rgba(78, 115, 223, 1)",
        pointRadius: 3,
        pointBackgroundColor: "rgba(78, 115, 223, 1)",
        pointBorderColor: "rgba(78, 115, 223, 1)",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,
        data: [
            @foreach($charts as $chart)
                @if(is_array($chart))
                    {{ $chart['price']}},
                @else
                    {{$chart}},
                @endif
            @endforeach
        ],
        }],
    },
    options: {
        maintainAspectRatio: false,
        layout: {
        padding: {
            left: 10,
            right: 25,
            top: 25,
            bottom: 0
        }
        },
        scales: {
        xAxes: [{
            time: {
            unit: 'date'
            },
            gridLines: {
            display: false,
            drawBorder: false
            },
            ticks: {
            maxTicksLimit: 7
            }
        }],
        yAxes: [{
            ticks: {
            maxTicksLimit: 5,
            padding: 10,
            // Include a dollar sign in the ticks
            callback: function(value, index, values) {
                return ' ج ' + number_format(value);
            }
            },
            gridLines: {
            color: "rgb(234, 236, 244)",
            zeroLineColor: "rgb(234, 236, 244)",
            drawBorder: false,
            borderDash: [2],
            zeroLineBorderDash: [2]
            }
        }],
        },
        legend: {
        display: false
        },
        tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        titleMarginBottom: 10,
        titleFontColor: '#6e707e',
        titleFontSize: 14,
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        intersect: false,
        mode: 'index',
        caretPadding: 10,
        callbacks: {
            label: function(tooltipItem, chart) {
            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
            return number_format(tooltipItem.yLabel) + ': ' +datasetLabel ;
            }
        }
        }
    }
    });

    $('body').on('click','.line_chart',function(e){
        e.preventDefault();
        var url = $(this).data('url')
            id = $(this).attr('id');
            if(id == 'purchase_line')
            {
                $('h6.chart_line').text('المشتريات');
            }else{
                $('h6.chart_line').text('المبيعات');
            }
        $.ajax({
            method : 'GET',
            url : url,
            success: function (data) {
                $('#myAreaChart').remove();
                $('.chart-area').append(data);
            }
        });
    });

    // Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
      @if($setting->piechart == 0)
      labels: ["المبيعات المستحقة","المبيعات غير المستحقة"],
      @else
      labels: ["المشتريات المستحقة", "المشتريات غير المستحقة"],
      @endif
    datasets: [{
      data: [{{$count_cash }}, {{$count_uncash}}],
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: false
    },
    cutoutPercentage: 80,
  },
});

$('body').on('click','.pie_chart',function(e){
        e.preventDefault();
        var url = $(this).data('url')
            id = $(this).attr('id');
            if(id == 'purchase_pie')
            {
                $('h6.chart_pie').text('المشتريات');
                $('span.cash_pie').html('<i class="fas fa-circle text-primary "></i> المشتريات المستحقة')
                $('span.uncash_pie').html('<i class="fas fa-circle text-success "></i> المشتريات الغير المستحقة')
            }else{
                $('h6.chart_pie').text('المبيعات');
                $('span.cash_pie').html('<i class="fas fa-circle text-primary "></i> المبيعات المستحقة')
                $('span.uncash_pie').html('<i class="fas fa-circle text-success "></i> المبيعات الغير المستحقة')
            }
        $.ajax({
            method : 'GET',
            url : url,
            success: function (data) {
                $('#myPieChart').remove();
                $('.chart-pie').append(data);
            }
        });
    });

  </script>
@endsection
