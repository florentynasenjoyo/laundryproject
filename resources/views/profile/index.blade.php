@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="box">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <p>{{ $message }}</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @elseif ($message = Session::get('danger'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p>{{ $message }}</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="box-image">
                        @if (Str::length(Auth::guard('web')->user()) > 0)
                            @if (Auth::guard('web')->user()->foto == NULL || Auth::guard('web')->user()->foto == '')
                                <img src="{{ url('/images/no-image.jpg') }}">
                            @else
                                <img src="{{ url(Auth::guard('web')->user()->foto) }}">
                            @endif
                        @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                            @if (Auth::guard('webdriver')->user()->foto == NULL || Auth::guard('webdriver')->user()->foto == '')
                                <img src="{{ url('/images/no-image.jpg') }}">
                            @else
                                <img src="{{ url(Auth::guard('webdriver')->user()->foto) }}">
                            @endif
                        @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                            @if (Auth::guard('webshop')->user()->logo == NULL || Auth::guard('webshop')->user()->logo == '')
                                <img src="{{ url('/images/no-image.jpg') }}">
                            @else
                                <img src="{{ url(Auth::guard('webshop')->user()->logo) }}">
                            @endif
                        @endif
                    </div>
                    <div class="box-text">
                        @if (Str::length(Auth::guard('web')->user()) > 0)
                        <h2 class="text-left">{{ Auth::guard('web')->user()->name }}</h2>
                        <span class="btn btn-gradient">{{ strtoupper(Auth::guard('web')->user()->level_member) }}</span>
                        <a href="{{ route('profile.edit', Auth::guard('web')->user()->id) }}" class="btn btn-gradient">Edit Profile</a>
                        @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                        <h2 class="text-left">{{ Auth::guard('webshop')->user()->name }}</h2>
                        <a href="{{ route('shopprofile.edit', Auth::guard('webshop')->user()->id) }}" class="btn btn-gradient">Edit Profile</a>
                        <a href="{{ route('rekening.show', Auth::guard('webshop')->user()->id) }}" class="btn btn-gradient">Rekening</a>
                        <a href="{{ route('openinghour.show', Auth::guard('webshop')->user()->id) }}" class="btn btn-gradient">Opening Hours</a>
                        <a href="{{ route('shopservice.show', Auth::guard('webshop')->user()->id) }}" class="btn btn-gradient">Services</a>
                        @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                        <h2 class="text-left">{{ Auth::guard('webdriver')->user()->name }}</h2>
                            <a href="{{ route('driverprofile.edit', Auth::guard('webdriver')->user()->id) }}" class="btn btn-gradient">Edit Profile</a>
                        @endif
                    </div>
                </div>
                @if (Str::length(Auth::guard('web')->user()) > 0)
                    <div class="box justify-content-between">
                        <div class="box-text">
                            <h2 class="text-left">
                                <a href="{{ route('profile.membercard', Auth::guard('web')->user()->id) }}">
                                    Member
                                </a>
                            </h2>
                        </div>
                        <div class="box-text">
                            <h2 class="text-right">
                                <a href="{{ route('profile.membercard', Auth::guard('web')->user()->id) }}" class="text-right">-></a>
                            </h2>
                        </div>
                    </div>
                @endif
                {{-- <div class="box justify-content-between">
                    <div class="box-text">
                        <h2 class="text-left">
                            <a href="#">
                                Language
                            </a>
                        </h2>
                    </div>
                    <div class="box-text">
                        <h2 class="text-right">
                            <a href="#" class="text-right">-></a>
                        </h2>
                    </div>
                </div> --}}
                <div class="box justify-content-between">
                    <div class="box-text">
                        <h2 class="text-left">
                            @if (Str::length(Auth::guard('web')->user()) > 0)
                                <a href="{{ route('profile.editpassword') }}">
                            @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                                <a href="{{ route('shopprofile.editpassword') }}">
                            @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                                <a href="{{ route('driverprofile.editpassword') }}">
                            @endif
                                Change Password
                            </a>
                        </h2>
                    </div>
                    <div class="box-text">
                        <h2 class="text-right">
                            @if (Str::length(Auth::guard('web')->user()) > 0)
                                <a href="{{ route('profile.editpassword') }}" class="text-right">-></a>
                            @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                                <a href="{{ route('shopprofile.editpassword') }}" class="text-right">-></a>
                            @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                                <a href="{{ route('driverprofile.editpassword') }}" class="text-right">-></a>
                            @endif
                        </h2>
                    </div>
                </div>

                @if (Str::length(Auth::guard('web')->user()) > 0)
                    {!! Form::open(['method' => 'post', 'route' => ['profile.destroy', Auth::guard('web')->user()->id]]) !!}
                @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                    {!! Form::open(['method' => 'post', 'route' => ['shopprofile.destroy', Auth::guard('webshop')->user()->id]]) !!}
                @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                    {!! Form::open(['method' => 'post', 'route' => ['driverprofile.destroy', Auth::guard('webdriver')->user()->id]]) !!}
                @endif
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Account</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection
