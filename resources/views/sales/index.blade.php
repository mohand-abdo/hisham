@extends('layouts.app')

@section('title','فواتير المبيعات المستحقة')

@section('content')
<div class="row">
    <div class="col-8">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">فواتير المبيعات المستحقة</h6>
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
                                    <th scope="col">تاريخ الفاتورة</th>
                                    <th scope="col">قيمة الفاتورة</th>
                                    <th scope="col">وسيلة الدفع</th>
                                    <th scope="col" class="text-center w-25">التحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $index => $sal)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{ 'His-'.$sal->id.'-RT'}}</td>
                                        <td>{{ date_format($sal->created_at,'Y-m-d') }}</td>
                                        <td>{{ number_format($sal->total_price) }}</td>
                                        <td>{{ $sal->inv_type == 1 ? 'كاش' : ( $sal->inv_type == 2 ? 'البنك' : 'اجل' ) }}</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-info btn-sm rounded-circle pur_det" title="تفاصيل" data-url="{{ route('sales.show',$sal->id)}}" data-method="GET" data-original-title="{{__('dashboard.print')}}">
                                                <i class="fa fa-link"></i>
                                            </a>
                                            <a href="{{route('sale.print',$sal->id)}}" class="btn btn-success btn-sm rounded-circle" title="طباعة" data-toggle="tooltip" data-original-title="{{__('dashboard.print')}}">
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
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">تفاصيل الفواتير</h6>
            </div>
            <div class="card-body">
                <div id="show-product">
                    <div class="table-responsive">
                        @if ($sale != '')
                            <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">اسم المنتج</th>
                                        <th scope="col">السعر</th>
                                        <th scope="col">الكمية</th>
                                        <th scope="col">الاجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale->items as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1}}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ number_format($item->pivot->item_price) }}</td>
                                            <td>{{ $item->pivot->qty }}</td>
                                            <td>{{ number_format($item->pivot->item_price * $item->pivot->qty) }}</td>
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
    </div>
</div>
@endsection

@section('js')
    <script>
        $('body').on('click','.pur_det',function(){
            var url = $(this).data('url'),
                method = $(this).data('method');
            $.ajax({
                method : method,
                url : url,
                success: function (data) {
                    $('#show-product .table-responsive').remove();
                    $('#show-product').append(data);
                }
            });
        });

        $('.table-right').DataTable({
            "language":{
                "url":"assets/js/arabic.json"
            },
	    });
    </script>
@endsection
