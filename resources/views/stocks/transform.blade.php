@extends('layouts.app')

@section('title','التحويل بين المخازن')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-lg-5">        
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary" style="float:right">كل المخازن</h6>
                </div>
                <div class="card-body">
                    @forelse ($stocks as $stock)
                        <a class="btn btn-primary mb-2 d-block" data-toggle="collapse" href="#{{ str_replace(' ','-',$stock->name).'-'.$stock->id}}" role="button"aria-expanded="false" aria-controls="{{$stock->name.'-'.$stock->id}}">{{$stock->name}}</a>
                        <div class="collapse" id="{{ str_replace(' ','-',$stock->name).'-'.$stock->id}}"> 
                            @if ($stock->items()->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-right align-items-center table-flush">
                                        <thead class="">
                                            <tr>
                                                <th scope="col" class="text-center">الاسم</th>
                                                <th scope="col">الكمية</th>
                                                <th scope="col">اضافة</th>
                                            </tr>
                                        </thead>
                                        <tbody class="product-list">
                                            @foreach ($stock->items as $product)
                                                <tr>
                                                    <td class="text-center">{{$product->name}}</td>
                                                    <td class="stock-{{$stock->id}}-{{$product->id}} qty">{{$product->pivot->qty}}</td>
                                                    <td>
                                                        <a href="" class="btn btn-success btn-sm btn-add-product product_{{$product->id}} rounded-circle" id="product-{{$stock->id}}-{{$product->id}}" data-product="{{$product->id}}" data-name="{{$product->name}}" data-qty="{{$product->pivot->qty}}" data-url="{{ route('stock.transform.ajax') }}" data-stock="{{$stock->name}}" data-id="{{$stock->id}}" >
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @lang('dashboard.data_not_found')
                            @endif                
                            
                        </div>
                    @empty
                        @lang('dashboard.data_not_found')
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-lg-7">        
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary" style="float:right">التحويل بين المخازن</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock.transform.store')}}" method="post">
                        @csrf
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col" class="text-center">الصنف</th>
                                        <th scope="col" class="text-center">من</th>
                                        <th scope="col" class="text-center">الي</th>
                                        <th scope="col" class="text-center">الكمية</th>
                                        <th scope="col" class="text-center">@lang('dashboard.delete')</th>
                                    </tr>
                                </thead>
                                <tbody class="body-list">
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block disabled btn-form-product">@lang('dashboard.add')</button>
                    </form>
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
        
        $(document).ready(function() {  
            $('body').on('click','.btn-form-product',function() {
                $(this).addClass('disabled dis');
            });

        })
        </script>
        {!! Html::script('assets/js/stock.js') !!}
@endsection
