{{-- @extends('errors::illustrated-layout')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found'))

@section('image')
    <img src="{{ asset('assets/img/undraw_posting_photo.svg')}}" alt="{{ asset('assets/img/undraw_posting_photo.svg')}}">
@endsection --}}

@extends('layouts.app')

@section('title','404')

@section('content')
    <div class="text-center">
        <div class="error mx-auto" data-text="404">404</div>
        <p class="lead text-gray-800 mb-5">Page Not Found</p>
        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
        <a href="{{route('home')}}">&rarr; Back to Dashboard</a>
    </div>
@endsection