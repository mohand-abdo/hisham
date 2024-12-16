@extends('layouts.app')

@section('title','كشف حساب العملاء')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">كشف حساب العملاء</h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="col-4 mb-3">
                    <select name="client" class="form-control client" data-url="{{ route('report.client.trans')}}" data-method="POST">
                        @if ($clients->count() > 0)
                            <option value="0">اختر العميل</option>
                            @foreach ($clients as $client)
                                <option value="{{$client->id}}">{{$client->name}}</option>
                            @endforeach
                        @else
                            <option>لا يوجد عملاء</option>    
                        @endif
                    </select>
                </div>
                <div class="table-row">
                    <div class="table-responsive ">
                        @if($clients_trans->count() > 0)
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
                                    @foreach ($clients_trans as $index => $client_trans)
                                        <tr>
                                            <td>{{ $index + 1}}</td>
                                            <td>{{ $client_trans->from ? number_format($client_trans->from) : '-' }}</td>
                                            <td>{{ $client_trans->to ? number_format($client_trans->to) : '-'}}</td>
                                            <td>{{ date_format($client_trans->created_at,'Y-m-d') }}</td>
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

        $('body').on('change','.client',function(){
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
