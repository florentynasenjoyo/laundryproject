@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section">
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <div class="box-3 p-3">
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
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Price Weight(Per KG)</th>
                                        <th>Price Unit(Per Unit)</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 justify-content-center mt-3">
                    <div class="box-2">
                    @if ($status == 'Show')
                        {!! Form::open(['route' => ['shopservice.store'], 'enctype' => 'multipart/form-data', 'method' => 'post']) !!}
                        @csrf
                        <div class="box-2-text">
                            <div class="row form-group">
                                <label class="col-md-2 text-left">Name</label>
                                <input type="text" name="name" class="col-md-10 form-control">
                                <i class="text-danger">{{ $errors->first('name') }}</i>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-2 text-left">Image</label>
                                <input type="file" name="image" class="col-md-10 form-control">
                                <i class="text-danger">{{ $errors->first('image') }}</i>
                            </div>
                            <div class="row form-group">
                                <label for="" class="col-md-2 text-left">Price Weight(Per KG)</label>
                                <input type="number" name="price_weight" class="col-md-10 form-control">
                                <i class="text-danger">{{ $errors->first('price_weight') }}</i>
                            </div>
                            <div class="row form-group">
                                <label for="" class="col-md-2 text-left">Price Unit(Per Unit)</label>
                                <input type="number" name="price_unit" class="col-md-10 form-control">
                                <i class="text-danger">{{ $errors->first('price_unit') }}</i>
                            </div>
                            <div class="row form-group" id="opentime">
                                <label class="col-md-2 text-left">Description</label>
                                <textarea name="description" id="editor" class="col-md-10 form-control"></textarea>
                                <i class="text-danger">{{ $errors->first('description') }}</i>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6 col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-block">ADD</button>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <a href="{{ route('shopprofile.index') }}" class="btn btn-info btn-block">BACK</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    @elseif ($status == 'Edit')
                        {!! Form::model($service, ['route' => ['shopservice.update', $service->id], 'enctype' => 'multipart/form-data', 'method' => 'post']) !!}
                        @csrf
                        @method('PUT')
                        <div class="box-2-text">
                            <div class="row form-group">
                                <label class="col-md-2 text-left">Name</label>
                                <input type="text" name="name" class="col-md-10 form-control" value="{{ $service->name }}">
                                <i class="text-danger">{{ $errors->first('name') }}</i>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-2"></div>
                                <div class="col-md-10">
                                    <img src="{{ url($service->image) }}" width="120">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-2 text-left">Image</label>
                                <input type="file" name="image" class="col-md-10 form-control">
                                <i class="text-danger">{{ $errors->first('image') }}</i>
                            </div>
                            <div class="row form-group">
                                <label for="" class="col-md-2 text-left">Price Weight(Per KG)</label>
                                <input type="number" name="price_weight" class="col-md-10 form-control" value="{{ $service->price_weight }}">
                                <i class="text-danger">{{ $errors->first('price_weight') }}</i>
                            </div>
                            <div class="row form-group">
                                <label for="" class="col-md-2 text-left">Price Unit(Per Unit)</label>
                                <input type="number" name="price_unit" class="col-md-10 form-control" value="{{ $service->price_unit }}">
                                <i class="text-danger">{{ $errors->first('price_unit') }}</i>
                            </div>
                            <div class="row form-group" id="opentime">
                                <label class="col-md-2 text-left">Description</label>
                                <textarea name="description" id="editor" class="col-md-10 form-control" rows="5">{{ $service->description }}</textarea>
                                <i class="text-danger">{{ $errors->first('description') }}</i>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4 col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-block">UPDATE</button>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <a href="{{ route('shopservice.show', Auth::user()->id) }}" class="btn btn-danger btn-block">RESET</a>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <a href="{{ route('shopprofile.index') }}" class="btn btn-info btn-block">BACK</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        var editor = document.getElementById('editor');
        CKEDITOR.replace(editor);
        $(function() {
            var table = $('#example1').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "ordering": 'true',
                ajax: {
                    url: "{{ route('shopservice.list') }}",
                    data: function(d) {}
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'price_weight',
                        name: 'price_weight'
                    },
                    {
                        data: 'price_unit',
                        name: 'price_unit'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
@endsection

