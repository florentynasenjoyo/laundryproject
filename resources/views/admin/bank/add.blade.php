@extends('admin.layouts.app')
@include('admin.layouts.partials.css')
@include('admin.layouts.partials.js')

@section('content')
<div class="page-breadcrumb bg-white">
    <div class="row align-items-center">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Banks</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <div class="d-md-flex">
                <ol class="breadcrumb ms-auto">
                    <li><a href="#" class="fw-normal">Bank Add</a></li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12">
            <div class="white-box">
                {!! Form::open(['method' => 'post', 'route' => ['admin.bank.store'], 'enctype' => 'multipart/form-data']) !!}
                @csrf
                <div class="form-group">
                    <label for="">Name Bank</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                    <i class="text-danger">{{ $errors->first('name') }}</i>
                </div>
                <div class="form-group">
                    <label for="">Name Rekening</label>
                    <input type="text" name="name_rekening" class="form-control" value="{{ old('name_rekening') }}">
                    <i class="text-danger">{{ $errors->first('name_rekening') }}</i>
                </div>
                <div class="form-group">
                    <label for="">No Rekening</label>
                    <input type="text" name="no_rekening" class="form-control" value="{{ old('no_rekening') }}">
                    <i class="text-danger">{{ $errors->first('no_rekening') }}</i>
                </div>
                <div class="form-group">
                    <label for="">Logo</label>
                    <input type="file" name="logo" class="form-control">
                    <i class="text-danger">{{ $errors->first('logo') }}</i>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

</div>
@endsection
