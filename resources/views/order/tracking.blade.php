@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="login-box">
                        <div class="fullwidth">
                            <div class="container separator">
                                <h3 class="text-black">Tracking</h3>

                                <ul class="progress-tracker progress-tracker--vertical">
                                    @foreach ($tracking as $key => $row)
                                        @if ($row->status==1)
                                            @php
                                                $step = 'is-active';
                                                $progress = 'Process Pickup';
                                                $driver = $row->driver_name;
                                            @endphp
                                        @elseif ($row->status==2)
                                            @php
                                                $step = 'is-active';
                                                $progress = 'Pickup Driver';
                                                $driver = $row->driver_name;
                                            @endphp
                                        @elseif ($row->status==3)
                                            @php
                                                $step = 'is-active';
                                                $progress = 'Already in Store';
                                                $driver = $row->driver_name;
                                            @endphp
                                        @elseif ($row->status==4)
                                            @php
                                                $step = 'is-active';
                                                $progress = 'Processed Store';
                                                $driver = '';
                                            @endphp
                                        @elseif ($row->status==5)
                                            @php
                                                $step = 'is-active';
                                                $progress = 'Delivery to customer';
                                                $driver = $row->driver_name;
                                            @endphp
                                        @elseif ($row->status==6)
                                            @php
                                                $step = 'is-active';
                                                $progress = 'Order received by customer';
                                                $driver = $row->driver_name;
                                            @endphp
                                        @elseif ($row->status==7)
                                            @php
                                                $step = 'is-active';
                                                $progress = 'Finished';
                                                $driver = '';
                                            @endphp
                                        @endif

                                        <li class="progress-step @if(++$key==$row->status) {{ $step }} @else is-complete @endif">
                                            <div class="progress-marker"></div>
                                            <div class="progress-text">
                                                <h4 class="progress-title">
                                                    {{ $progress }}
                                                </h4>
                                                {{ $driver }}
                                            </div>
                                        </li>

                                    @endforeach

                                </ul>

                            </div>
                            <div class="text-center">
                                @if (Str::length(Auth::guard('web')->user()) > 0)
                                    <a href="{{ route('order.index') }}" class="btn btn-primary">OKE</a>
                                @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                                    <a href="{{ route('shop.order') }}" class="btn btn-primary">OKE</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
