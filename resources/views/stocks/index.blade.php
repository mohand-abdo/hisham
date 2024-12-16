@extends('layouts.app')

@section('title','المخازن')


@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right">كل المخازن</h6>
            <a href="{{route('stock.create')}}"class="btn btn-primary" style="float:left">اضافة مخزن</a>
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            @include('includes.messages')
            <div class="table-responsive">
                @if($stocks->count() > 0)
                    <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">القسم</th>
                                <th scope="col" class="text-center w-25">التحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stocks as $index => $stock)
                                <tr>
                                    <td>{{ $index + 1}}</td>
                                    <td>{{$stock->name}}</td>
                                    <td class="text-center">
                                        {{-- <a href="{{route('stock.show',$stock->id)}}" class="btn btn-info btn-sm rounded-circle" title="عرض" data-toggle="tooltip" data-original-title="{{__('dashboard.edit')}}">
                                            <i class="fa fa-link"></i>
                                        </a> --}}
                                        <a href="{{route('stock.edit',$stock->id)}}" class="btn btn-warning btn-sm rounded-circle" title="تعديل" data-toggle="tooltip" data-original-title="{{__('dashboard.edit')}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{route('stock.destroy',$stock->id)}}" method="post" style="display:inline-block">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-circle" title="حذف" data-toggle="tooltip" data-original-title="{{__('dashboard.delete')}}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
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