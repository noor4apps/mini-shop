@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h2>Shopping Cart</h2>
        </div>
        <div class="row pt-5">
            <div class="col-lg-8">
                <table class="table">
                    <thead class="thead-light">
                    <tr>
                        <th></th>
                        <th class="text-center" width="50%">Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Http\Controllers\CartController::cart_items() as $product)
                        <tr id="cart_item_{{ $product->id }}">
                            <td>
                                <button type="button" class="close">
                                    <span class="p-1 remove-from-cart" data-product_id="{{ $product->id }}">&times;</span>
                                </button>
                            </td>
                            <td>
                                <div class="media">
                                    <img src="{{ asset('uploads/images/products/' . $product->thumbnail) }}" width="64" class="mr-3">
                                    <div class="media-body">
                                        {{$product->title}}
                                    </div>
                                </div>
                            </td>
                            <td>{{ $product->price }}</td>
                            <td>
                                <input type="number" style="max-width: 50%;" class="form-control" step="1" min="0" value="{{ \App\Http\Controllers\CartController::get_cart()[$product->id]['qty'] }}" inputmode="numeric">
                            </td>
                            <td><span>$</span>{{ $product->price * \App\Http\Controllers\CartController::get_cart()[$product->id]['qty'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <button class="btn btn-secondary float-right">Update cart</button>
            </div>
            <div class="col-lg-4" style="border: solid #f8f9fa; max-height: 15rem; margin-top: 8px;">
                <table class="table table-borderless">
                    <thead>
                    <tr style="border-bottom: solid #f8f9fa">
                        <th colspan="2">Cart totals</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr style="border-bottom: solid #f8f9fa;">
                        <th>Subtotal</th>
                        <td>$ <span class="mini-cart-subtotal">{{ \App\Http\Controllers\CartController::sub_total() }}</span></td>
                    </tr>
                    <tr>
                        <th>Total</th>
                        <th>$ <span class="final-price">{{ \App\Http\Controllers\CartController::cart_final_price() }}</span></th>
                    </tr>
                    </tbody>
                </table>
                <a class="btn btn-info btn-block">Proceed to checkout</a>
            </div>
        </div>
    </div>
@endsection
