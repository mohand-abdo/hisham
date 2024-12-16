@extends('layouts.app')

@section('title','فواتيــر المشتريات التي تجاوزت موعد الاستحقاق')

@section('content')
<div class="row">
    <div class="col-8">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">فواتير المشتريات التي تجاوزت موعد الاستحقاق</h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="table-responsive ">
                    @if($purchases->count() > 0)
                        <table class="table table-right table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">رقم الفاتورة</th>
                                    <th scope="col">تاريخ الاستحقاق</th>
                                    <th scope="col">قيمة الفاتورة</th>
                                    <th scope="col">المورد</th>
                                    <th scope="col" class="text-center w-25">التحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $index => $purchase)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{ 'His-'.$purchase->id.'-RT'}}</td>
                                        <td>{{ $purchase->pay_date }}</td>
                                        <td>
                                            @if ($purchase->uncash_type == 1)
                                                {{ number_format($purchase->total_price) }}                                                    
                                            @else
                                                @php supplier_account($purchase) @endphp
                                                {{ number_format($purchase->total_price) }}
                                            @endif
                                        </td>
                                        <td>{{ $purchase->supplier->name }}</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-info btn-sm rounded-circle sup_det" title="تفاصيل" data-url="{{ route('purchase.supplier',$purchase->id)}}" data-method="GET" data-original-title="{{__('dashboard.print')}}">
                                                <i class="fa fa-link"></i>
                                            </a>
                                            <a href="{{route('purchase.print',$purchase->id)}}" title="طباعة" class="btn btn-success btn-sm rounded-circle" data-toggle="tooltip" data-original-title="{{__('dashboard.print')}}">
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
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">تفاصيل المورد</h6>
            </div>
            <div class="card-body">
                <div id="show-client">
                    <div class="table-responsive">
                        @if ($supplier != '')
                            <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                                <thead>
                                    <tr>
                                        <th scope="col">اسم المورد</th>
                                        <th scope="col">التلفون</th>
                                        <th scope="col">العنوان</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $supplier->supplier->name }}</td>
                                        <td>{{ implode($supplier->supplier->phone ,'-') }}</td>
                                        <td>{{ $supplier->supplier->address }}</td>
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
        $('body').on('click','.sup_det',function(){
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
