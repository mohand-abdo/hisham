@extends('layouts.app')

@section('title','فواتير المبيعات التي تجاوزت موعد الاستحقاق')

@section('content')
<div class="row">
    <div class="col-8">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">فواتير المبيعات التي تجاوزت موعد الاستحقاق</h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="table-responsive ">
                    @if($sales->count() > 0)
                        <table class="table table-right table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">رقم الفاتورة</th>
                                    <th scope="col">تاريخ الاستحقاق</th>
                                    <th scope="col">قيمة الفاتورة</th>
                                    <th scope="col">العميل</th>
                                    <th scope="col" class="text-center w-25">التحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $index => $sale)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{ 'His-'.$sale->id.'-RT'}}</td>
                                        <td>{{ $sale->pay_date }}</td>
                                        <td>
                                            @if ($sale->uncash_type == 1)
                                                {{ number_format($sale->total_price) }}                                                    
                                            @else
                                                @php client_account($sale) @endphp
                                                {{ number_format($sale->total_price) }}
                                            @endif
                                        </td>
                                        <td>{{ $sale->client->name }}</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-info btn-sm rounded-circle cli_det" title="تفاصيل" data-url="{{ route('sale.client',$sale->id)}}" data-method="GET" data-original-title="{{__('dashboard.print')}}">
                                                <i class="fa fa-link"></i>
                                            </a>
                                            <a href="{{route('sale.print',$sale->id)}}" class="btn btn-success btn-sm rounded-circle" title="طباعة" data-toggle="tooltip" data-original-title="{{__('dashboard.print')}}">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center">لاتوجد بيانات</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">تفاصيل العميل</h6>
            </div>
            <div class="card-body">
                <div id="show-client">
                    <div class="table-responsive">
                        @if ($client != '')
                            <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                                <thead>
                                    <tr>
                                        <th scope="col">اسم العميل</th>
                                        <th scope="col">التلفون</th>
                                        <th scope="col">العنوان</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $client->client->name }}</td>
                                        <td>{{ implode($client->client->phone ,'-') }}</td>
                                        <td>{{ $client->client->address }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <div class="text-center">لاتوجد بيانات</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <script>
        $('body').on('click','.cli_det',function(){
            var url = $(this).data('url'),
                method = $(this).data('method');
            $.ajax({
                method : method,
                url : url,
                success: function (data) {
                    $('#show-client .table-responsive').remove();
                    $('#show-client').append(data);
                }
            });
        });

        $('.table-right').DataTable({
            "language":{
                "url":"{{ asset('assets/js/arabic.json') }} "
            },
	    });
    </script>
@endsection
