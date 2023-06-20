@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div id="map"></div>
@endsection

@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initialize" async defer></script>
    <script type="text/javascript">
        function initialize() {
            var coordinates = {
                lat: 37.77,
                lng: -1.447
            };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 5,
                center: { lat: -6.200000, lng: 106.816666 },
            });

            const tourStops = [
                @foreach ($shops as $shop)
                    [
                        {
                            lat: {{ $shop->latitude }},
                            lng: {{ $shop->longitude }},
                        },
                        "{{ $shop->name }}",
                        '<h2 class="map-title">{{ $shop->name }}</h2>' +
                        '<p class="map-desc">{{ $shop->address }}</p>' +
                        '<ul class="map-desc">' +
                            '<li>Email : {{ $shop->email }}</li>' +
                            '<li>Whatsapp : {{ $shop->no_hp }}</li>' +
                        '</ul>'
                    ],
                @endforeach
            ];


            tourStops.forEach(([position, title, desc], i) => {

                var measle = new google.maps.Marker({
                    position: position,
                    map: map,
                    icon: {
                        url: "https://maps.gstatic.com/intl/en_us/mapfiles/markers2/measle.png",
                        size: new google.maps.Size(7, 7),
                        anchor: new google.maps.Point(4, 4)
                    }
                });
                var marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    icon: {
                        url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
                        labelOrigin: new google.maps.Point(75, 32),
                        size: new google.maps.Size(32,32),
                        anchor: new google.maps.Point(16,32)
                    },
                    label: {
                        text: title,
                        color: "#C70E20",
                        fontWeight: "bold",
                    }
                });

                var infowindow = new google.maps.InfoWindow({
                    content: desc,
                });

                marker.addListener("click", () => {
                    infowindow.open({
                        anchor: marker,
                        map,
                    });
                });
            });

        }

        // function initialize() {
        //     const directionsRenderer = new google.maps.DirectionsRenderer();
        //     const directionsService = new google.maps.DirectionsService();
        //     const map = new google.maps.Map(document.getElementById("map"), {
        //         zoom: 14,
        //         center: {
        //             lat: 37.77,
        //             lng: -1.447,
        //         },
        //     });

        //     directionsRenderer.setMap(map);
        //     calculateAndDisplayRoute(directionsService, directionsRenderer);
        //     document.getElementById("mode").addEventListener("change", () => {
        //         calculateAndDisplayRoute(directionsService, directionsRenderer)
        //     });
        // }

        // function calculateAndDisplayRoute(directionsService, directionsRenderer){
        //     const selectedMode = document.getElementById("mode").value;

        //     directionsService
        //     .route({
        //         origin: document.getElementById("from").value,
        //         destination: document.getElementById("to").value,

        //         travelMode: google.maps.TravelMode.DRIVING,
        //     })
        //     .then((response) => {
        //         directionsRenderer.setDirections(response);
        //     })
        //     .catch((e) => window.alert("Direction request failed !! " + status))
        // }
    </script>
@endsection
