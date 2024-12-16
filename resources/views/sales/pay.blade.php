@extends('layouts.app')

@section('css')
    {!! Html::style('assets/vendor/date-picker/css/date-picky.css') !!}
    {!! Html::style('assets/vendor/date-picker/css/fonts.css') !!}
    {!! Html::style('assets/vendor/date-picker/css/animate.min.css') !!}
@endsection

@section('title','تفاصيل فاتورة مبيعات')

@section('content')
    @include('includes.messages');
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger d-none alert-custom">@lang('dashboard.stock_must_greater_than_zerro')</div>
        </div>
        <div class="col-12">          
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary" style="float:right">تفاصيل فاتورة مبيعات</h6>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body">
                    <div class="row">    
                        <div class="col-md-3 col-xs-12">
                            <label for="">رقم الفاتورة</label>
                            <input type="text" class="form-control dis" disabled value="{{'His-'. $sale->id .'-RT'}}">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label for="">المورد</label>
                            <input type="text" class="form-control dis" disabled value="{{$sale->client->name}}">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label for="">تاريخ الفاتورة</label>
                            <input type="text" class="form-control dis" disabled value="{{date_format($sale->created_at,'Y-m-d')}}">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label for="">تاريخ الاستحقاق</label>
                            <input type="text" class="form-control dis" disabled value="{{$sale->pay_date}}">
                        </div>
                    </div>
                    <div class="row">    
                        <div class="col-md-3 col-xs-12">
                            <label for="">نوع الفاتورة</label>
                            <input type="text" class="form-control dis" disabled value="{{$sale->uncash_type == 1 ? ' مشروط' : 'غير مشروط'}}">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label for="">اجمالي الفاتورة</label>
                            <input type="text" class="form-control dis" disabled value="{{number_format($sale->total_price)}}">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label for="">المتبقي</label>
                            <input type="text" class="form-control dis" disabled value="{{number_format($sale->total_price - $sale->cli_account->pay_price) }}">
                        </div>
                        <div class="col-md-3 col-xs-12">
                            <label for="">المدفوع</label>
                            <input type="text" class="form-control dis" disabled value="{{number_format($sale->cli_account->pay_price)}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card shadow pb-3">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary" style="float: right">تفاصيل الدفعات</h6>
                            <a href="{{-- {{ route('sale.account.pay')}} --}}#" class="btn btn-primary" style="float:left" data-toggle="modal" data-target=".pay">سداد</a>
                            <div class="clearfix"></div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                @if($pay_det->count() > 0)
                                    <table class="table table-bordered dt-responsive nowrap">
                                        <thead class="">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">المبلغ</th>
                                                <th scope="col">وسيلة الدفع</th>
                                                <th scope="col">تاريخ الدفع</th>
                                            </tr>
                                        </thead>
                                        <tbody class="product-list">
                                            @foreach ($pay_det as $index => $item)
                                                <tr>
                                                    <td>{{$index + 1}}</td>
                                                    <td>{{number_format($item->price)}}</td>
                                                    <td>{{$item->inv_type == 1 ? 'كاش' : 'بنك'}}</td>
                                                    <td>{{date_format($item->created_at,'Y-m-d')}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="text-center">لا توجد دفوعات</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade pay" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <form action="{{route('sale.account.pay')}}" method="post" class="modal_form">
            @csrf
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">سداد فاتورة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="sale_id" value="{{$sale->id}}">
                        <input type="hidden" name="client_id" value="{{$sale->client->id}}">
                        <div class="row">
                            <div class="col-4 price">
                                <label for="price">المبلغ</label>
                                <input type="number" class="form-control" id="price" name="price" min="1">
                            </div>
                            <div class="col-4 inv_type" >
                                <label for="inv_type">وسيلة الدفع</label>
                                <select class="form-control" id="inv_type" name="inv_type">
                                    <option value="1">كاش</option>
                                    <option value="2">البنك</option>
                                </select>
                            </div>
                            <div class="col-4 safe">
                                <label for="safe">الخزينة</label>
                                <select class="form-control" name="safe" id="safe">
                                    @if ($safes->count() > 0)
                                    @foreach($safes as $safe)
                                    <option value="{{$safe->id}}">{{$safe->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-4 bank d-none">
                                <label for="bank">البنك</label>
                                <select class="form-control" name="bank" id="bank">
                                    @if ($banks->count() > 0)
                                    @foreach($banks as $bank)
                                    <option value="{{$bank->id}}">{{$bank->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-add disabled">تحويل</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            var inv_type = $('#inv_type').val();
            checkInv(inv_type);
            $('body').on('change', '#inv_type', function () {
                var inv_type = $(this).val();
                checkInv(inv_type);
            });

             $('body').on('keyup','#price',function(){
                var price = $(this).val(),
                    inv_type = $('#inv_type').val();
                if(price != '')
                {
                    if(inv_type == 1)
                    {
                        var safe = $('#safe').val();
                        if(safe == null)
                        {
                            $('.btn-add').addClass('disabled');
                        }else
                        {
                            $('.btn-add').removeClass('disabled');
                        }
                    }else if(inv_type == 2)
                    {
                        var bank = $('#bank').val();
                        if(bank == null){
                            $('.btn-add').addClass('disabled');
                        }else{
                            $('.btn-add').removeClass('disabled');
                        } 
                    }
                }else
                {
                    $('.btn-add').addClass('disabled');
                }
            });

            $('body').on('change','#inv_type', function(){
                var inv_type = $(this).val(),
                    price = $('#price').val();
                if(price != '')
                {
                    if(inv_type == 1)
                    {
                        var safe = $('#safe').val();
                        if(safe == null)
                        {
                            $('.btn-add').addClass('disabled');
                        }else
                        {
                            $('.btn-add').removeClass('disabled');
                        }
                    }else if(inv_type == 2)
                    {
                        var bank = $('#bank').val();
                        if(bank == null){
                            $('.btn-add').addClass('disabled');
                        }else{
                            $('.btn-add').removeClass('disabled');
                        } 
                    }
                }
                else{
                    $('.btn-add').addClass('disabled');
                }

            });

            $('body').on('click','.btn-add',function() {
            $(this).addClass('disabled dis');
            });
            
            $('body').on('click','.btn-add.disabled',function(e) {
            e.preventDefault();
            });

            $('body').on('hide.bs.modal',function(){
                $('.modal_form').trigger("reset"); 
                $('.btn-add').addClass('disabled');
                $('.modal_form').attr('action','{{ route("sale.account.pay")}}');
                $('#inv_type option[value="1"]').attr('selected','selected');
                $('.bank').addClass('d-none');
                $('.safe').removeClass('d-none');
            });        
        });

        function checkInv(inv_type){
            if (inv_type == 1) {
                $('.safe').removeClass('d-none');
                $('.bank').addClass('d-none');
            }
            else if (inv_type == 2) {
                $('.safe').addClass('d-none');
                $('.bank').removeClass('d-none');
            }
        }
    </script>
@endsection
