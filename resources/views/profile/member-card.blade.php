@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')

<div class="site-section">
    <div class="container">
        <div class="card">
            @if (Auth::guard('web')->user()->foto == NULL || Auth::guard('web')->user()->foto == '')
                <img src="{{ url('/images/no-image.jpg') }}" class="w-100">
            @else
                <img src="{{ url(Auth::guard('web')->user()->foto) }}" class="w-100">
            @endif
            <div class="container text-center">
                <h4 class="text-black"><b>{{ Auth::guard('web')->user()->name }}</b></h4>
                <p class="font-weight-bold text-black">{{ strtoupper(Auth::guard('web')->user()->level_member) }}</p>
                <a href="{{ route('profile.index') }}" class="btn btn-primary mb-4">OK</a>
            </div>
        </div>
    </div>
</div>

@endsection
