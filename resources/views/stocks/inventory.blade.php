@extends('layouts.app')

@section('css')
    {!! Html::style('assets/css/tab.css') !!} 
    <style>
        .tab .tab-content:not(:first-of-type) {
            display: none ;
        }
    </style>
@endsection

@section('title','جرد المخازن')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right">جرد المخازن </h6>
        </div>
        <div class="card-body">
            @include('includes.messages')   
            <div class="tab">
                <form action="{{route('stock.inventory.store')}}" method="post">
                    @csrf
                    <ul class="d-flex flex-column d-lg-block">
                        @foreach ($stocks as $index => $stock)
                            <li id="tab{{$index + 1}}" class="btn btn-list {{$index ==0?'active':''}}">{{$stock->name}}</li>
                        @endforeach
                    </ul>
                    @foreach($stocks as $index =>$stock )
                        <div id="tab{{$index + 1}}-content" class="tab-content"> 
                            @component('components.tables.inventory',['collection'=>$stock])@endcomponent
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary btn-block mt-5">حفظ</button>  
                </form> 
            </div>
        </div>
    </div>
@endsection

@section('js')
    {!! Html::script('assets/js/tab.js') !!} 
    <script>
        $('.table').DataTable({
            "language":{
                "url":"{{ asset('assets/js/arabic.json') }} "
            },
	    });

        $('body').on('click','.btn-primary',function() {
            $(this).addClass('disabled');
            });
    </script>
@endsection