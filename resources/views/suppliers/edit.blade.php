@extends('layouts.app')

@section('title')
تعديل {{$supplier->name}}
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right">تعديل {{$supplier->name}}</h6>
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            @include('includes.messages');
            <form action="{{route('supplier.update',$supplier->id)}}" method="POST">
                @csrf
                @method('PUT')
                @include('suppliers.form')
                <button type="submt" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>
@endsection