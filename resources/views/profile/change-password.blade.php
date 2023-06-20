@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 justify-content-center">
                <div class="box-2 text-center">
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

                    @if (Str::length(Auth::guard('web')->user()) > 0)
                        {!! Form::open(['route' => ['profile.updatepassword'], 'enctype' => 'multipart/form-data', 'method' => 'post']) !!}
                    @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                        {!! Form::open(['route' => ['shopprofile.updatepassword'], 'enctype' => 'multipart/form-data', 'method' => 'post']) !!}
                    @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                        {!! Form::open(['route' => ['driverprofile.updatepassword'], 'enctype' => 'multipart/form-data', 'method' => 'post']) !!}
                    @endif
                    @csrf
                    <div class="box-2-text">
                        <div class="row form-group">
                            <label class="col-md-5 text-left">Current Password</label>
                            <input type="password" name="current_password" class="col-md-7 form-control">
                            <i class="text-danger">{{ $errors->first('current_password') }}</i>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-5 text-left">New Password</label>
                            <input type="password" name="new_password" class="col-md-7 form-control">
                            <i class="text-danger">{{ $errors->first('new_password') }}</i>
                        </div>
                        <div class="row form-group">
                            <label class="col-md-5 text-left">Confirm Password</label>
                            <input type="password" name="new_password_confirmation" class="col-md-7 form-control">
                            <i class="text-danger">{{ $errors->first('new_password') }}</i>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">DONE</button>
                                @if (Str::length(Auth::guard('web')->user()) > 0)
                                    <a href="{{ route('profile.index') }}" class="btn btn-info btn-block">BACK</a>
                                @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                                    <a href="{{ route('shopprofile.index') }}" class="btn btn-info btn-block">BACK</a>
                                @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                                    <a href="{{ route('driverprofile.index') }}" class="btn btn-info btn-block">BACK</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
