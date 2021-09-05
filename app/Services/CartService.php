<?php

namespace App\Services;

use App\Http\Controllers\CartController;

class CartService {

    public function mini_cart_markup($products) {
        $data = '<ul class="list-unstyled">';
        foreach($products as $product) {
            $data .= '<li class="media my-2 pb-1 border-bottom" id="cart_item_'. $product->id .'">
                        <img src="'.asset('uploads/images/products/' . $product->thumbnail).'" width="64" class="mr-3">
                        <div class="media-body">
                            <h5 class="mt-0 mb-1">'. $product->title .'</h5>
                            <span class="quantity"> '. CartController::get_cart()[$product->id]['qty'] .' ×<span> '. $product->price .' $</span></span>
                        </div>
                        <button type="button" class="close">
                            <span class="p-1 remove-from-cart" data-product_id="'. $product->id .'">&times;</span>
                        </button>
                      </li>';
        }
        $data .= '</ul>';
        if(count($products) > 0) {
            $data .= '<h5 class="p-2">Subtotal: <span class="mini-cart-subtotal">'. CartController::sub_total() .'</span> $</h5>
                    <a class="dropdown-item text-center border mb-2" href="'. route('carts.index') .'">
                        View cart
                    </a>
                    <a class="dropdown-item text-center border bg-light">
                        Checkout
                    </a>';
        } else {
            $data .= '<h6 class="p-4 text-center">Empty Cart</h6>';
        }
        return $data;
    }
}
