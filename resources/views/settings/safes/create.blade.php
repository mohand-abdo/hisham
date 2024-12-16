@extends('layouts.app')

@section('title','اضافة خزنة')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right">اضافة خزينة</h6>
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            @include('includes.messages');
            <form action="{{route('safe.store')}}" method="POST">
                @csrf
                @include('settings.safes.form')
                <button type="submt" class="btn btn-primary">اضافة</button>
            </form>
        </div>
    </div>
@endsection