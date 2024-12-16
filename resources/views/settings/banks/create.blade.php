@extends('layouts.app')

@section('title','اضافة بنك')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" style="float:right">اضافة بنك</h6>
            <div class="clearfix"></div>
        </div>
        <div class="card-body">
            @include('includes.messages');
            <form action="{{route('bank.store')}}" method="POST">
                @csrf
                @include('settings.banks.form')
                <button type="submt" class="btn btn-primary">اضافة</button>
            </form>
        </div>
    </div>
@endsection