@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')

<div class="site-section site-blocks-2" style="background-image: url({{ url('/images/background-image.png') }}); background-size:cover; background-repeat:no-repeat;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 mb-4 mb-lg-0" data-aos="fade" data-aos-delay="">
                <div class="login-box">
                        <div class="login-title">
                            <h2 class="text-center">Login Shop</h2>
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
                        </div>
                    {!! Form::open(['method' => 'post', 'route' => ['shop.postlogin']]) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" name="email" class="form-control" placeholder="Email">
                                    <i class="text-danger">{{ $errors->first('email') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="********">
                                    <i class="text-danger">{{ $errors->first('password') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-block btn-lg">Sign In</button>
                                <a href="{{ route('shop.register') }}" class="btn btn-outline-primary btn-block btn-lg">Register</a>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
