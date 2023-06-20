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
                            <h2 class="text-center">Register Driver</h2>
                        </div>
                    {!! Form::open(['method' => 'post', 'route' => ['driver.postregister'], 'enctype' => 'multipart/form-data']) !!}
                    @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Nama" value="{{ old('nama') }}">
                                    <i class="text-danger">{{ $errors->first('nama') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">No Handphone</label>
                                    <input type="text" name="no_hp" class="form-control" placeholder="No Handphone" value="{{ old('no_hp') }}">
                                    <i class="text-danger">{{ $errors->first('no_hp') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                    <i class="text-danger">{{ $errors->first('email') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Foto</label>
                                    <input type="file" name="foto" class="form-control">
                                    <i class="text-danger">{{ $errors->first('foto') }}</i>
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
                                <div class="form-group">
                                    <label for="">Password Confirmation</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="********">
                                    <i class="text-danger">{{ $errors->first('password') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-block btn-lg">Register</button>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-block btn-lg">Login</a>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
