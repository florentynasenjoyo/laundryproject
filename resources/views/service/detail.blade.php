@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-primary page-title">{{ $service->name }}</h2>
                    <div class="box-4">
                        <div class="box-4-image" style="background-image: url({{ url($service->image) }})"></div>
                        <div class="box-4-text">
                            <h2>
                                <span>{{ $service->name_shop }}</span><br>
                                {{ $service->name }}
                            </h2>
                            <h3>Rp. {{ number_format($service->price_weight) }} /kg</h3>
                            <h3>Rp. {{ number_format($service->price_unit) }} /unit</h3>
                            <div class="text-justify text-black">
                                {!! $service->description !!}
                            </div>
                            @if (Session::get('users') == 'user')
                                {!! Form::open(['method' => 'post', 'route' => ['order.store']]) !!}
                                @php
                                    $lat1 = Auth::user()->latitude;
                                    $lon1 = Auth::user()->longitude;
                                    $lat2 = $service->latitude;
                                    $lon2 = $service->longitude;
                                    $theta = $lon1 - $lon2;
                                    $miles = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                                    $miles = acos($miles);
                                    $miles = rad2deg($miles);
                                    $miles = $miles * 60 * 1.1515;
                                    $kilometers = $miles * 1.609344;
                                @endphp
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <input type="hidden" name="service_id" value="{{ $service->id }}">
                                        <input type="hidden" name="distance" value="{{ round($kilometers, 2) }}">
                                        <div class="form-group">
                                            <label for="">Type</label><br>
                                            <input type="radio" name="type" value="0"> Weight
                                            <input type="radio" name="type" value="1"> Unit
                                        </div>
                                        <i class="text-danger">{{ $errors->first('type') }}</i>
                                        <div class="form-group">
                                            <div class="input-group mb-3" style="max-width: 150px;">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-outline-primary btn-sm js-btn-minus"
                                                        type="button">&minus;</button>
                                                </div>
                                                <input type="text" name="amount" class="form-control text-center"
                                                    value="1" placeholder=""
                                                    aria-label="Example text with button addon"
                                                    aria-describedby="button-addon1">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary btn-sm js-btn-plus"
                                                        type="button">&plus;</button>
                                                </div>
                                            </div>
                                            <i class="text-danger">{{ $errors->first('amount') }}</i>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @foreach ($rekening as $row)
                                            <div class="form-group d-flex">
                                                <input type="radio" name="bank" value="{{ $row->id }}">
                                                <img src="{{ url($row->logo) }}" width="70" class="mr-2">
                                                <p class="text-black" style="margin: 12px 10px 0 0;">
                                                    {{ $row->no_rekening }}</p>
                                                <p class="text-black" style="margin: 12px 10px 0 0;">
                                                    {{ $row->name_rekening }}</p>
                                            </div>
                                            <i class="text-danger">{{ $errors->first('bank') }}</i>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-gradient">ORDER</button>
                                {!! Form::close() !!}
                            @endif
                        </div>
                    </div>

                    <div class="box-5">

                            <div class="container">
                                <div class="row">
                                    @if ($message = Session::get('success'))
                                    <div class="col-12">
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <p>{{ $message }}</p>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                    @elseif ($message = Session::get('danger'))
                                    <div class="col-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <p>{{ $message }}</p>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col mt-4">
                                        <p class="font-weight-bold text-black">Review</p>

                                        @foreach ($review as $value)
                                            <div class="form-group row">
                                                <div class="col">
                                                    <div class="rated">
                                                        @for ($i = 1; $i <= $value->star_rating; $i++)
                                                            {{-- <input type="radio" id="star{{$i}}" class="rate" name="rating" value="5"/> --}}
                                                            <label class="star-rating-complete"
                                                                title="text">{{ $i }} stars</label>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col d-flex">
                                                    <img src="{{ url($value->foto) }}" width="70">
                                                    <div class="text ml-2 text-black">
                                                        <h6>{{ $value->name }}</h6>
                                                        <p>{{ $value->comments }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @if (Str::length(Auth::guard('web')->user()) > 0)
                            @if ($reviewcount <= 0)
                                <div class="container">
                                    <div class="row">

                                        <div class="col mt-4 mb-4">
                                            {!! Form::open(['method' => 'post', 'route' => ['review.service.store']]) !!}
                                            @csrf
                                            <div class="form-group row">
                                                <input type="hidden" name="service_id" value="{{ $service->id }}">
                                                <div class="col">
                                                    <div class="rate">
                                                        <input type="radio" id="star5" class="rate" name="rating"
                                                            value="5" />
                                                        <label for="star5" title="text">5 stars</label>
                                                        <input type="radio" checked id="star4" class="rate"
                                                            name="rating" value="4" />
                                                        <label for="star4" title="text">4 stars</label>
                                                        <input type="radio" id="star3" class="rate" name="rating"
                                                            value="3" />
                                                        <label for="star3" title="text">3 stars</label>
                                                        <input type="radio" id="star2" class="rate" name="rating"
                                                            value="2">
                                                        <label for="star2" title="text">2 stars</label>
                                                        <input type="radio" id="star1" class="rate" name="rating"
                                                            value="1" />
                                                        <label for="star1" title="text">1 star</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-4">
                                                <div class="col">
                                                    <textarea class="form-control" name="comment" rows="6 " placeholder="Comment" maxlength="200"></textarea>
                                                </div>
                                            </div>
                                            <div class="mt-3 text-right">
                                                <button class="btn btn-sm py-2 px-3 btn-info">Submit
                                                </button>
                                            </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection
