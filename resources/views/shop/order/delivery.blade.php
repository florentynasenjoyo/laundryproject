@extends('layouts.app')
@include('layouts.partials.css')
@include('layouts.partials.js')

@section('content')
    <div class="site-section" style="background: #fff;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <h2 class="h3 mb-3 text-black">Order Details</h2>
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

                        {!! Form::open(['method' => 'post', 'route' => ['shop.order.postdelivery', $order->id], 'enctype' => 'multipart/form-data']) !!}
                        @csrf
                        @method('PUT')

                        <div class="border p-3 mb-3">
                            <p class="text-black font-weight-bold"><b>Proof of Payment</b></p>
                            <a href="{{ url($order->proof_payment) }}" target="_blank">
                                <img src="{{ url($order->proof_payment) }}" width="180">
                            </a>
                        </div>

                        <div class="border p-3 mb-3">
                            <p class="text-black font-weight-bold"><b>Driver List</b></p>
                            <select name="driver" class="driverList form-control" required></select>
                            <i class="text-danger">{{ $errors->first('driver') }}</i>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg py-3 btn-block">Delivery to Customer</button>
                        </div>

                        {!! Form::close() !!}

                    </div>

                </div>
            </div>
            <!-- </form> -->
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script type="text/javascript">

        $('.driverList').select2({
            placeholder: 'Select Driver',
            ajax: {
                url: "{{ route('shop.order.driver') }}",
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
