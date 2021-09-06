<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
{{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Cart -->
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path class="heroicon-ui" d="M17 16a3 3 0 1 1-2.83 2H9.83a3 3 0 1 1-5.62-.1A3 3 0 0 1 5 12V4H3a1 1 0 1 1 0-2h3a1 1 0 0 1 1 1v1h14a1 1 0 0 1 .9 1.45l-4 8a1 1 0 0 1-.9.55H5a1 1 0 0 0 0 2h12zM7 12h9.38l3-6H7v6zm0 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm10 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
                                <span class="badge badge-light cart-quantity-highlighter">{{ App\Http\Controllers\CartController::total_in_cart() }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right p-2 append-mini-cart-items" style="min-width: 21rem" aria-labelledby="navbarDropdown">
                                {!! App\Http\Controllers\CartController::get_markup() !!}
                            </div>
                        </li>

                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
{{--    <script src="{{ asset('js/app.js') }}"></script>--}}
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        $(document).on('click', '.add-to-cart', function (e) {
            e.preventDefault()
            let p_id = $(this).data('id')
            let p_qty = $('#quantity_' + p_id).val()
            p_qty = p_qty == undefined ? 1 : p_qty

            axios.post('{{ route('add-to-cart') }}', {
                id: p_id,
                qty: p_qty
            }).then(res => {
                    $('.cart-quantity-highlighter').text(res.data.count)
                    $('.append-mini-cart-items').html(res.data.markup)
                    $('.mini-cart-subtotal').text(res.data.subtotal)
                    // swtoaster('success', 'Added to cart.')
                }).catch(err => console.log(err))
        })

        $(document).on('click', '.remove-from-cart', function(e) {
            e.preventDefault()
            let p_id = $(this).data('product_id')
            axios.post('{{ route('remove-from-cart') }}', {
                id: p_id
            })
                .then(res => {
                    $('.cart-quantity-highlighter').text(res.data.count)
                    $('.append-mini-cart-items').html(res.data.markup)
                    $('.mini-cart-subtotal').text(res.data.subtotal)
                    $('.final-price').text(res.data.total)
                    $('#cart_item_' + p_id).remove()
                    // swtoaster('success', 'Cart item removed.')
                    if(res.data.count == 0 && '{{ request()->route()->getName() }}' == 'carts.index') {
                        window.location.href = '{{ url('/') }}'
                    }
                })
                .catch(err => console.log(err))
        })
        
        $(document).on('keyup change', '.click-change-qty', function(e) {
            e.preventDefault()
            let pid = $(this).data('product_id')
            let pqty = $(this).val();

            axios.post('{{ route('carts.update') }}', {
                id: pid,
                qty: pqty
            })
                .then(res => {
                    $('.append-mini-cart-items').html(res.data.markup)
                    $('.mini-cart-subtotal').text(res.data.subtotal)
                    $('.final-price').text(res.data.total)
                    $('#then-price-by-qty-'+pid).text(res.data.price_by_qty)

                    // swtoaster('success', 'Cart item removed.')
                })
                .catch(err => console.log(err))
        })
    </script>
</body>
</html>
