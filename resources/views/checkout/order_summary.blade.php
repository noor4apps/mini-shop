@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h2>Order Summary</h2>
            </div>
        </div>
        <div class="row pt-5">
            <!-- Payment Method, Payment Status and Order Total -->
            <div class="col">
                <table>
                    <tr>
                        <th style="width: 60%">Payment Method: </th>
                        <td>{{ $order->payment_method }}</td>
                    </tr>
                    <tr>
                        <th>Payment Status: </th>
                        <td>{{ $order->payment_status }}</td>
                    </tr>
                    <tr>
                        <th>Order Status: </th>
                        <td>{{ $order->payment_status }}</td>
                    </tr>
                    <tr>
                        <th>Order Total: </th>
                        <td>{{ $order->order_total }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row pt-5">
            <!-- Order details -->
            <div class="col">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-center" width="50%">Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($order_details as $order_detail)
                        <tr>
                            <td>
                                <div class="media">
                                    <img src="{{ asset('uploads/images/products/' . $order_detail->product->thumbnail) }}" width="64" class="mr-3">
                                    <div class="media-body">
                                        {{$order_detail->product->title}}
                                    </div>
                                </div>
                            </td>
                            <td>$ {{ $order_detail->product_price }}</td>
                            <td>{{ $order_detail->product_qty }}</td>
                            <td>$ {{ $order_detail->total_price }}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row py-5">
            <!-- Billing Address -->
            <div class="col-md-6">
                <h4>Billing Address</h4>
                <div>{{ $order->address_line_1 }}</div>
                <div>{{ $order->address_line_2 }}</div>
            </div>
            <!-- Shipping Address -->
            <div class="col-md-6">
                <h4>Shipping Address</h4>
                @if($order->different_shipping == true)
                    <div>{{ $order->s_address_line_1 }}</div>
                    <div>{{ $order->s_address_line_2 }}</div>
                @else
                    <div>Same Billing Address</div>
                @endif
            </div>
        </div>
    </div>
@endsection
