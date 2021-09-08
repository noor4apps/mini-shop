@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h2>Checkout</h2>
        </div>

        <form method="POST" action="{{ route('place-order') }}">
            @csrf
            <div class="row pt-3">


                <div class="col-lg-8">
                @guest
                        <!-- Register -->
                        <div class="card mb-2">
                            <div class="card-header">{{ __('Register') }}</div>

                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="inputName">Name</label>
                                        <input name="name" type="text" class="form-control" id="inputName">
                                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputEmail4">Email</label>
                                        <input name="email" type="email" class="form-control" id="inputEmail4">
                                        @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputPassword4">Password</label>
                                        <input name="password" type="password" class="form-control" id="inputPassword4">
                                        @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                @endguest
                        <!-- Billing Address -->
                        <div class="card mb-2">
                            <div class="card-header">{{ __('Billing Address') }}</div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="inputAddress1">Address 1</label>
                                    <input name="address_line_1" type="text" class="form-control" id="inputAddress1" value="{{ old('address_line_1') ? old('address_line_1') : $orderDetails->address_line_1 }}">
                                    @error('address_line_1')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="inputAddress2">Address 2</label>
                                    <input name="address_line_2" type="text" class="form-control" id="inputAddress2" value="{{ old('address_line_2') ? old('address_line_2') : $orderDetails->address_line_2 }}">
                                    @error('address_line_2')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>
                        <!-- check Different Shipping Address -->
                        <div class="mb-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="different_shipping" name="different_shipping">
                                <label class="form-check-label" for="differentShippingAddress">Different Shipping Address?</label>
                            </div>
                        </div>
                        <!-- Shipping Address -->
                        <div class="card shipping-address-box d-none">
                            <div class="card-header">{{ __('Shipping Address') }}</div>

                            <div class="card-body">
                                <div class="form-group">
                                    <label for="sInputAddress1">Address 1</label>
                                    <input name="s_address_line_1" type="text" class="form-control" id="sInputAddress1" value="{{ old('s_address_line_1') }}">
                                    @error('s_address_line_1')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="sInputAddress2">Address 2</label>
                                    <input name="s_address_line_2" type="text" class="form-control" id="sInputAddress2" value="{{ old('s_address_line_2') }}">
                                    @error('s_address_line_2')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>

                </div>

                <div class="col-lg-4 p-2" style="background-color: #baf1c5; max-height: 18rem;">
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
                    <!-- radio Payment Method -->
                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cashOnDelivery" value="Cash On Delivery" checked>
                            <label class="form-check-label" for="cashOnDelivery">
                                Cash On Delivery
                            </label>
                        </div>
                    </div>

                    <button type="submit"  class="btn btn-success btn-block">Place order</button>

                </div>

            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        if( $('#different_shipping').prop('checked') ) {
            $('.shipping-address-box').removeClass('d-none');
        }
        $('#different_shipping').on('click', function(e) {
            $('.shipping-address-box').toggleClass('d-none');
        })
    </script>
@endsection
