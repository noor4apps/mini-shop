<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function my_products()
    {
        $user = Auth::user();
        $products = Product::where('user_id', $user->id)->get();

        if ($products->count() > 0) {
            return ProductResource::collection($products);
        } else {
            return response()->json(['errors' => true, 'message' => 'No product found'], 200);
        }
    }

    public function store_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required',
            'description'   => 'required',
            'price'         => 'required',
            'qty'           => 'required',
            'thumbnail'     => 'nullable',
        ]);
        if($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 200);
        }

        $product                      = new Product();
        $product->title               = $request->get('title');
        $product->description         = $request->get('description');
        $product->price         = (float) $request->get('price');
        $product->qty     = $request->get('qty');

        $user = Auth::user();
        $product->user_id     = $user->id;


        if ($request->has('thumbnail')) {
            $image      = $request->file('thumbnail');
            $path       = 'uploads/images/products';
            $image_name = time() . '_' . rand(100, 999) . '_' . $image->getClientOriginalName();
            $image->move(public_path($path), $image_name);
            $product->thumbnail = $image_name;
        }

        $product->save();

        return response()->json([
            'errors' => false,
            'message' => 'Product created successfully',
        ], 200);
    }

    public function update_product(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required',
            'description'   => 'required',
            'price'         => 'required',
            'qty'           => 'required',
            'thumbnail'     => 'nullable',
        ]);
        if($validator->fails()) {
            return response()->json(['errors' => true, 'messages' => $validator->errors()], 200);
        }

        $product->title                 = $request->get('title');
        $product->description           = $request->get('description');
        $product->price                 = (float) $request->get('price');
        $product->qty                   = $request->get('qty');


        if ($request->has('thumbnail')) {
            if ($product->thumbnail) {
                File::delete($product->thumbnail);
            }
            $image      = $request->file('thumbnail');
            $path       = 'uploads/images/products';
            $image_name = time() . '_' . rand(100, 999) . '_' . $image->getClientOriginalName();
            $image->move(public_path($path), $image_name);
            $product->thumbnail = $image_name;
        }

        $product->save();

        return response()->json([
            'errors' => false,
            'message' => 'Product updated successfully',
        ], 200);

    }

    public function destroy_product(Product $product)
    {
        if ($product->thumbnail) {
            File::delete($product->thumbnail);
        }

        $product->delete();

        return response()->json([
            'errors' => false,
            'message' => 'Product deleted successfully',
        ], 200);

    }

    public function user_information()
    {
        $user = \auth()->user();
        return response()->json(['errors' => false, 'message' => $user], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['errors' => false, 'message' => 'Successfully logged out']);
    }

}
