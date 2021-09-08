<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * return  view checkout.index
     */
    public function checkout()
    {
        /**
         * Go back to the products and do not proceed to checkout if the cart is empty
         */
        if (!count(CartController::get_cart())) {
            return redirect()->intended('/');
        }

        $orderDetails                     = new Collection();
        $orderDetails->address_line_1     = '';
        $orderDetails->address_line_2     = '';


        $orderDetails->different_shipping = '';

        $orderDetails->s_address_line_1   = '';
        $orderDetails->s_address_line_2   = '';


        /**
         * Fetching details of the last order of the registered user
         */
        if (auth()->check()) {
            $order = Order::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();

            if ($order) {
                $orderDetails = $order;
            }
        }

        return view('checkout.index', compact('orderDetails'));

    }

    /**
     * Register and login the user if he is new
     * Create a new order and order details
     */
    public function store(Request $request)
    {
        $request->validate([
            'address_line_1' => 'required',
        ]);

        /**
         * Fetch an existing user else register as a new user
         */
        if (auth()->check()) {
            $user = User::where('id', auth()->user()->id)->first();
        } else {
            $request->validate([
                'name'     => 'required',
                'email'    => 'required|unique:users,email',
                'password' => 'required|min:8',
            ]);

            $user           = new User();
            $user->name = $request->input('name');
            $user->type = 'customer';
            $user->email    = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->save();
        }


        /**
         * Create a new order
         */
        $order                 = new Order();
        $order->address_line_1 = $request->input('address_line_1');
        $order->address_line_2 = $request->input('address_line_2');

        if ($request->has('different_shipping')) {
            $order->different_shipping = true;
        }

        $order->s_address_line_1 = $request->input('s_address_line_1');
        $order->s_address_line_2 = $request->input('s_address_line_2');
;
        $order->payment_method   = $request->input('payment_method');

        $order->sub_total        = CartController::sub_total();
        $order->order_total      = CartController::cart_final_price();

        $order->payment_status   = 'pending';
        $order->order_status     = 'pending';

        $order->user_id        = $user->id;
        $order->save();

        /**
         * Create order details for each cart products
         */
        $order_details_array = array();
        $get_cart            = CartController::get_cart();
        $order_id            = $order->id;

        if (count($get_cart)) {
            foreach ($get_cart as $cart_key => $cart_product) {
                $product_price = Product::whereId($cart_key)->first()->price;
                $new_item      = array(
                    'order_id'      => $order_id,
                    'product_id'    => $cart_key,
                    'product_price' => $product_price,
                    'product_qty'   => $cart_product['qty'],
                    'total_price'   => $product_price * $cart_product['qty'],
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s')
                );
                array_push($order_details_array, $new_item);
            }

        }
        $order_created = OrderDetail::insert($order_details_array);

        /**
         * Subtract the quantity of products sold (each cart products)
         */
        if($order_created) {
            foreach ($get_cart as $cart_key => $cart_product) {
                $product = Product::FindOrFail($cart_key);
                $product->update([
                    'qty' => $product->qty - $cart_product['qty']
                ]);
            }
        }

        /**
         * Log in if the user is not already logged in
         * Empty the cart and redirect to order placed
         */
        if ($order_created) {
            if (!auth()->check()) {
                $credentials = $request->only('email', 'password');
                Auth::attempt($credentials);
            }
            if (CartController::make_cart_empty()) {
                return redirect()->route('order-placed', $order_id)->with('success', 'Order placed successfully');
            } else {
                return redirect()->back()->with('error', 'Please try again');
            }
        }
        return redirect()->back()->with('error', 'Please try again');
    }

    /**
     * Return view order summary with order details
     */
    public function order_placed(Order $order)
    {
        $order_details = OrderDetail::whereOrderId($order->id)->get();
        return view('checkout.order_summary', compact('order', 'order_details'));
    }

}
