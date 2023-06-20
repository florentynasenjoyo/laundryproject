@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if ($shops->count() > 0)
                        @foreach ($shops as $row)
                            <div class="box justify-content-between">
                                <div class="d-flex">
                                    <div class="box-image">
                                        <a href="{{ route('shop.show', $row->slug) }}">
                                            @if ($row->logo == NULL || $row->logo == '')
                                                <img src="{{ url('/images/no-image.jpg') }}">
                                            @else
                                                <img src="{{ url($row->logo) }}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="box-text">
                                        <h2 class="text-left">
                                            <a href="{{ route('shop.show', $row->slug) }}">
                                                {{ $row->name }}
                                            </a>
                                        </h2>
                                        {{-- <div>
                                            <span class="icon icon-star"></span>
                                            <span class="icon icon-star"></span>
                                            <span class="icon icon-star"></span>
                                            <span class="icon icon-star"></span>
                                            <span class="icon icon-star"></span>
                                        </div> --}}
                                        <div>
                                            @if ($row->status=='1')
                                                <p class="mb-0">{{ $row->day.' '.$row->open.' - '.$row->close }}</p>
                                                @if (date('H:i') < $row->open)
                                                    <span class="badge badge-danger">CLOSED</span>
                                                @elseif (date('H:i') > $row->close)
                                                    <span class="badge badge-danger">CLOSED</span>
                                                @else
                                                    <span class="badge badge-success">OPEN</span>
                                                @endif
                                            @else
                                                <span class="badge badge-danger">CLOSED</span>
                                            @endif
                                        </div>
                                        @if (Session::get('users')=='user')
                                            <div>
                                                @php
                                                    $lat1 = Auth::user()->latitude;
                                                    $lon1 = Auth::user()->longitude;
                                                    $lat2 = $row->latitude;
                                                    $lon2 = $row->longitude;
                                                    $theta = $lon1 - $lon2;
                                                    $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
                                                    $miles = acos($miles);
                                                    $miles = rad2deg($miles);
                                                    $miles = $miles * 60 * 1.1515;
                                                    $kilometers = $miles * 1.609344;
                                                @endphp
                                                <p class="text-primary">
                                                    <strong>
                                                        {{ round($kilometers,2).' KM' }}
                                                    </strong>
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="box-flex align-items-center">
                                    <div class="box-text">
                                        <h2 class="text-right">
                                            <a href="#" class="text-right">
                                                <span class="icon icon-phone"></span>
                                            </a>
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="box">
                            <div class="box-text">
                                <h2>No Shops Open</h2>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
