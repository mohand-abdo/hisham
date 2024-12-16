@extends('layouts.app')

@section('title',' فاتورة مبيعات')

@section('content')
    <div class="card shadow mb-4">
        {{-- <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right">فاتورة مشتريات </h6>
        </div> --}}
        <div class="card-body" id="print">
            <div class="header_bills text-center mb-5">
                 <p>بسم الله الرحمن الرحيم</p>
                 <p>محلات اولاد حسن للمعدات الزراعية ومعدات الورش</p>
                 <p>فاتورة مبيعات</p>
            </div>
            <div class="purchase_info">
                <div class="row">
                    <div class="col-6 pr-4">
                        <p>
                            <span class="ml-3">تاريخ الفاتورة :</span>
                            <span>{{date_format($sale->created_at,'Y-m-d')}}</span>
                        </p>
                        <p>
                            <span class="ml-3">اسم المورد :</span>
                            <span>{{$sale->client()->exists() ? $sale->client->name: 'غير معروف'}}</span>
                        </p>
                    </div>
                    {{-- <div class="col-4 text-center">
                        <p>
                            <span>5/2/2021</span>
                        </p>
                    </div> --}}
                    <div class="col-6 pl-4">
                        <p>
                            <span class="ml-3">رقم الفاتورة :</span>
                            <span>{{'His-'.$sale->id .'-RT'}}</span>
                        </p>
                        <p>
                            <span class="ml-3"> اسم المستخدم :</span>
                            <span>{{$sale->user->f_name .' '.$sale->user->l_name}}</span>
                        </p>
                    </div>
                </div>
                <div class="purchase_det pt-5">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:10%" class="text-center">المتسلسل</th>
                                <th>الصنف</th>
                                <th>الكمية</th>
                                <th>سعر الوحدة</th>
                                <th>الاجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sale->items as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->pivot->qty }}</td>
                                    <td class="text-center">{{ $item->sale_price }}</td>
                                    <td class="text-center">{{ number_format($item->sale_price * $item->pivot->qty) }}</td>
                                </tr>
                            @endforeach
                            <tr class="text-left">
                                <th colspan="4" class="text-center">المجــمـــوع</th>
                                <td class="text-center">{{ number_format($sale->total_price) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="purchase_info pt-2">
                    <div class="row">
                        <div class="col-6">
                            <p>
                                <label>موفعنا</label><br>
                                <span>الجزيرة الحصاحيصا</span>
                            </p>
                        </div>
                        <div class="col-6">
                            <p>
                                <label>تلفون:</label><br>
                                <span>000000455454545</span><br>
                                {{-- <span>000000455454545</span><br>
                                <span>000000455454545</span><br>
                                <span>000000455454545</span><br> --}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <a href="#" class="btn btn-primary print"><i class="fa fa-print mx-1"></i>طباعة</a>
        </div>
    </div>
@endsection

@section('js')
    <script>
            function printData(){
                var bills = document.getElementById("print").innerHTML,
                    newin = document.body.innerHTML;
                document.body.innerHTML = bills;
                window.print();
                document.body.innerHTML = newin;
                // location.reload();
                window.location.href =" {{ route('sales.create')}}";
            }

            $('.print').on('click',function(){
                printData();
                return false;
            });

    </script>

@endsection
