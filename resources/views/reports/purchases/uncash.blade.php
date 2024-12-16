@extends('layouts.app')

@section('css')
    {!! Html::style('assets/vendor/date-picker/css/date-picky.css') !!}
@endsection

@section('title','فواتير المشتريات الغير مستحقة')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">فواتير المشتريات الغير مستحقة</h6>
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
                                    <th scope="col">تاريخ الفاتورة</th>
                                    <th scope="col">تاريخ الاستحقاق</th>
                                    <th scope="col">قيمة الفاتورة</th>
                                    <th scope="col">المدفوع</th>
                                    <th scope="col">المتبقي</th>
                                    <th scope="col">المورد</th>
                                    <th scope="col">تفاصيل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $index => $purchase)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{ 'His-'.$purchase->id.'-RT'}}</td>
                                        <td>{{ date_format($purchase->created_at,'Y-m-d') }}</td>
                                        <td>{{ $purchase->pay_date }}</td>
                                        <td>{{ number_format($purchase->total_price) }}</td>
                                        <td>{{ number_format($purchase->sup_account->pay_price) }}</td>
                                        <td>{{ number_format($purchase->sup_account->sub_price) }}</td>
                                        <td>{{$purchase->supplier()->exists() ? $purchase->supplier->name : 'غير معروف'}}</td>
                                        <td>
                                            <a href="{{route('purchase.pay',$purchase->id)}}" class="btn btn-warning btn-sm rounded-circle" title="سداد المتاخرات" data-toggle="tooltip" data-original-title="{{__('dashboard.edit')}}">
                                                    <i class="fa fa-dollar-sign"></i>
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
</div>
@endsection

@section('js')
    {!! Html::script('assets/vendor/date-picker/js/date-picky.min.js') !!}
    <script>
        $('#from_date').datePicky({
            multipleDate : false,
            format : 'YYYY-MM-DD',
            activation: 'onClick',
            showCurrentDateButton: false,
            // showCurrentDate: false,

        });
        
        $('#to_date').datePicky({
            multipleDate : false,
            format : 'YYYY-MM-DD',
            activation: 'onClick',
            showCurrentDateButton: false,
            // showCurrentDate: false,

        });

        $('.table-right').DataTable({
            "language":{
                "url":"{{ asset('assets/js/arabic.json') }} "
            },
	    });
    </script>
@endsection
