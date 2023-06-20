@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section">
        <div class="container">
            <div class="row">
                @if ($services->count() > 0)
                    <div class="col-md-12">
                        <h2 class="text-primary page-title">Services</h2>
                    </div>
                    @foreach ($services as $row)
                        <div class="col-md-4 col-sm-12 mt-3">
                            <div class="box-3">
                                <div class="box-3-image" style="background-image: url({{ url($row->image) }})"></div>
                                <div class="box-3-text text-center">
                                    <a href="{{ route('services.show', ['slug' => $row->slug_shop, 'id' => $row->slug]) }}">
                                        <h2>
                                            <span>{{ $row->name_shop }}</span><br>
                                            {{ $row->name }}
                                        </h2>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="box">
                        <div class="box-text">
                            <h2>Service Not Found</h2>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
