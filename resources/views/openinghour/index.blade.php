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
                        {!! Form::open(['route' => ['openinghour.store'], 'enctype' => 'multipart/form-data', 'method' => 'post']) !!}
                        @csrf
                        <div class="box-2-text">
                            <div class="row form-group">
                                <label class="col-md-5 text-left">Day</label>
                                <select name="day" class="col-md-7 form-control">
                                    <option value="">- Select -</option>
                                    @foreach ($days as $day)
                                        <option value="{{ $day }}">{{ $day }}</option>
                                    @endforeach
                                </select>
                                <i class="text-danger">{{ $errors->first('day') }}</i>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 text-left">Open/Close</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" onclick="opencloseCheck()" name="status" id="open" value="1" checked>
                                    <label class="form-check-label">
                                        Open
                                    </label>
                                </div>
                                <div class="form-check ml-3">
                                    <input class="form-check-input" type="radio" onclick="opencloseCheck()" name="status" id="close" value="0">
                                    <label class="form-check-label">
                                        Close
                                    </label>
                                </div>
                                <i class="text-danger">{{ $errors->first('status') }}</i>
                            </div>
                            <div class="row form-group" id="opentime">
                                <label class="col-md-5 text-left">Open Time</label>
                                <input type="time" name="open_time" class="col-md-7 form-control">
                                <i class="text-danger">{{ $errors->first('open_time') }}</i>
                            </div>
                            <div class="row form-group" id="closetime">
                                <label class="col-md-5 text-left">Close Time</label>
                                <input type="time" name="close_time" class="col-md-7 form-control">
                                <i class="text-danger">{{ $errors->first('close_time') }}</i>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block">DONE</button>
                                    <a href="{{ route('shopprofile.index') }}" class="btn btn-info btn-block">BACK</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="box">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Day</th>
                                        <th>Open/Close</th>
                                        <th>Open Time</th>
                                        <th>Close Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($opening as $key => $row)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $row->day }}</td>
                                            <td>
                                                @if ($row->status==1)
                                                    <span class="badge badge-success">Open</span>
                                                @else
                                                    <span class="badge badge-danger">Close</span>
                                                @endif
                                            </td>
                                            <td>{{ $row->open }}</td>
                                            <td>{{ $row->close }}</td>
                                            <td>
                                                {!! Form::open(['method' => 'post', 'route' => ['openinghour.destroy', $row->id]]) !!}
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><span class="icon icon-trash"></span></button>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                    @endforeach --}}
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
    <script type="text/javascript">
        function opencloseCheck() {
            if (document.getElementById('open').checked) {
                document.getElementById('opentime').style.display = 'flex';
                document.getElementById('closetime').style.display = 'flex';
            } else if (document.getElementById('close').checked) {
                document.getElementById('opentime').style.display = 'none';
                document.getElementById('closetime').style.display = 'none';
            }
        }
    </script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var table = $('#example1').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "ordering": 'true',
                ajax: {
                    url: "{{ route('openinghour.list') }}",
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
                        data: 'day',
                        name: 'day'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'open',
                        name: 'open'
                    },
                    {
                        data: 'close',
                        name: 'close'
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
