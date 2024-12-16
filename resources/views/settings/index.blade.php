@extends('layouts.app')

@section('css')
    {!! Html::style('assets/css/tab.css') !!} 
    <style>
        .tab .tab-content {
            display: none;
        }
    </style>
@endsection

@section('title','الاعدادات')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right">الاعدادات </h6>
            <a href="{!!route_tab()!!}"class="btn btn-primary btn-add" style="float:left">اضافة</a>
        </div>
        <div class="card-body">
             @php
                $models = ['الاقسام','المنتجات','الخزائن','البنوك','المصروفات'];
                $contents = [
                    ['collection' => $categories, 'name' =>'القسم','url' => 'category'],
                    ['collection' => $items, 'name' =>'المنتج','url' => 'item'],
                    ['collection' => $safes, 'name' =>'الخزينة','url' => 'safe'],
                    ['collection' => $banks, 'name' =>'البنك','url' => 'bank'],
                    ['collection' => $expenses, 'name' =>'المصروف','url' => 'expense'],
                ];
            @endphp
            @include('includes.messages')   
            <div class="tab">
                <ul class="d-flex flex-column d-lg-block">
                    @foreach ($models as $index => $model)
                    <li id="tab{{$index + 1}}" class="btn btn-list {{$index + 1 ==tab() ? 'active' : '' }}"  data-id="{{$index + 1}}" data-url="{{route('setting.tab')}}" data-route="/settings/{{$contents[$index]['url']}}/create">{{$model}}</li>
                    @endforeach
                </ul>
                @foreach($contents as $index =>$content )
                    <div id="tab{{$index + 1}}-content" class="tab-content {{$index + 1 ==tab() ? 'd-flex' : ''}}"> 
                        @component('components.tables.settings',['collection'=>$content['collection'],'name'=>$content['name'],'url'=>$content['url']])@endcomponent
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('.table').DataTable({
            "language":{
                "url":"{{asset('assets/js/arabic.json')}}"
            },
	    });

        $('.tab ul li').click(function () {
            var id = $(this).attr('id'),
            route = $(this).attr('data-route'),
            url = $(this).data('url'),
            index = $(this).data('id');
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            $('.tab .tab-content.d-flex').removeClass('d-flex');
            $('#' + id + '-content').addClass('d-flex');
            $('.btn-add').attr('href', route);
            
            $.ajax({
                method: 'GET',
                url: url,
                data:{id : index}
            });
        });
    </script>
@endsection