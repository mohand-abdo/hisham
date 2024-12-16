@extends('layouts.app')

@section('title','العملاء')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right">كل العملاء</h6>
            <a href="{{route('client.create')}}"class="btn btn-primary" style="float:left">اضافة عميل</a>
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            @include('includes.messages')
            <div class="table-responsive">
                @if($clients->count() > 0)
                    <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">الاسم</th>
                                <th scope="col">التلفون</th>
                                <th scope="col">العنوان</th>
                                <th scope="col" class="text-center w-25">التحكم</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $index => $client)
                                <tr>
                                    <td>{{ $index + 1}}</td>
                                    <td>{{$client->name}}</td>
                                    <td>{{ implode($client->phone ,'-') }}</td>
                                    <td>{{$client->address}}</td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-info btn-sm rounded-circle " id="get_money" title="دين" data-toggle="modal" data-target=".get_money" data-id="{{ $client->id }}">
                                            <i class="fa fa-money"></i>
                                        </a>

                                        <a href="{{ route('client.account_friend',$client->id) }}" class="btn btn-warning btn-sm rounded-circle " title="دين" >
                                            <i class="fa fa-money"></i>
                                        </a>

                                        <a href="{{route('client.edit',$client->id)}}" class="btn btn-warning btn-sm rounded-circle"  title="تعديل" data-toggle="tooltip" data-original-title="{{__('dashboard.edit')}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{route('client.destroy',$client->id)}}" method="post" style="display:inline-block">
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

    <div class="modal fade get_money" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <form action="{{route('client.get_money')}}" method="post" class="get_money_form">
            @csrf
            <input type="hidden" name="client_id" id="client_id">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">طلب دين لعميل</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-3 price">
                                <label for="price">المبلغ</label>
                                <input type="number" class="form-control" id="price" name="price" min="1">
                            </div>
                            <div class="col-3 inv_type" >
                                <label for="move_type">دائن / مدين</label>
                                <select class="form-control" id="move_type" name="move_type">
                                    <option value="1">دائن</option>
                                    <option value="2">مدين</option>
                                </select>
                            </div>
                            <div class="col-3 inv_type" >
                                <label for="inv_type">وسيلة الدفع</label>
                                <select class="form-control" id="inv_type" name="inv_type">
                                    <option value="1">كاش</option>
                                    <option value="2">البنك</option>
                                </select>
                            </div>
                            <div class="col-3 safe">
                                <label for="safe">الخزينة</label>
                                <select class="form-control" name="safe" id="safe">
                                    @if ($safes->count() > 0)
                                    @foreach($safes as $safe)
                                    <option value="{{$safe->id}}">{{$safe->name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-3 bank d-none">
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
        $('.table').DataTable({
            "language":{
                "url":"assets/js/arabic.json"
            },
	    });
    </script>

    <script>
        $(document).ready(function(){
            $('body').on("click" ,"#get_money" , function(){
                var id = $(this).data('id');
                $('#client_id').val(id);
            });

            $('.get_money_form').trigger("reset");
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
                $('.get_money_form').trigger("reset"); 
                $('.btn-add').addClass('disabled');
                $('.modal_form').attr('action','{{ route("client.get_money")}}');
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