@extends('layouts.app')

@section('css')
    {!! Html::style('assets/vendor/date-picker/css/date-picky.css') !!}
    <style>
        .read_only{
            background-color: #fff !important;
        }
    </style>
@endsection

@section('title','فواتير المشتريات المستحقة')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">فواتير المشتريات المستحقة</h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="col-12">
                    <form class="d-flex justify-content-between mb-3" method="post" action="{{route('report.purchase.cash.search')}}"  >
                        @csrf
                        <div class="col-4">
                            <input type="text" id="from_date" class="form-control ml-5 read_only"  name="from_date" value="{{ old('from_date') ?? $from ?? date('Y-m-d')}}" readonly autocomplete="off" placeholder="من"/>
                        </div>
                        <div class="col-4">
                            <input type="text" id="to_date" class="form-control ml-5 read_only"  name="to_date" value="{{ old('to_date') ?? $to ?? date('Y-m-d')}}" readonly autocomplete="off" placeholder="الى"/>
                        </div>
                        <select class="form-control ml-5" name="cash_type">
                            <option value="" >---اختر---</option>
                            <option value="1" {{ $cash_type == 1 ? 'selected':''}}>كاش</option>
                            <option value="2" {{ $cash_type == 2 ? 'selected':'' }}>بنك</option>
                        </select>
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </form>
                </div>
                <div class="table-responsive ">
                    @if($purchases->count() > 0)
                        <table class="table table-right table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">رقم الفاتورة</th>
                                    <th scope="col">تاريخ الفاتورة</th>
                                    <th scope="col">قيمة الفاتورة</th>
                                    <th scope="col">وسيلة الدفع</th>
                                    <th scope="col">المورد</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($purchases as $index => $purchase)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{ 'His-'.$purchase->id.'-RT'}}</td>
                                        <td>{{ date_format($purchase->created_at,'Y-m-d') }}</td>
                                        <td>{{ number_format($purchase->total_price) }}</td>
                                        <td>{{ $purchase->inv_type == 1 ? 'كاش' : ( $purchase->inv_type == 2 ? 'البنك' : 'اجل' ) }}</td>
                                        <td>{{$purchase->supplier()->exists() ? $purchase->supplier->name : 'غير معروف'}}</td>
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
