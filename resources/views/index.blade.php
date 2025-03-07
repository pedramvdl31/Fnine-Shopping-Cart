@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
@endpush

@section('content')

    <div id="toast-container"></div>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="#">{{ config('app.name') }}</a>
        <div class="cart-container">
            <a href="javascript:void(0);" onclick="toggleCartDropdown()">
                <i class="fa-solid fa-cart-shopping cart-icon"></i> <!-- FontAwesome Icon -->
                <span class="cart-badge">{{ $cart->sum('quantity') }}</span>
            </a>

            <!-- Carts dropdown -->
            <div class="cart-dropdown">
                <div class="cart-items">
                    @if($cart->isEmpty())
                        <p class="text-center">Cart is empty</p>
                    @else
                        @foreach($cart as $item)
                            @php
                                $isMinQuantity = $item->quantity <= 1;
                                $isMaxQuantity = $item->quantity >= $item->product->stock;
                            @endphp

                            <div class="cart-item">
                                <img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}" class="cart-item-img">
                                <div class="cart-info">
                                    <p>{{ $item->product->name }}</p>
                                    <div class="cart-quantity">
                                        <button onclick="updateCartQuantity({{ $item->product_id }}, -1)" 
                                                {{ $isMinQuantity ? 'disabled' : '' }}>-</button>
                                        
                                        <input type="number" id="cart-qty-{{ $item->product_id }}" 
                                               value="{{ $item->quantity }}" 
                                               min="1" max="{{ $item->product->stock }}" 
                                               oninput="manualUpdateQuantity({{ $item->product_id }})">

                                        <button onclick="updateCartQuantity({{ $item->product_id }}, 1)" 
                                                {{ $isMaxQuantity ? 'disabled' : '' }}>+</button>
                                    </div>
                                    <br>
                                    <small>Total: {{ number_format($item->quantity * $item->price, 2) }}</small>
                                </div>
                                <button onclick="removeCart({{ $item->product_id }})" class="remove-btn">X</button>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="cart-footer">
                    <a href="{{ route('cart.index') }}" class="view-cart-btn {{ $cart->isEmpty() ? 'disabled-btn' : '' }}">
                        View Cart
                    </a>
                </div>
            </div>



        </div>
    </nav>


    <div class="container">
        <div class="row">

            @if($products->isEmpty())
                <div class="no-products">
                    <h3>No products available.</h3>
                    <p>Check back later for new stock!</p>
                </div>
            @else
                @foreach($products as $product)

                    <!-- checking the cart to handle buttons if that item is already in the stock and  -->
                    <!-- the qty is equal to the stock number -->
                    @php
                        $cartItem = $cart->firstWhere('product_id', $product->id);
                    @endphp

                    <div class="product-card">
                        <img id="product-img-{{ $product->id }}" src="{{ asset($product->image_url) }}" class="product-img" alt="{{ $product->name }}">
                        <h5 class="product-title">{{ $product->name }}</h5>
                        <p class="price">${{ number_format($product->price, 2) }}</p>
                        <p class="stock">Stock: {{ $product->stock }}</p>
                        <label for="quantity-{{ $product->id }}">Qty:</label>

                        <input type="number" id="quantity-{{ $product->id }}" 
                               value="1" min="1" max="{{ $product->stock }}"
                               oninput="checkStock({{ $product->id }}, {{ $product->stock }})"
                               {{ ($cartItem && $cartItem->quantity >= $product->stock) ? 'disabled' : '' }}>

                        <button id="add-to-cart-{{ $product->id }}" class="add-to-cart"
                                data-id="{{ $product->id }}" 
                                data-stock="{{ $product->stock }}"
                                onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})"
                                {{ ($cartItem && $cartItem->quantity >= $product->stock) ? 'disabled' : '' }}>
                            Add to Cart
                        </button>
                    </div>
                @endforeach
            @endif

        </div>

    </div>

    <!-- Paggination -->
    <div class="pagination-container">
        @if ($products->lastPage() > 1)
            <ul class="pagination">
                <!-- Previous Page -->
                @if ($products->onFirstPage())
                    <li class="disabled"><span>&laquo;</span></li>
                @else
                    <li><a href="{{ $products->previousPageUrl() }}" aria-label="Previous">&laquo;</a></li>
                @endif

                <!-- Page Numbers -->
                @foreach(range(1, $products->lastPage()) as $page)
                    <li class="{{ ($products->currentPage() == $page) ? 'active' : '' }}">
                        <a href="{{ $products->url($page) }}">{{ $page }}</a>
                    </li>
                @endforeach

                <!-- Next Page -->
                @if ($products->hasMorePages())
                    <li><a href="{{ $products->nextPageUrl() }}" aria-label="Next">&raquo;</a></li>
                @else
                    <li class="disabled"><span>&raquo;</span></li>
                @endif
            </ul>
        @endif
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/global.js') }}"></script>
    <script src="{{ asset('assets/js/homepage/cart.js') }}"></script>
    <script src="{{ asset('assets/js/homepage/index.js') }}"></script>
@endpush
