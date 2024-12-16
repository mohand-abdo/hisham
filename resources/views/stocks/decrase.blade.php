@extends('layouts.app')

@section('title','المخازن')


@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right">النواقص</h6>
        </div>
        <div class="card-body">
            @include('includes.messages')
            <div class="table-responsive">
                @if(count($stock_name) > 0)
                    <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">المنتج</th>
                                <th scope="col">المخزن</th>
                                <th scope="col">الكمية</th>
                                <th scope="col" class="text-center w-25">التحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stock_name as $index => $item)
                                <tr>
                                    <td>{{ $index + 1}}</td>
                                    <td>{{$item_name[$index]}}</td>
                                    <td>{{$item}}</td>
                                    <td>{{$qty[$index]}}</td>
                                    <td class="text-center">
                                        <a href="{{route('stock.transform.create')}}" class="btn btn-info btn-sm rounded-circle" title="تحويل" data-toggle="tooltip" data-original-title="{{__('dashboard.edit')}}">
                                            <i class="fa fa-exchange-alt"></i>
                                        </a>
                                        <a href="{{route('purchase.create')}}" class="btn btn-success btn-sm rounded-circle" title="شراء" data-toggle="tooltip" data-original-title="{{__('dashboard.edit')}}">
                                            <i class="fa fa-plus"></i>
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
@endsection

@section('js')
    <script>
        $('.table').DataTable({
            "language":{
                "url":"assets/js/arabic.json"
            },
	    });
    </script>
@endsection