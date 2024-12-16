@extends('layouts.app')

@section('title')
    {{$stock->name}}
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right"> {{$stock->name}}</h6>
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            @include('includes.messages')
            <div class="table-responsive">
                @if(count($items) > 0)
                    <table class="table table-bordered dt-responsive nowrap tabl" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">اسم الصنف</th>
                                <th scope="col">اسم المخزن</th>
                                <th scope="col">الكمية</th>
                                <th scope="col" class="text-center w-25">التحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $index => $item)
                                @if ($item->pivot->qty > 0)                               
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$stock->name}}</td>
                                        <td>{{$item->stocks()->find($stock->id)->pivot->qty}}</td>
                                        <td class="text-center">
                                            <a href="#" class="btn btn-info btn-sm rounded-circle btn-transfer" title="تحويل" data-toggle="modal" data-target=".transfer" data-stock="{{$stock->id}}" data-url="{{route('stock.item')}}" data-name="{{$item->name}}" data-id="{{$item->id}}" data-value="{{$item->pivot->qty - 1}}" data-qty="{{$item->pivot->qty}}">
                                                <i class="fa fa-exchange-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @else                   
                    <div class="text-center">لاتوجد بيانات</div>
                @endif
            </div>
        </div>
    </div>
    @if(count($items) > 0)
        <div class="modal fade transfer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
            <form action="{{ route('stock.transform.store')}}" method="post" class="modal_form">
                @csrf
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">التحويل بين المخازن</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table align-items-center">
                                    <thead class="">
                                        <tr>
                                            <th scope="col" class="text-center">الاسم</th>
                                            <th scope="col" class="text-center">المخزن</th>
                                            <th scope="col" class="text-center">الكمية</th>
                                        </tr>
                                    </thead>
                                    <tbody class="product-list">
                                        <tr class="tr-list">
                                            <td class="item"></td>
                                            <td>
                                                <select class="form-control stock2" name="to_stock_id[]" data-url="{{route('stock.select_qty.ajax')}}" data-stock="{{$stock->id}}">
                                                    <option value="">اختر مخزن</option>
                                                    @php
                                                        $stocks = App\Models\Stock::where('id','!=',$stock->id)->get();
                                                    @endphp

                                                    @foreach($stocks as $index => $val)
                                                        <option value="{{$val->id}}">{{$val->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control input-sm input-qty dis" disabled value="1" min="1"/>
                                                <input type="hidden" class="form-control stock1" name="from_stock_id[]" value="{{$stock->id}}"/>
                                                <input type="hidden" class="form-control item_inpt" name="from_product_id[]" value=""/>
                                                <input type="hidden" class="form-control input-sm qty1" name="from_qty[]"/>
                                                <input type="hidden" class="form-control input-sm qty2" name="to_qty[]"/>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-add disabled">تحويل</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endif
@endsection

@section('js')
    <script>
        $('.tabl').DataTable({
            "language":{
                "url":"{{asset('assets/js/arabic.json')}}"
            },
	    });

        $('body').on('hide.bs.modal',function(){
            $('.modal_form').trigger("reset");
            $('.input-qty').prop('disabled',true);
            $('select.stock2').prop('disabled',false);
            $('.input-qty').addClass('dis'); 
            $('.btn-add').addClass('disabled');
        });

        $('.btn-transfer').click(function(){
            var item = $(this).data('name'),
                id = $(this).data('id'),
                stock = $(this).data('stock'),
                url = $(this).data('url');
            $('.stock2').attr('data-id', id);
            $('.item').html(item);
            $('.item_inpt').val(id);
            $.ajax({
                url: url,
                data: { id: id,stock:stock},
                method: 'get',
                success: function (data) {
                    $('.qty1').val(data.value);
                    $('.qty1').attr('data-qty',data.qty);
                }
            });
        });

        $('body').on('keyup change', '.stock2', function () { 
            var id = $(this).val(),
                product = $(this).data('id');
                url = $(this).data('url');
            $.ajax({
                url: url,
                data: { id: id,product:product},
                method: 'get',
                success: function (data) {
                    $('.qty2').val(data.new_qty);
                    $('.qty2').attr('data-qty',data.old_qty);
                }
            });
            if (id == ''){
                $('.input-qty').prop('disabled',true); 
                $('.input-qty').addClass('dis'); 
                $('.btn-add').addClass('disabled');   
            }
            else{
                $('.input-qty').prop('disabled',false);
                $('.input-qty').removeClass('dis');
                $('.btn-add').removeClass('disabled');
            }
        });

        $('body').on('keyup change', '.input-qty', function () { 
            var qty = $(this).val(),
                qty1 = $('.qty1').data('qty'),
                qty2 = $('.qty2').data('qty'),
                stock1 = qty1 - qty,
                stock2 = Number(qty) + Number(qty2);
                console.log(qty1);
            $('.qty1').val(stock1);
            var check = $('.qty1').val();
            $('.qty2').val(stock2);
            if(check < 0 ){
                $('.btn-add').addClass('disabled');
                $('select.stock2').prop('disabled',true);
            }else{
                $('select.stock2').prop('disabled',false);
                $('.btn-add').removeClass('disabled');
            }
        });

        $('body').on('click','.btn-add.disabled',function(e){
            e.preventDefault();
        });

    </script>
@endsection