@extends('layouts.app')

@section('title','الايرادات')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right">التوريد</h6>
            <a href="#"class="btn btn-primary" style="float:left" data-toggle="modal" data-target=".wared">اضافة توريد</a>
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            @include('includes.messages')
            <div class="table-responsive">
                @if($wareds->count() > 0)
                    <table class="table table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">لمبلغ</th>
                                <th scope="col">وسيلة التوريد</th>
                                <th scope="col">التاريخ</th>
                                <th scope="col">البيان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($wareds as $index => $wared)
                                <tr>
                                    <td>{{ $index + 1}}</td>
                                    <td>{{number_format($wared->to)}}</td>
                                    <td>{{ $wared->cash == 1 ? 'كاش' : 'البنك' }}</td>
                                    <td>{{date_format($wared->created_at,'Y-m-d')}}</td>
                                    <td>{{$wared->note}}</td>
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

    <div class="modal fade wared" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <form action="{{route('wared.store')}}" method="post" class="modal_form">
            @csrf
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">توريد</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{-- <input type="hidden" value="1" name="check" id="check"> --}}
                        <div class="row">
                            <div class="col-6">
                                <label for="to">المبلغ</label>
                                <input type="number" class="form-control" id="to" name="to" value="{{old('to')}}">
                            </div>

                            
                            <div class="col-6 add_row" >
                                <label for="note">البيان</label>
                                <input type="text" class="form-control" id="note" name="note" value="{{old('note')}}">
                            </div>
                            
                            <div class="col-6">
                                <label for="cash">وسيلة الدفع</label>
                                <select class="form-control" id="cash" name="cash">
                                    <option value="1" {{ old('cash') == 1 ? 'selected':'' }}>كاش</option>
                                    <option value="2" {{ old('cash') == 2 ? 'selected':'' }}>البنك</option>
                                </select>
                            </div>

                            <div class="col-6" id="row_safe">
                                <label for="safe">الخزينة</label>
                                <select class="form-control" name="safe" id="safe">
                                    @foreach ($safes as $safe)
                                    <option value="{{$safe->id}}">{{$safe->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-6" id="row_bank" hidden>
                                <label for="bank">البنك</label>
                                <select class="form-control" name="bank" id="bank">
                                    @foreach ($banks as $bank)
                                    <option value="{{$bank->id}}">{{$bank->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-add disabled">توريد</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            $('.table').DataTable({
                "language":{
                    "url":"assets/js/arabic.json"
                },
            });

            $('body').on('change','#cash',function(){
                var price = $('#to').val(),
                    note  = $('#note').val(),
                    inv_type  = $(this).val();
                if(price != '' && note !=''){
                    checkCashType(inv_type);
                }else{
                    $('.btn-add').addClass('disabled');
                }
            });
            
            $('body').on('keyup','#to',function(){
                var price = $(this).val(),
                    note  = $('#note').val(),
                    inv_type  = $('#cash').val();
                if(price != '' && note !=''){
                    checkCashType(inv_type);
                }else{
                    $('.btn-add').addClass('disabled');
                }
            });
            
            $('body').on('keyup','#note',function(){
                var note = $(this).val(),
                    price = $('#to').val(),
                    inv_type  = $('#cash').val();
                if(note != '' && price !=''){
                    checkCashType(inv_type);
                }else{
                    $('.btn-add').addClass('disabled');
                }
            });

            $('body').on('change','#cash',function(){
                var cash = $(this).val();
                if(cash == 1){
                    $('#row_bank').attr('hidden','hidden');   
                    $('#row_safe').removeAttr('hidden');
                }else{   
                    $('#row_safe').attr('hidden','hidden');                    
                    $('#row_bank').removeAttr('hidden');
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
                $('.modal_form').attr('action','{{ route("wared.store")}}');
                $('#cash option[value="1"]').attr('selected','selected');
                $('#row_bank').attr('hidden','hidden');   
                $('#row_safe').removeAttr('hidden');
            });
        });

        function checkCashType(inv_type){
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
            }
            else if(inv_type == 2)
            {
                var bank = $('#bank').val();
                if(bank == null){
                    $('.btn-add').addClass('disabled');
                }else{
                    $('.btn-add').removeClass('disabled');
                } 
            }
        }
    </script>
@endsection