@extends('layouts.app')

@section('title','كشف حساب بالديون عليك')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4 ">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary" style="float:right">كشف حساب بالديون عليك</h6>
            </div>
            <div class="card-body">
                @include('includes.messages')
                <div class="table-responsive ">
                    @if($suppliers->count() > 0)
                        <table class="table table-right table-bordered dt-responsive nowrap" id="dataTable" width="100%" cellspacing="0" dir="rtl" style="text-align:right">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">المورد</th>
                                    <th scope="col">المبلغ </th>
                                    <th scope="col"> التحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $index => $supplier)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{$supplier->supplier->name}}</td>
                                        <td>{{ number_format($supplier->sub_price)}}</td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm rounded-circle user_info"  title="تفاصيل المورد" data-id="{{$supplier->supplier_id}}" data-url="{{route('supplier.show',$supplier->supplier_id)}}" data-toggle="modal" data-target=".user"><i class="fa fa-user"></i></a>
                                            <a href="{{ route('report.supplier.money',$supplier->supplier_id) }}" class="btn btn-info btn-sm rounded-circle" title="تفاصيل المديونات"><i class="fa fa-link"></i></a>
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

<div class="modal fade user" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <form class="modal_form">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">الاسم</label>
                                <input id="name" class="form-control" disabled/>
                            </div>
                        </div>
                    </div>

                    @for($i = 0 ; $i < 3 ; $i++)
                        <div class="row row-{{$i}}">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="phone-{{$i}}" class="lable-phone-{{$i}} lable-phone">التلفون{{$i == 0 ? ' الاول':($i == 1 ? ' التاني' : ' التالت' )}}</label>
                                    <input type="number" id="phone-{{$i}}" class="form-control phone" disabled/>
                                </div>
                            </div>
                        </div>
                    @endfor

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">العنوان</label>
                                <textarea id="address" class="form-control" disabled></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
    <script>
        $('.table-right').DataTable({
            "language":{
                "url":"{{ asset('assets/js/arabic.json') }} "
            },
	    });

        $('body').on('click', '.user_info', function () { 
            var id = $(this).data('id'),
                url = $(this).data('url');
            $.ajax({
                url: url,
                data: { id: id},
                method: 'get',
                success: function (data) {
                    for( var i=0; i < data.phone.length;i++ ){
                        $('#phone-'+i).val(data.phone[i]);
                    }
                    $('#name').val(data.name);
                    $('.modal-title').text('بيانات المورد '+ data.name);
                    $('#address').val(data.address); 
                }
            });
        });

        $('body').on('hide.bs.modal',function(){
            $('.modal_form').trigger("reset");
        });
    </script>
@endsection
