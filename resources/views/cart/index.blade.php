@extends('layouts.main')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cart/index.css') }}">
@endpush


@section('content')

    <div id="toast-container"></div>

    <!-- Navbar-->
    <nav class="navbar">
        <a href="{{ url('/') }}">{{ config('app.name') }}</a>
    </nav>

    <div class="container">

    <div class="cart-items-container">
        <h2>Your Shopping Cart</h2>

        <div>
            @if($cart->isEmpty())
                <p class="text-center">Your cart is empty</p>
            @else
                <div class="cart-items-container">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                                <tr data-product-id="{{ $item->product_id }}">
                                    <td><img src="{{ asset($item->product->image_url) }}" alt="{{ $item->product->name }}" class="cart-item-img"></td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>${{ number_format($item->product->price, 2) }}</td>
                                    <td>{{ $item->product->stock }}</td>
                                    <td>
                                        <div class="cart-quantity">
                                            <button id="minus-btn-{{ $item->product_id }}" onclick="updateCartQuantity({{ $item->product_id }}, -1)" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                            <input type="number" id="cart-qty-{{ $item->product_id }}" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" oninput="manualUpdateQuantity({{ $item->product_id }})">
                                            <button id="plus-btn-{{ $item->product_id }}" onclick="updateCartQuantity({{ $item->product_id }}, 1)" {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>+</button>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                                    <td><button onclick="removeCart({{ $item->product_id }})" class="remove-btn">X</button></td>
                                </tr>
                            @endforeach
                        </tbody>

                        @php
                            $subtotal = $cart->sum(fn($item) => $item->quantity * $item->price);
                            $gstAmount = $tax ? ($subtotal * ($tax->gst / 100)) : 0;
                            $qstAmount = $tax ? ($subtotal * ($tax->qst / 100)) : 0;
                            $total = $subtotal + $gstAmount + $qstAmount;
                        @endphp

                        <tfoot>
                            <tr>
                                <td colspan="5" style="text-align: right; font-weight: bold;">Subtotal:</td>
                                <td colspan="2" id="subtotal-value">${{ number_format($subtotal, 2) }}</td>
                            </tr>
                            <!-- Coming from db -->
                            @if($tax)
                                <tr>
                                    <td colspan="5" style="text-align: right; font-weight: bold;">GST ({{ $tax->gst }}%):</td>
                                    <td colspan="2" id="gst-value" data-gst="{{ $tax->gst }}">${{ number_format($gstAmount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="5" style="text-align: right; font-weight: bold;">QST ({{ $tax->qst }}%):</td>
                                    <td colspan="2" id="qst-value" data-qst="{{ $tax->qst }}">${{ number_format($qstAmount, 2) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="5" style="text-align: right; font-size: 22px; font-weight: bold;">Total:</td>
                                <td colspan="2" id="final-total" style="font-size: 22px; font-weight: bold;">${{ number_format($total, 2) }}</td>
                            </tr>
                            <!-- Checkout Button Row (Inside Table, No Borders) -->
                            <tr class="checkout-row">
                                <td colspan="7" style="text-align: right; padding-top: 20px;">
                                    <button class="checkout-btn">Checkout</button>
                                </td>
                            </tr>
                        </tfoot>


                    </table>
                </div>
            @endif
        </div>
    </div>



    </div>


@endsection

@push('scripts')
    <script src="{{ asset('assets/js/global.js') }}"></script>
    <script src="{{ asset('assets/js/cart/index.js') }}"></script>
@endpush

