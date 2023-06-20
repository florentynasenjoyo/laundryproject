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
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="order-canceled-tab" data-toggle="tab" data-target="#order-canceled"
                                    type="button" role="tab" aria-controls="order-canceled"
                                    aria-selected="false">Order Canceled</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="order" role="tabpanel"
                                aria-labelledby="order-tab">
                                <table id="example1" class="table table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Member</th>
                                            <th>Image</th>
                                            <th>Service</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                            <th>Shipping Cost</th>
                                            <th width="60">Total</th>
                                            <th>Method Payment</th>
                                            <th>Status</th>
                                            <th class="product-remove">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="order-finished" role="tabpanel" aria-labelledby="order-finished-tab">
                                <table id="example2" class="table table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Member</th>
                                            <th>Image</th>
                                            <th>Service</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                            <th>Shipping Cost</th>
                                            <th width="60">Total</th>
                                            <th>Method Payment</th>
                                            <th>Status</th>
                                            <th class="product-remove">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="order-canceled" role="tabpanel" aria-labelledby="order-canceled-tab">
                                <table id="example3" class="table table-bordered" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Member</th>
                                            <th>Image</th>
                                            <th>Service</th>
                                            <th>Type</th>
                                            <th>Price</th>
                                            <th>Amount</th>
                                            <th>Shipping Cost</th>
                                            <th width="60">Total</th>
                                            <th>Method Payment</th>
                                            <th>Status</th>
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
                    url: "{{ route('shop.order.list') }}",
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
                        data: 'member_name',
                        name: 'users.name'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'name',
                        name: 'services.name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'shipping_cost',
                        name: 'shipping_cost'
                    },
                    {
                        data: 'price_total',
                        name: 'price_total'
                    },
                    {
                        data: 'logo',
                        name: 'logo'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
                    url: "{{ route('shop.order.list.finish') }}",
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
                        data: 'member_name',
                        name: 'users.name'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'name',
                        name: 'services.name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'shipping_cost',
                        name: 'shipping_cost'
                    },
                    {
                        data: 'price_total',
                        name: 'price_total'
                    },
                    {
                        data: 'logo',
                        name: 'logo'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
            var table = $('#example3').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                "ordering": 'true',
                ajax: {
                    url: "{{ route('shop.order.list.cancel') }}",
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
                        data: 'member_name',
                        name: 'users.name'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'name',
                        name: 'services.name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'shipping_cost',
                        name: 'shipping_cost'
                    },
                    {
                        data: 'price_total',
                        name: 'price_total'
                    },
                    {
                        data: 'logo',
                        name: 'logo'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    }
                ]
            });
        });
    </script>
@endsection
