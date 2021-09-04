@extends('layouts.app')

@section('content')
    <div class="container">

        @foreach($products->chunk(3) as $chunk)
        <div class="row mt-2">
            @foreach($chunk as $product)
                <div class="col-sm">
                    <div class="card" style="width: 18rem;">
                        <img src="{{ asset('uploads/images/products/' . $product->thumbnail) }}" class="card-img-top"
                             alt="{{ $product->title }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->title }}</h5>
                            <h5 class="card-text float-left">${{ $product->price }}</h5>
                            <button data-id="{{ $product->id }}" class="btn btn-primary float-right add-to-cart">Add to cart</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endforeach

    </div>
@endsection


