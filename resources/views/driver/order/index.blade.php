@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section" style="background: #fff">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="site-blocks-table table-responsive">

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

                        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="order-tab" data-toggle="tab" data-target="#order"
                                    type="button" role="tab" aria-controls="order" aria-selected="true">Order Progress</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="order-finished-tab" data-toggle="tab" data-target="#order-finished"
                                    type="button" role="tab" aria-controls="order-finished"
                                    aria-selected="false">Order Finished</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="order" role="tabpanel"
                                aria-labelledby="order-tab">
                                <table id="example1" class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Member</th>
                                            <th>Shop</th>
                                            <th>Service</th>
                                            <th>Distance</th>
                                            <th>Shipping Cost</th>
                                            <th>Cast</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th class="product-remove">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="order-finished" role="tabpanel" aria-labelledby="order-finished-tab">
                                <table id="example2" class="table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Member</th>
                                            <th>Shop</th>
                                            <th>Service</th>
                                            <th>Distance</th>
                                            <th>Shipping Cost</th>
                                            <th>Cast</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @foreach ($tracking as $row)
                                            <tr>
                                                <td>{{ $row->order_id }}</td>
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
                    url: "{{ route('driver.order.list') }}",
                    data: function(d) {}
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'shop',
                        name: 'shop'
                    },
                    {
                        data: 'name',
                        name: 'services.name'
                    },
                    {
                        data: 'distance',
                        name: 'distance'
                    },
                    {
                        data: 'shipping_cost',
                        name: 'shipping_cost'
                    },
                    {
                        data: 'cast',
                        name: 'cast'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'status_tracking',
                        name: 'status_tracking'
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

        $(function() {
            var table = $('#example2').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "ordering": 'true',
                ajax: {
                    url: "{{ route('driver.order.finish') }}",
                    data: function(d) {}
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'shop',
                        name: 'shop'
                    },
                    {
                        data: 'name',
                        name: 'services.name'
                    },
                    {
                        data: 'distance',
                        name: 'distance'
                    },
                    {
                        data: 'shipping_cost',
                        name: 'shipping_cost'
                    },
                    {
                        data: 'cast',
                        name: 'cast'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'status_tracking',
                        name: 'status_tracking'
                    }
                ]
            });
        });
    </script>
@endsection
