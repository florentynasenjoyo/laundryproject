@extends('admin.layouts.app')
@include('admin.layouts.partials.css')
@include('admin.layouts.partials.js')

@section('content')
<div class="page-breadcrumb bg-white">
    <div class="row align-items-center">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Setting</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <div class="d-md-flex">
                <ol class="breadcrumb ms-auto">
                    <li><a href="#" class="fw-normal">Setting Edit</a></li>
                </ol>
            </div>
        </div>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">

    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div class="white-box">
                {!! Form::model($setting, ['method' => 'post', 'route' => ['admin.setting.update', $setting->id], 'enctype' => 'multipart/form-data']) !!}
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Website Name</label>
                            <input type="text" name="website_name" class="form-control" value="{{ $setting->website_name }}">
                            <i class="text-danger">{{ $errors->first('website_name') }}</i>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" name="email" class="form-control" value="{{ $setting->email }}">
                            <i class="text-danger">{{ $errors->first('email') }}</i>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Shipping Cost</label>
                            <input type="text" name="shipping_cost" class="form-control" value="{{ $setting->shipping_cost }}">
                            <i class="text-danger">{{ $errors->first('shipping_cost') }}</i>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Telephone</label>
                            <input type="text" name="no_telp" class="form-control" value="{{ $setting->no_telp }}">
                            <i class="text-danger">{{ $errors->first('no_telp') }}</i>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="">Whatsapp</label>
                            <input type="text" name="whatsapp" class="form-control" value="{{ $setting->whatsapp }}">
                            <i class="text-danger">{{ $errors->first('whatsapp') }}</i>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Logo</label>
                            <input type="file" name="logo" class="form-control">
                            <i class="text-danger">{{ $errors->first('logo') }}</i>
                        </div>
                        <img src="{{ url($setting->logo) }}" width="70">
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="">Image About</label>
                            <input type="file" name="image_about" class="form-control">
                            <i class="text-danger">{{ $errors->first('image_about') }}</i>
                        </div>
                        <img src="{{ url($setting->image_about) }}" width="70">
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="">Tagline</label>
                            <textarea name="tagline" rows="5" class="form-control">{{ $setting->tagline }}</textarea>
                            <i class="text-danger">{{ $errors->first('tagline') }}</i>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="">About</label>
                            <textarea name="about" rows="5" class="form-control">{{ $setting->about }}</textarea>
                            <i class="text-danger">{{ $errors->first('about') }}</i>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="">Address</label>
                            <textarea name="address" id="editor" rows="5" class="form-control">{{ $setting->address }}</textarea>
                            <i class="text-danger">{{ $errors->first('address') }}</i>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label for="">Google Map</label>
                            <input type="text" id="address-input" name="google_map" class="form-control map-input" value="{{ $setting->google_map }}">
                            <i class="text-danger">{{ $errors->first('google_map') }}</i>
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
                            <input type="text" name="address_latitude" id="address-latitude" class="form-control" @if($setting->latitude <> NULL) value="{{ $setting->latitude }}" @else value="0" @endif readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Longitude</label>
                            <input type="text" name="address_longitude" id="address-longitude" class="form-control" @if($setting->longitude <> NULL) value="{{ $setting->longitude }}" @else value="0" @endif readonly>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
    <script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initialize" async defer></script>
    <script type="text/javascript">
        var editor = document.getElementById('editor');
        CKEDITOR.replaceAll(editor);
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
