@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-12 justify-content-center">
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

                        @if ($status == 'Show')
                            {!! Form::open(['route' => ['rekening.store'], 'enctype' => 'multipart/form-data', 'method' => 'post']) !!}
                            @csrf
                            <div class="box-2-text">
                                <div class="row form-group">
                                    <label class="col-md-5 text-left">Name Rekening</label>
                                    <input type="text" name="name_rekening" class="col-md-7 form-control">
                                    <i class="text-danger">{{ $errors->first('name_rekening') }}</i>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-5 text-left">No Rekening</label>
                                    <input type="text" name="no_rekening" class="col-md-7 form-control">
                                    <i class="text-danger">{{ $errors->first('no_rekening') }}</i>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-5 text-left">Bank</label>
                                    <select name="bank_id" class="col-md-7 form-control">
                                        <option value="0">- Select -</option>
                                        @foreach ($banks as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="text-danger">{{ $errors->first('bank_id') }}</i>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block">DONE</button>
                                        <a href="{{ route('shopprofile.index') }}" class="btn btn-info btn-block">BACK</a>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @elseif ($status == 'Edit')
                            {!! Form::model($rekening, ['route' => ['rekening.update', $rekening->id], 'enctype' => 'multipart/form-data', 'method' => 'post']) !!}
                            @csrf
                            @method('PUT')
                            <div class="box-2-text">
                                <div class="row form-group">
                                    <label class="col-md-5 text-left">Name Rekening</label>
                                    <input type="text" name="name_rekening" class="col-md-7 form-control" value="{{ $rekening->name_rekening }}">
                                    <i class="text-danger">{{ $errors->first('name_rekening') }}</i>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-5 text-left">No Rekening</label>
                                    <input type="text" name="no_rekening" class="col-md-7 form-control" value="{{ $rekening->no_rekening }}">
                                    <i class="text-danger">{{ $errors->first('no_rekening') }}</i>
                                </div>
                                <div class="row form-group">
                                    <label class="col-md-5 text-left">Bank</label>
                                    <select name="bank_id" class="col-md-7 form-control">
                                        <option value="0">- Select -</option>
                                        @foreach ($banks as $row)
                                            <option value="{{ $row->id }}" @if($rekening->bank_id==$row->id) {{ 'selected' }} @endif>{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="text-danger">{{ $errors->first('bank_id') }}</i>
                                </div>

                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-block">DONE</button>
                                        <a href="{{ route('shopprofile.index') }}" class="btn btn-info btn-block">BACK</a>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @endif


                    </div>
                </div>
                <div class="col-md-7 col-sm-12">
                    <div class="box">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name Rekening</th>
                                        <th>No Rekening</th>
                                        <th>Bank</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('#example1').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "ordering": 'true',
                ajax: {
                    url: "{{ route('rekening.list') }}",
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
                        data: 'name_rekening',
                        name: 'name_rekening'
                    },
                    {
                        data: 'no_rekening',
                        name: 'no_rekening'
                    },
                    {
                        data: 'image',
                        name: 'image'
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
