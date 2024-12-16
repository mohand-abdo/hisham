@extends('layouts.app')

@section('css')
    {!! Html::style('assets/vendor/date-picker/css/date-picky.css') !!}
    {!! Html::style('assets/vendor/date-picker/css/fonts.css') !!}
    {!! Html::style('assets/vendor/date-picker/css/animate.min.css') !!}
@endsection

@section('title','فاتورة مبيعات')

@section('content')
    @include('includes.messages');
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger d-none alert-custom">@lang('dashboard.stock_must_greater_than_zerro')</div>
        </div>
        <div class="col-12">
            <form action="{{route('sales.store')}}" method="post" class="puchase_form">
                @csrf
                <div class="card shadow mb-4 col-12">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary" style="float:right">فاتورة مبيعات</h6>
                        <div class="clearfix"></div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="client_id" class="client_id"> العميل</label>
                                    <select name="client_id" id="client_id" class="form-control">
                                        <option value="">اختر عميل</option>
                                        @foreach ($clients as $client)
                                            <option value="{{$client->id}}" {{ old('client_id') == $client->id ? 'selected':'' }}>{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <span class="require-insert">*</span>
                                    <label for="inv_type" >طريقة الدفع</label>
                                    <select name="inv_type" id="inv_type" class="form-control">
                                        <option value="" disabled>طريقة الدفع</option>
                                        <option value="1" {{ old('inv_type') == 1 ? 'selected':'' }}>كاش</option>
                                        <option value="2" {{ old('inv_type') == 2 ? 'selected':'' }}>البنك</option>
                                        <option value="3" {{ old('inv_type') == 3 ? 'selected':'' }}>اجل</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row pay_type">
                            <div class="col-6 bank_id d-none">
                                <div class="form-group">
                                    <span class="require-insert">*</span>
                                    <label for="bank_id">البنك</label>
                                    <select name="bank_id" id="bank_id" class="form-control">
                                        <option value="">اختر البنك</option>
                                        @foreach ( $banks as $bank)
                                            <option value="{{$bank->id}}" {{ old('bank_id') == $bank->id ? 'selected':'' }} >{{$bank->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-6 uncash_type d-none">
                                <div class="form-group">
                                    <span class="require-insert">*</span>
                                    <label for="uncash_type"> نوع الاجل</label>
                                    <select name="uncash_type" id="uncash_type" class="form-control">
                                        <option value="">اختر نوع الاجل</option>
                                        <option value="1" {{ old('uncash_type') == 1 ? 'selected':'' }}> مشروط</option>
                                        <option value="2" {{ old('uncash_type') == 2 ? 'selected':'' }}>غير مشروط</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6 pay_date d-none">
                                <div class="form-group">
                                    <span class="require-insert">*</span>
                                    <label for="pay_date">تاريخ الاستحقاق</label>
                                    <input type="text" id="pay_date" class="form-control"  name="pay_date" value="{{ old('pay_date') }}" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" class="total_price" name="total_price">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-lg-6 mb-4">
                        <div class="card shadow pb-3">
                            <div class="card-header border-0 mb-3">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <h6 class="m-0 font-weight-bold text-primary">المنتجات</h6>
                                    </div>
                                </div>
                            </div>
                            @if ($items->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-right align-items-center table-flush">
                                        <thead class="">
                                            <tr>
                                                <th scope="col">{{ __('dashboard.name') }}</th>
                                                <th scope="col">{{ __('dashboard.stock') }}</th>
                                                <th scope="col">{{ __('dashboard.price') }}</th>
                                                <th scope="col">{{ __('dashboard.add') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="product-list">
                                            @foreach ($items as $item)
                                                <tr>
                                                    <td>{{$item->name}}</td>
                                                    <td class="stock-{{$item->id}} qty">{{$item->stocks()->where('stock_id', 2)->exists() ? $item->stocks()->find(2)->pivot->qty : '0'}}</td>
                                                    <td>{{number_format($item->sale_price)}}</td>
                                                    <td>
                                                        <a href="" class="btn btn-success btn-sm btn-add-product rounded-circle" id="product-{{$item->id}}" data-id="{{$item->id}}" data-name="{{$item->name}}" data-price="{{$item->sale_price}}" data-qty="{{$item->stocks()->where('stock_id', 2)->exists() ? $item->stocks()->find(2)->pivot->qty : '0'}}">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center">لاتوجد بيانات</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-12 col-lg-6 mb-4">
                        <div class="card shadow pb-3 mb-3">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 font-weight-bold text-primary">تفاصيل الفاتورة</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="">
                                        <tr>
                                            <th scope="col">{{__('dashboard.name')}}</th>
                                            <th scope="col">{{__('dashboard.stock')}}</th>
                                            <th scope="col">{{__('dashboard.price')}}</th>
                                            <th scope="col">@lang('dashboard.delete')</th>
                                        </tr>
                                    </thead>
                                    <tbody class="body-list">
                                    </tbody>
                                </table>
                            </div>
                            <h4 class="mt-3 mr-3"><span class="font-weight-bold text-primary">المجــموع</span> : <span class="total-price">0</span></h4>
                            <button type="submit" class="btn btn-primary btn-block disabled btn-form-product">@lang('dashboard.add')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    {!! Html::script('assets/js/product2.js') !!}
    {!! Html::script('assets/js/invoices.js') !!}
    {!! Html::script('assets/vendor/date-picker/js/date-picky.min.js') !!}
    <script>

        $('.table-right').DataTable({
            "language":{
                "url":"{{ asset('assets/js/arabic.json') }} "
            },
	    });

        $('#pay_date').datePicky({
            multipleDate : false,
            format : 'YYYY-MM-DD',
            activation: 'onClick',
            showCurrentDateButton: false,
            // showCurrentDate: false,

        });

        $('body').on('click','.btn-form-product',function() {
            $(this).addClass('disabled');
        })
    </script>
@endsection
