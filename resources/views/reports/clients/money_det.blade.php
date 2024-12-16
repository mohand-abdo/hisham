@extends('layouts.app')

@section('title')
تفاصيل مديونات {{ $clients[0]->client->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">تفاصيل مديونات {{ $clients[0]->client->name }}  </h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="table-responsive ">
                    @if($clients->count() > 0)
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
                                @foreach ($clients as $index => $client)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{$client->sale_id == 0 ? '-': 'His-'.$client->sale_id.'-RT'}}</td>
                                        <td>{{ number_format($client->sub_price)}}</td>
                                        <td>{{ $client->sale_id == 0 ? 'دين' :($client->cash_type== 2 ? 'غير مشروط' : 'مشروط') }}</td>    
                                        <td>{{ date_format($client->created_at,'Y-m-d') }}</td>
                                        <td>
                                            <a href="{{$client->sale_id == 0 ? ( route('client.give_me',$client->id)): (route('sale.pay', $client->sale_id))}}" class="btn btn-warning btn-sm rounded-circle" title="سداد المتاخرات" data-toggle="tooltip" data-original-title="{{__('dashboard.edit')}}">
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
