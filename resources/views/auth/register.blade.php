@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')

<div class="site-section site-blocks-2" style="background-image: url({{ url('/images/background-image.png') }}); background-size:cover; background-repeat:no-repeat;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12 mb-4 mb-lg-0" data-aos="fade" data-aos-delay="">
                <div class="login-box-2">
                        <div class="login-title">
                            <h2 class="text-center">Register</h2>
                        </div>
                    {!! Form::open(['method' => 'post', 'route' => ['register.store'], 'enctype' => 'multipart/form-data']) !!}
                    @csrf
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}">
                                    <i class="text-danger">{{ $errors->first('name') }}</i>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">No Handphone</label>
                                    <input type="text" name="no_hp" class="form-control" placeholder="No Handphone" value="{{ old('no_hp') }}">
                                    <i class="text-danger">{{ $errors->first('no_hp') }}</i>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Whatsapp</label>
                                    <input type="text" name="whatsapp" class="form-control" placeholder="Whatsapp" value="{{ old('whatsapp') }}">
                                    <i class="text-danger">{{ $errors->first('whatsapp') }}</i>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                    <i class="text-danger">{{ $errors->first('email') }}</i>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="0">Women</option>
                                        <option value="1">Men</option>
                                    </select>
                                    <i class="text-danger">{{ $errors->first('gender') }}</i>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Birthday</label>
                                    <input type="date" name="birthday" class="form-control">
                                    <i class="text-danger">{{ $errors->first('birthday') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label for="">Foto</label>
                                    <input type="file" name="foto" class="form-control">
                                    <i class="text-danger">{{ $errors->first('foto') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Address</label>
                                    <textarea name="address" class="form-control" rows="5">{{ old('address') }}</textarea>
                                    <i class="text-danger">{{ $errors->first('address') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Google Maps</label>
                                    <input type="text" id="address-input" name="google_map" class="form-control map-input">
                                    <i class="text-danger">{{ $errors->first('google_map') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div id="address-map-container" style="width:100%;height:400px; ">
                                    <div style="width: 100%; height: 100%" id="address-map"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" name="address_latitude" id="address-latitude" class="form-control" value="0" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" name="address_longitude" id="address-longitude" class="form-control" value="0" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="********">
                                    <i class="text-danger">{{ $errors->first('password') }}</i>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="">Password Confirmation</label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="********">
                                    <i class="text-danger">{{ $errors->first('password') }}</i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary btn-block btn-lg">Register</button>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-block btn-lg">Login</a>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
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
