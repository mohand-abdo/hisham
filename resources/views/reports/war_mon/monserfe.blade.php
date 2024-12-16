@extends('layouts.app')

@section('css')
    {!! Html::style('assets/vendor/date-picker/css/date-picky.css') !!}
    <style>
        .read_only{
            background-color: #fff !important;
        }
    </style>
@endsection

@section('title','حساب المنصرفات')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">حساب المنصرفات</h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="col-12">
                    <form class="d-flex justify-content-between mb-3" method="post" action="{{route('report.monserfe.search')}}"  >
                        @csrf
                        <div class="col-5">
                            <input type="text" id="from_date" class="form-control ml-5 read_only"  name="from_date" value="{{ old('from_date') ?? $from }}"readonly autocomplete="off" placeholder="من"/>
                        </div>
                        <div class="col-5">
                            <input type="text" id="to_date" class="form-control ml-5 read_only"  name="to_date" value="{{ old('to_date') ?? $to }}" readonly autocomplete="off" placeholder="الى"/>
                        </div>
                        <select class="form-control ml-5" name="note">
                            <option value="" >---اختر---</option>
                            @foreach ($expenses as $expense)
                                <option value="{{$expense->id}}" {{ $note == $expense->id ? 'selected':''}}>{{$expense->name}}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">بحث</button>
                    </form>
                </div>
                <div class="table-responsive ">
                    @if($monserfes->count() > 0)
                        <table class="table table-right table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">المبلغ</th>
                                    <th scope="col">وسيلة الدفع</th>
                                    <th scope="col">اسم البنك </th>
                                    <th scope="col">البيان</th>
                                    <th scope="col">التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monserfes as $index => $monserfe)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{ number_format($monserfe->from) }}</td>
                                        <td>{{ $monserfe->cash == 1 ? 'كاش' : 'البنك'}}</td>
                                        <td>{{ $monserfe->bank_id ? $monserfe->bank->name : '-'}}</td>
                                        <td>{{ $monserfe->expense->name}}</td>
                                        <td>{{ date_format($monserfe->created_at,'Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center">لاتوجد بيانات</div>
                    @endif
                </div>
                <h5 class="mt-3 mr-3"><span class="font-weight-bold text-primary">المنصرفات</span> : <span class="total-price">{{number_format($total)}} جنيه</span></h5>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    {!! Html::script('assets/vendor/date-picker/js/date-picky.min.js') !!}
    <script>
        $('.table-right').DataTable({
            "language":{
                "url":"{{ asset('assets/js/arabic.json') }} "
            },
	    });

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

    </script>
@endsection
