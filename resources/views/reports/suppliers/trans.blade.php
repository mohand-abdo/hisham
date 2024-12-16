@extends('layouts.app')

@section('title','كشف حساب الموردين')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">كشف حساب الموردين</h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="col-4 mb-3">
                    <select name="supplier" class="form-control supplier" data-url="{{ route('report.supplier.trans')}}" data-method="POST">
                        @if ($suppliers->count() > 0)
                            <option value="0">اختر مورد</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                            @endforeach
                        @else
                            <option>لا يوجد موردين</option>    
                        @endif
                    </select>
                </div>
                <div class="table-row">
                    <div class="table-responsive ">
                        @if($suppliers_trans->count() > 0)
                            <table class="table table-right table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">من</th>
                                        <th scope="col">الى</th>
                                        <th scope="col">التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suppliers_trans as $index => $supplier_trans)
                                        <tr>
                                            <td>{{ $index + 1}}</td>
                                            <td>{{ $supplier_trans->from ? number_format($supplier_trans->from) : '-' }}</td>
                                            <td>{{ $supplier_trans->to ? number_format($supplier_trans->to) : '-'}}</td>
                                            <td>{{ date_format($supplier_trans->created_at,'Y-m-d') }}</td>
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
        $('.table-right').DataTable({
            "language":{
                "url":"{{ asset('assets/js/arabic.json') }} "
            },
	    });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('change','.supplier',function(){
            var id = $(this).val(),
                url = $(this).data('url'),
                method = $(this).data('method');
            $.ajax({
                url:url,
                method:method,
                data:{id,id},
                success: function (data) {
                    $('.table-responsive').remove();
                    $('.table-row').append(data);
                }
            });
        });
    </script>
@endsection
