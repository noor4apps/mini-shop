<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{

    /**
     * Add product to cart
     * return [json "total_in_cart, cart_items, get_markup, sub_total"]
     */
    public function add_to_cart(Request $request)
    {
        $productId  = $request->id;
        $productQty = $request->qty;

        if (auth()->check()) {
            $user_cart = Cart::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
            if ($user_cart) {
                // Check existing data
                $items                 = json_decode($user_cart->cart_items, true);
                $items[$productId]     = ['qty' => $productQty];
                $user_cart->cart_items = json_encode($items);
            } else {
                $user_cart             = new Cart();
                $user_cart->user_id    = auth()->user()->id;
                $user_cart->cart_items = json_encode([$productId => ['qty' => $productQty]]);
            }
            $user_cart->save();
        } else {
            $request->session()->put('cart.' . $productId . '.qty', $productQty);
        }

        return response()->json([
            'count'    => self::total_in_cart(),
            'items'    => self::cart_items(),
            'markup'   => self::get_markup(),
            'subtotal' => self::sub_total()
        ]);
    }

    /**
     * Get cart with quantity and product IDs
     * return [array cart]
     */
    public static function get_cart()
    {
        if (auth()->check()) {
            $user_cart = Cart::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();

            // $user_cart = auth()->user()->cart;
            if ($user_cart) {
                return json_decode($user_cart->cart_items, true);
            }
            return array();
        }
        if (session()->has('cart')) {
            return session('cart');
        } else {
            return array();
        }
    }

    /**
     * Get total number of individual products in cart
     * return [int productCount]
     */
    public static function total_in_cart()
    {
        if (auth()->check()) {
            $user_cart = Cart::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
            if ($user_cart) {
                return count(json_decode($user_cart->cart_items, true));
            } else {
                return 0;
            }
        }
        return session('cart') ? count(session('cart')) : 0;
    }

    /**
     * Get all cart items
     * return [array products]
     */
    public static function cart_items()
    {
        $sProducts = [];

        if (auth()->check()) {
            $user_cart = Cart::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
            if ($user_cart) {
                $items = $user_cart->cart_items;
                if ($items) {
                    $cart_items_array = json_decode($items, true);
                    $p_ids            = array_keys($cart_items_array);
                    $sProducts        = Product::whereIn('id', $p_ids)->get();
                }
                return $sProducts;
            }
            return $sProducts;
        }

        if (session('cart') && count(session('cart')) > 0) {
            $p_ids     = array_keys(session('cart'));
            $sProducts = Product::whereIn('id', $p_ids)->get();
        }
        return $sProducts;
    }

    /**
     * Mini cart markup generation on the fly for ajax request
     */
    public static function get_markup()
    {
        $products     = self::cart_items();
        $cart_service = new CartService();
        return $cart_service->mini_cart_markup($products);
    }

    /**
     * Returns subTotal price of the products
     */
    public static function sub_total()
    {
        $products   = self::cart_items();
        $cart_items = self::get_cart();
        $subTotal   = 0;
        if (count($products)) {
            foreach ($products as $product) {
                $subTotal += $product->price * $cart_items[$product->id]['qty'];
            }
        }
        return sprintf("%0.2f", $subTotal);
    }

    /**
     * Remove item from cart
     * return [json "total_in_cart, cart_items, get_markup, sub_total"]
     */
    public function remove_from_cart(Request $request)
    {
        if (auth()->check()) {
            $user_cart = Cart::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
            if ($user_cart) {
                $cart_items = json_decode($user_cart->cart_items, true);
                unset($cart_items[$request->id]);
                $cart             = Cart::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
                $cart->cart_items = json_encode($cart_items);
                $cart->save();
            }
        }
        if (session('cart') && count(session('cart')) > 0) {
            session()->forget('cart.' . $request->id);
        }

        return response()->json([
            'count'    => self::total_in_cart(),
            'items'    => self::cart_items(),
            'markup'   => self::get_markup(),
            'subtotal' => self::sub_total(),
            'total'    => self::cart_final_price()
        ]);
    }

    /**
     * cart page view
     */
    public function show_cart_page()
    {
        if (self::total_in_cart() > 0) {
            return view('carts.index');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Returns the final price after tax, discount and shipping charge calculation
     */
    public static function cart_final_price()
    {
        $subTotal = self::sub_total();

        // Coupon discount
        $couponAmount = 0;

        // we will add discount, tax and shipping charge here
        $tax            = 0;
        $shippingCharge = 0;
        $total          = $subTotal + $tax + $shippingCharge - $couponAmount;
        return $total;
    }

    /**
     * Update item quantity
     * return [json "markup, subtotal, total, price_by_qty"]
     */
    public function update_item_qty(Request $request)
    {
        if (auth()->check()) {
            $user_cart = Cart::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
            if ($user_cart) {
                $items                 = json_decode($user_cart->cart_items, true);
                $items[$request->id]     = ['qty' => $request->qty];
                $user_cart->cart_items = json_encode($items);
                $user_cart->save();
            }
        }
        else {
            $request->session()->put('cart.' . $request->id . '.qty', $request->qty);
        }
        return response()->json([
            'markup'   => self::get_markup(),
            'subtotal' => self::sub_total(),
            'total'    => self::cart_final_price(),
            'price_by_qty' => self::calc_price_by_qty($request->id, $request->qty)
        ]);
    }

    /**
     * Calculate product price multiplied by quantity
     * return [int price_by_qty]
     */
    public static function calc_price_by_qty($p_id, $p_qty)
    {
        $product = Product::where('id', $p_id)->first();
        $price_by_qty = $product->price * ((int)$p_qty);
        return sprintf("%0.2f", $price_by_qty);
    }

    /**
     * Make Cart Empty
     * return [boolean]
     */
    public static function make_cart_empty()
    {
        if (auth()->check()) {
            $user_cart = Cart::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
            if ($user_cart) {
                $user_cart->delete();
                session()->forget('cart');
                return true;
            }
        }
        if (session('cart') && count(session('cart')) > 0) {
            session()->forget('cart');
            return true;
        }
        return false;
    }


}
