@extends('layouts.main')

@section('styles')

@endsection

@section('scripts')

@endsection

@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cart - {{ config('app.name') }}</title>
    <!-- For icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cart/index.css') }}">
</head>
<body>

<div id="toast-container"></div>

<!-- Navbar-->
<nav class="navbar">
    <a href="{{ url('/') }}">{{ config('app.name') }}</a>
</nav>

<div class="container">
    <h2>Your Shopping Cart</h2>

    <div class="cart-items-container">
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
                        <!-- Table of items in the cart -->
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
                </table>
            </div>

            <!-- Cart summary, total, subtotal, etc. -->
            <div class="cart-summary">

                @php
                    $subtotal = $cart->sum(fn($item) => $item->quantity * $item->price);
                    $gstAmount = $tax ? ($subtotal * ($tax->gst / 100)) : 0;
                    $qstAmount = $tax ? ($subtotal * ($tax->qst / 100)) : 0;
                    $total = $subtotal + $gstAmount + $qstAmount;
                @endphp

                <h3>Subtotal: <span id="subtotal-value">${{ number_format($subtotal, 2) }}</span></h3>

                @if($tax)
                    <p>GST ({{ $tax->gst }}%): <span id="gst-value" data-gst="{{ $tax->gst }}">${{ number_format($gstAmount, 2) }}</span></p>
                    <p>QST ({{ $tax->qst }}%): <span id="qst-value" data-qst="{{ $tax->qst }}">${{ number_format($qstAmount, 2) }}</span></p>
                @endif

                <h2>Total: <span id="final-total">${{ number_format($total, 2) }}</span></h2>

                <button class="checkout-btn">Checkout</button>
            </div>


        @endif
    </div>
</div>

<script src="{{ asset('assets/js/global.js') }}"></script>
<script src="{{ asset('assets/js/cart/index.js') }}"></script>

</body>
</html>

@endsection
