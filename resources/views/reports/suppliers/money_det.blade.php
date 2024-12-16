@extends('layouts.app')

@section('title')
    تفاصيل مديونات {{ $suppliers[0]->supplier->name }}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">تفاصيل مديونات {{ $suppliers[0]->supplier->name }}  </h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="table-responsive ">
                    @if($suppliers->count() > 0)
                        <table class="table table-right table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">رقم الفاتورة</th>
                                    <th scope="col">المبلغ </th>
                                    <th scope="col"> نوع الاجل</th>
                                    <th scope="col">التاريخ</th>
                                    <th scope="col">تسديد</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $index => $supplier)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{$supplier->purchase_id ? 'His-'.$supplier->purchase_id.'-RT':'بدون'}}</td>
                                        <td>{{ number_format($supplier->sub_price)}}</td>
                                        <td>{{ $supplier->cash_type==0?'غير مشروط':'مشروط'}}</td>
                                        <td>{{ date_format($supplier->created_at,'Y-m-d') }}</td>
                                        <td>
                                            <a href="{{route('purchase.pay', $supplier->purchase_id)}}" class="btn btn-warning btn-sm rounded-circle" title="سداد المتاخرات" data-toggle="tooltip" data-original-title="{{__('dashboard.edit')}}">
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
    <script>
        $('.table-right').DataTable({
            "language":{
                "url":"{{ asset('assets/js/arabic.json') }} "
            },
	    });

        
    </script>
@endsection
