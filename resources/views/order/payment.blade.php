@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section" style="background: #fff;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    {{-- <div class="row mb-5">
                        <div class="col-md-12">
                            <h2 class="h3 mb-3 text-black">Coupon Code</h2>
                            <div class="p-3 p-lg-5 border">

                                <label for="c_code" class="text-black mb-3">Enter your coupon code if you have
                                    one</label>
                                <div class="input-group w-75">
                                    <input type="text" class="form-control" id="c_code" placeholder="Coupon Code"
                                        aria-label="Coupon Code" aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary btn-sm" type="button"
                                            id="button-addon2">Apply</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div> --}}

                    <div class="row mb-5">
                        <div class="col-md-6 col-sm-12 mb-5 mb-md-0">
                            <h2 class="h3 mb-3 text-black">Google Maps</h2>
                            <div class="p-3 p-lg-5 border">
                                <div id="map"></div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h2 class="h3 mb-3 text-black">Your Order</h2>
                            <div class="p-3 p-lg-5 border">
                                <table class="table site-block-order-table mb-5">
                                    <thead>
                                        <th>Service</th>
                                        <th>Amount</th>
                                        <th>Total</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $order->name }}</td>
                                            <td><strong class="mx-2">x</strong> {{ $order->amount }}</td>
                                            <td>
                                                @if ($order->type==0)
                                                    @php
                                                        $price = $order->price_weight;
                                                    @endphp
                                                @else
                                                    @php
                                                        $price = $order->price_unit;
                                                    @endphp
                                                @endif
                                                {{ 'Rp. '.number_format($price) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Shipping Cost</td>
                                            <td></td>
                                            <td>{{ 'Rp. '.number_format($order->shipping_cost) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-black font-weight-bold"><strong>Subtotal</strong></td>
                                            <td></td>
                                            <td class="text-black">{{ 'Rp. '.number_format($price + $order->shipping_cost) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-black font-weight-bold"><strong>Order Total</strong></td>
                                            <td></td>
                                            <td class="text-black font-weight-bold"><strong>{{ 'Rp. '.number_format($order->price_total) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="border p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="font-weight-bold text-black">Method Payment</p>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <img src="{{ url($order->logo) }}" width="70">
                                        </div>
                                        <div class="col-md-4 col-sm-12"></div>
                                        <div class="col-md-4 col-sm-12 text-left">
                                            <h6 class="text-black">{{ $order->no_rekening }}</h6>
                                            <h6 class="text-black">{{ $order->name_rekening }}</h6>
                                        </div>
                                    </div>
                                </div>

                                {!! Form::open(['method' => 'post', 'route' => ['order.payment_process', $order->id], 'enctype' => 'multipart/form-data']) !!}
                                @csrf
                                @method('PUT')

                                <div class="border p-3 mb-3">
                                    <p class="text-black font-weight-bold"><b>Proof of Payment</b></p>
                                    <input type="file" name="proof_payment" class="form-control">
                                    <i class="text-danger">{{ $errors->first('proof_payment') }}</i>
                                </div>

                                <div class="border p-3 mb-3">
                                    <p class="text-black font-weight-bold"><b>Driver List</b></p>
                                    <select name="driver" class="driverList form-control" required></select>
                                    <i class="text-danger">{{ $errors->first('driver') }}</i>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg py-3 btn-block">Place Order</button>
                                </div>

                                {!! Form::close() !!}

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- </form> -->
        </div>
    </div>
@endsection

@section('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initialize" async defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">
        function initialize() {
            var coordinates = {
                lat: 37.77,
                lng: -1.447
            };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: { lat: {{ Auth::user()->latitude }}, lng: {{ Auth::user()->longitude }} },
            });

            const tourStops = [
                [
                    {
                        lat: {{ Auth::user()->latitude }},
                        lng: {{ Auth::user()->longitude }},
                    },
                    "{{ Auth::user()->name }}",
                    '<h2 class="map-title">{{ Auth::user()->name }}</h2>' +
                    '<p class="map-desc">{{ Auth::user()->address }}</p>' +
                    '<ul class="map-desc">' +
                        '<li>Email : {{ Auth::user()->email }}</li>' +
                        '<li>Whatsapp : {{ Auth::user()->no_hp }}</li>' +
                    '</ul>'
                ],
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

        $('.driverList').select2({
            placeholder: 'Select Driver',
            ajax: {
                url: "{{ route('order.driver') }}",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item.id
                        }
                    })
                };
                },
                cache: true
            }
        });
    </script>
@endsection
