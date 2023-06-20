@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-5 justify-content-center">
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

                    @if (Str::length(Auth::guard('web')->user()) > 0)
                        {!! Form::model($profile, ['route' => ['profile.update', $profile->id], 'enctype' => 'multipart/form-data']) !!}
                    @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                        {!! Form::model($profile, ['route' => ['shopprofile.update', $profile->id], 'enctype' => 'multipart/form-data']) !!}
                    @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                        {!! Form::model($profile, ['route' => ['driverprofile.update', $profile->id], 'enctype' => 'multipart/form-data']) !!}
                    @endif
                    @csrf
                    @method('PUT')
                    <div class="box-2-image">
                        @if (Str::length(Auth::guard('web')->user()) > 0 || Str::length(Auth::guard('webdriver')->user()) > 0)
                            @if ($profile->foto == NULL || $profile->foto == '')
                                <img src="{{ url('/images/no-image.jpg') }}">
                            @else
                                <img src="{{ url($profile->foto) }}">
                            @endif
                        @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                            @if ($profile->logo == NULL || $profile->logo == '')
                                <img src="{{ url('/images/no-image.jpg') }}">
                            @else
                                <img src="{{ url($profile->logo) }}">
                            @endif
                        @endif
                        <input type="file" name="foto" class="form-control mt-3">
                    </div>
                    <div class="box-2-text">
                        <div class="row form-group">
                            <label class="col-md-5 text-left">Name</label>
                            <input type="text" name="name" class="col-md-7 form-control" value="{{ $profile->name }}">
                        </div>
                        <div class="row form-group">
                            <label class="col-md-5 text-left">No Handphone</label>
                            <input type="text" name="no_hp" class="col-md-7 form-control" value="{{ $profile->no_hp }}">
                        </div>
                        <div class="row form-group">
                            <label class="col-md-5 text-left">Email</label>
                            <input type="text" name="email" class="col-md-7 form-control" value="{{ $profile->email }}">
                        </div>

                        @if (Str::length(Auth::guard('webdriver')->user()) > 0)
                            <div class="row form-group">
                                <label class="col-md-5 text-left">Whatsapp</label>
                                <input type="text" name="whatsapp" class="col-md-7 form-control" value="{{ $profile->whatsapp }}">
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 text-left">Name Rekening</label>
                                <input type="text" name="name_rekening" class="col-md-7 form-control" value="{{ $profile->name_rekening }}">
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 text-left">No Rekening</label>
                                <input type="text" name="no_rekening" class="col-md-7 form-control" value="{{ $profile->no_rekening }}">
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 text-left">Bank</label>
                                <select name="bank" class="col-md-7 form-control">
                                    <option value="" @if($profile->bank_id==NULL) {{ 'selected' }} @endif>- Pilih -</option>
                                    @foreach ($bank as $row)
                                        <option value="{{ $row->id }}" @if($profile->bank_id==$row->id) {{ 'selected' }} @endif>{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if (Str::length(Auth::guard('web')->user()) > 0)
                            <div class="row form-group">
                                <label class="col-md-5 text-left">Birthday</label>
                                <input type="date" name="tgl_lahir" class="col-md-7 form-control" value="{{ $profile->tgl_lahir }}">
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 text-left">Jenis Kelamin</label>
                                <select name="jns_kelamin" class="col-md-7 form-control">
                                    <option value="" @if($profile->jns_kelamin==NULL) {{ 'selected' }} @endif>- Pilih -</option>
                                    <option value="0" @if($profile->jns_kelamin=='0') {{ 'selected' }} @endif>Perempuan</option>
                                    <option value="1" @if($profile->jns_kelamin=='1') {{ 'selected' }} @endif>Pria</option>
                                </select>
                            </div>
                        @endif

                        @if ($profile->address <> NULL)
                            <div class="row form-group">
                                <label class="col-md-5 text-left">Address</label>
                                <input type="text" name="address" class="col-md-7 form-control" value="{{ $profile->address }}" readonly>
                            </div>
                            <div class="row form-group">
                                <label class="col-md-5 text-left">Google Map</label>
                                <input type="text" name="google_map" class="col-md-7 form-control" value="{{ $profile->google_map }}" readonly>
                            </div>
                        @endif
                        <div class="row form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-block">DONE</button>
                                @if (Str::length(Auth::guard('web')->user()) > 0)
                                    <a href="{{ route('profile.index') }}" class="btn btn-info btn-block">BACK</a>
                                    <a href="{{ route('logout') }}" class="btn btn-danger btn-block">SIGN OUT</a>
                                @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                                    <a href="{{ route('shopprofile.index') }}" class="btn btn-info btn-block">BACK</a>
                                    <a href="{{ route('shop.logout') }}" class="btn btn-danger btn-block">SIGN OUT</a>
                                @elseif (Str::length(Auth::guard('webdriver')->user()) > 0)
                                    <a href="{{ route('driverprofile.index') }}" class="btn btn-info btn-block">BACK</a>
                                    <a href="{{ route('driver.logout') }}" class="btn btn-danger btn-block">SIGN OUT</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>

            @if (Str::length(Auth::guard('webdriver')->user()) <= 0)

                <div class="col-md-7">
                    <div class="box-2">
                        <div class="box-2-text">
                            @if (Str::length(Auth::guard('web')->user()) > 0)
                                {!! Form::open(['route' => ['profile.updateaddress'], 'enctype' => 'multipart/form-data', 'method' => 'post']) !!}
                            @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                                {!! Form::open(['route' => ['shopprofile.updateaddress'], 'enctype' => 'multipart/form-data', 'method' => 'post']) !!}
                            @endif
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">Address</label>
                                        @if (Str::length(Auth::guard('web')->user()) > 0)
                                            <textarea name="address" class="form-control" rows="3">{{ Auth::guard('web')->user()->address }}</textarea>
                                        @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                                            <textarea name="address" class="form-control" rows="3">{{ Auth::guard('webshop')->user()->address }}</textarea>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Google Map</label>
                                        @if (Str::length(Auth::guard('web')->user()) > 0)
                                            <input type="text" id="address-input" name="google_map" class="form-control map-input" value="{{ Auth::guard('web')->user()->google_map }}">
                                        @elseif (Str::length(Auth::guard('webshop')->user()) > 0)
                                            <input type="text" id="address-input" name="google_map" class="form-control map-input" value="{{ Auth::guard('webshop')->user()->google_map }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="address-map-container" style="width:100%;height:400px; ">
                                        <div style="width: 100%; height: 100%" id="address-map"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Latitude</label>
                                        <input type="text" name="address_latitude" id="address-latitude" class="form-control" @if(Auth::user()->latitude <> NULL) value="{{ Auth::user()->latitude }}" @else value="0" @endif readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Longitude</label>
                                        <input type="text" name="address_longitude" id="address-longitude" class="form-control" @if(Auth::user()->longitude <> NULL) value="{{ Auth::user()->longitude }}" @else value="0" @endif readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block">DONE</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>
</div>

@endsection

@section('script')
    {{-- <script type="text/javascript" src="https://maps.google.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=initMap" ></script> --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initialize" async defer></script>
    <script type="text/javascript">
        function initialize() {

            $('form').on('keyup keypress', function(e) {
                var keyCode = e.keyCode || e.which;
                if (keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });
            const locationInputs = document.getElementsByClassName("map-input");

            const autocompletes = [];
            const geocoder = new google.maps.Geocoder;
            for (let i = 0; i < locationInputs.length; i++) {

                const input = locationInputs[i];
                const fieldKey = input.id.replace("-input", "");
                const isEdit = document.getElementById(fieldKey + "-latitude").value != '' && document.getElementById(fieldKey + "-longitude").value != '';

                const latitude = parseFloat(document.getElementById(fieldKey + "-latitude").value) || -6.200000;
                const longitude = parseFloat(document.getElementById(fieldKey + "-longitude").value) || 106.816666;

                const map = new google.maps.Map(document.getElementById(fieldKey + '-map'), {
                    center: {lat: latitude, lng: longitude},
                    zoom: 13
                });
                const marker = new google.maps.Marker({
                    map: map,
                    position: {lat: latitude, lng: longitude},
                });

                marker.setVisible(isEdit);

                const autocomplete = new google.maps.places.Autocomplete(input);
                autocomplete.key = fieldKey;
                autocompletes.push({input: input, map: map, marker: marker, autocomplete: autocomplete});
            }

            for (let i = 0; i < autocompletes.length; i++) {
                const input = autocompletes[i].input;
                const autocomplete = autocompletes[i].autocomplete;
                const map = autocompletes[i].map;
                const marker = autocompletes[i].marker;

                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    marker.setVisible(false);
                    const place = autocomplete.getPlace();

                    geocoder.geocode({'placeId': place.place_id}, function (results, status) {
                        if (status === google.maps.GeocoderStatus.OK) {
                            const lat = results[0].geometry.location.lat();
                            const lng = results[0].geometry.location.lng();
                            setLocationCoordinates(autocomplete.key, lat, lng);
                        }
                    });

                    if (!place.geometry) {
                        window.alert("No details available for input: '" + place.name + "'");
                        input.value = "";
                        return;
                    }

                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }
                    marker.setPosition(place.geometry.location);
                    marker.setVisible(true);

                });
            }
        }

        function setLocationCoordinates(key, lat, lng) {
            const latitudeField = document.getElementById(key + "-" + "latitude");
            const longitudeField = document.getElementById(key + "-" + "longitude");
            latitudeField.value = lat;
            longitudeField.value = lng;
        }
    </script>
@endsection
