@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-primary page-title">Services {{ $shop->name }}</h2>
                </div>
                @foreach ($services as $row)
                    <div class="col-md-4 col-sm-12 mt-3">
                        <div class="box-3">
                            <div class="box-3-image" style="background-image: url({{ url($row->image) }})"></div>
                            <div class="box-3-text text-center">
                                <a href="{{ route('services.show', ['slug' => $shop->slug, 'id' => $row->slug]) }}">
                                    <h2>
                                        <span>{{ $shop->name }}</span><br>
                                        {{ $row->name }}
                                    </h2>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
