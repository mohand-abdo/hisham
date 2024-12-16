@extends('layouts.app')

@section('title','فواتير المبيعات اليومية')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">فواتير المبيعات اليومية</h6>
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
                                    <th scope="col">المورد</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $index => $sale)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{ 'His-'.$sale->id.'-RT'}}</td>
                                        <td>{{ date_format($sale->created_at,'Y-m-d') }}</td>
                                        <td>{{ number_format($sale->total_price) }}</td>
                                        <td>{{ $sale->inv_type == 1 ? 'كاش' : ( $sale->inv_type == 2 ? 'البنك' : 'اجل' ) }}</td>
                                        <td>{{$sale->client()->exists() ? $sale->client->name : 'غير معروف'}}</td>
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
    <script>
        $('.table-right').DataTable({
            "language":{
                "url":"{{ asset('assets/js/arabic.json') }} "
            },
	    });
    </script>
@endsection
