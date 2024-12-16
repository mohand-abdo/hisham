@extends('layouts.app')

@section('title')
    مشتريات ومبيعات العميل {{ $client->name}}
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <a href="http://hisham.com/client/create" class="btn btn-primary" style="float:right">محاسية تجار</a>
                <a href="http://hisham.com/client/create" class="btn btn-primary" style="float:left"> محاسية اخوان</a>
                <div class="clearfix"></div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h6 class="mt-3 mr-3"><span class="font-weight-bold text-primary">صافي المبيعات </span> : <span class="total-price">{{friend_total($client->friend_purchases , $client->friend_sales )[1] .'جنيه'}}</span></h6>
                    <h6 class="mt-3 mr-3"><span class="font-weight-bold text-primary">صافي المشتريات</span> : <span class="total-price">{{friend_total($client->friend_purchases , $client->friend_sales )[2] .'جنيه'}}</span></h6>
                    <h6 class="mt-3 mr-3"><span class="font-weight-bold text-primary">صافي المبيعات من المشتريات</span> : <span class="total-price">{{friend_total($client->friend_purchases , $client->friend_sales )[0] .'جنيه'}}</span></h6>
                </div>
                
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h6 class="mt-3 mr-3"><span class="font-weight-bold text-primary">دائن (عليه)</span> : <span class="total-price">{{$cli_account_main ? $cli_account_main->sub_price .'جنيه' : 0}}</span></h6>
                    <h6 class="mt-3 mr-3"><span class="font-weight-bold text-primary">مدين (له)</span> : <span class="total-price">{{$sup_account_main ? $sup_account_main->sub_price .'جنيه' : 0}}</span></h6>
                    <h6 class="mt-3 mr-3"><span class="font-weight-bold text-primary">صافي الدين</span> : <span class="total-price"> {{($cli_account_main ? $cli_account_main->sub_price : 0) - ($sup_account_main ? $sup_account_main->sub_price : 0 ) .'جنيه'}}</span></h6>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">مشتريات العميل {{ $client->name}}</h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="table-responsive">
                    @if($client->friend_purchases->count() > 0 )
                        <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">المبلغ</th>
                                    <th scope="col">التاريخ</th>
                                    <th scope="col">تفاصيل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($client->friend_purchases as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{$item->sup_account->sub_price}}</td>
                                        <td>{{date_format($item->created_at,'Y-m-d') }}</td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm rounded-circle pur_det" title="تفاصيل"  data-toggle="modal" data-target=".details" data-url="{{ route('purchase.show',$item->id)}}" data-method="GET">
                                                <i class="fa fa-link"></i>
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

    <div class="col-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right"> مبيعات العميل {{ $client->name}}</h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="table-responsive">
                    @if($client->friend_sales->count() > 0)
                        <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">المبلغ</th>
                                    <th scope="col">التاريخ</th>
                                    <th scope="col">تفاصيل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($client->friend_sales as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>
                                            @if ($item->uncash_type == 1)
                                                {{ $item->cli_account->sub_price }}                                                    
                                            @else
                                                @php 
                                                    client_account($item);
                                                    $item->refresh()
                                                @endphp
                                                {{ $item->cli_account->sub_price }}
                                            @endif
                                        </td>
                                        <td>{{date_format($item->created_at,'Y-m-d') }}</td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm rounded-circle sale_det" title="تفاصيل" data-url="{{ route('sales.show',$item->id)}}" data-method="GET" data-toggle="modal" data-target=".details">
                                                <i class="fa fa-link"></i>
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



<div class="modal fade details" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">    
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل الفاتورة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="show-product"></div>
            </div>
        </div>
    </div>
</div>

   
@endsection

@section('js')
    <script>
        $('.table').DataTable({
            "language":{
                "url":"{{ asset('assets/js/arabic.json') }} "            
            },
	    });

        $('body').on('click','.pur_det',function(){
            var url = $(this).data('url'),
                method = $(this).data('method');
            $.ajax({
                method : method,
                url : url,
                success: function (data) {
                    $('#show-product').append(data);
                    $('.details').modal('show');
                }
            });
        });

        $('body').on('click','.sale_det',function(){
            var url = $(this).data('url'),
                method = $(this).data('method');
            $.ajax({
                method : method,
                url : url,
                success: function (data) {
                    $('#show-product').append(data);
                    $('.details').modal('show');
                }
            });
        });

        $('body').on('hide.bs.modal',function(){
            $('#show-product .table-responsive').remove();
        }); 
    </script>
@endsection