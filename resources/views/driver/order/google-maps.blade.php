@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
<div class="site-section" style="background: #fff">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-black">Google Maps</h2>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="border p-3 mb-3">
                    <p class="text-black font-weight-bold"><b>Address Member</b></p>
                    <p class="text-black">
                        {{ $address_member }}
                    </p>
                </div>
                <div class="border p-3 mb-3">
                    <p class="text-black font-weight-bold"><b>Address Store</b></p>
                    <p class="text-black">
                        {{ $address_shop }}
                    </p>
                </div>
            </div>
            <div class="col-md-6 co-sm-12">
                <div id="map"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initialize" async defer></script>
    <script type="text/javascript">
        function initialize() {
            const directionsRenderer = new google.maps.DirectionsRenderer();
            const directionsService = new google.maps.DirectionsService();
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 14,
                center: { lat: -6.200000, lng: 106.816666 },
            });
            directionsService.route(
                {
                    origin: "{{ $from }}",
                    destination: "{{ $destination }}",
                    travelMode: "DRIVING"
                },
                (response, status) => {
                    directionsRenderer.setDirections(response);
                }
            );
            directionsRenderer.setMap(map);
        }
    </script>
@endsection
