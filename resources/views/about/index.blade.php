@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section border-bottom" data-aos="fade">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-6">
                    <div class="block-16">
                        <figure>
                            <img src="{{ url($about->image_about) }}" alt="{{ $about->website_name }}" class="img-fluid rounded">
                        </figure>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">

                    <div class="site-section-heading pt-3 mb-4">
                        <h2 class="text-black">{{ $about->website_name }}</h2>
                    </div>
                    <div class="text-black text-justify">
                        {!! $about->about !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
